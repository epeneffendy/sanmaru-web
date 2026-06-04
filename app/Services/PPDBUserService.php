<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use TaylorNetwork\UsernameGenerator\Generator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\RegistrantConfirmed;
use App\Mail\RegistrationConfirmed;
use App\Mail\PaymentConfirmed;
use App\Services\EmailService;
use App\Models\PPDBUserStage;
use App\Traits\ImageHandler;
use App\Helpers\PriceHelper;
use Illuminate\Support\Str;
use App\Models\PPDBUser;
use App\Models\Student;
use App\Models\Unit;
use App\Models\User;
use App\Lib\DbTrx;
use App\Models\Finance;
use App\Models\StudentBills;
use App\Services\StudentBillService;
use App\Models\PaymentDispensations;
use App\Services\PaymentDispensationsService;
use App\Models\Voucher;
use Exception;

class PPDBUserService
{
    use ImageHandler;

    public function create($params)
    {
        $data = $this->generateUserData($params);
        $user = new User($data);
        $user->save();
        $data = $this->populateDataFromParams($params);
        $ppdb = new PPDBUser($data);
        $ppdb->user_id = $user->id;
        $ppdb->save();
        return $ppdb;
    }

    public function update($id, $params)
    {
        $ppdbUser = PPDBUser::findOrFail($id);
        $data = $this->populateDataFromParams($params);
        $ppdbUser->fill($data);
        return $ppdbUser->save();
    }

    public function verify($id): bool
    {
        try {
            DB::beginTransaction();
            $ppdbUser = PPDBUser::byUserRole()->where('id', $id)->where('status', PPDBUser::STATUS_INCOMPLETE)->doesntHave('student')->firstOrFail();
            $ppdbUser->status = PPDBUser::STATUS_COMPLETE;
            $ppdbUser->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
        return false;
    }

    public function massSetPaymentVerified($input): bool
    {
        $ppdbUsers = PPDBUser::query()
            ->with('unit', 'user')
            ->whereIn('id', array_keys($input['users']))
            ->get();

        DbTrx::useTrx(function () use ($ppdbUsers, $input) {
            foreach ($ppdbUsers as $ppdb) {
                $ppdb->status = 'confirmed';

                $ppdb->save();
                event(new \App\Events\PPDB\FinanceFormPaymentImported($ppdb, $input['payment_dates'][$ppdb->id]));
            }
        });

        return false;
    }

    public function show($id)
    {
        $ppdb = PPDBUser::byUserRole()->findOrFail($id);
        $userData = User::where('id', $ppdb->user_id)->limit(1)->pluck('email', 'mobile_phone');
        $ppdb->email = $userData->values()->first();
        $ppdb->mobilePhone = $userData->keys()->first();
        return $ppdb;
    }

    public function getDataFromRegisterToken($registerToken)
    {
        return PPDBUser::whereHas('user', function ($query) use ($registerToken) {
            $query->where('register_token', $registerToken);
        })->firstOrFail();
    }

    public function confirmPayment($id)
    {
        $ppdb = PPDBUser::byUserRole()->findOrFail($id);
        $ppdb->status = PPDBUser::STATUS_CONFIRMED;
        $user = $ppdb->user;

        // $student_bills

        $template = (new PaymentConfirmed($user, $ppdb));
        (new EmailService())->sendMail($template, $user->email);
        return $ppdb->save();
    }

    public function rejectPayment($id, $params)
    {
        $ppdb = PPDBUser::byUserRole()->findOrFail($id);
        $ppdb->payment_form = null;

        $notificationValues = [
            'ppdb_user_id' => [$id],
            'title' => "[TOLAK] Bukti Pembayaran Registrasi $ppdb->name",
            'body' => $params['body'],
            'send_email' => $params['send_email'] ?? 0,
        ];

        (new NotificationService())->create($notificationValues);
        return $ppdb->save();
    }

    public function confirmDevelopmentStatement($id)
    {
        $ppdb = PPDBUser::byUserRole()->with('user', 'user.ppdb')->findOrFail($id);

        $stages = $ppdb->stages()->filter(function($stage) {
            return $stage->is_opening_development_feature;
        });

        foreach ($stages as $stage) {
            $userStage = PPDBUserStage::firstOrNew([
                'id' => $stage->ppdb_user_stage_id,
                'ppdb_user_id' => $ppdb->id,
                'stage_id' => $stage->id
            ]);
            $userStage->passed = 1;
            $userStage->save();
        }

        $statusVoucher = PriceHelper::getFreeVouchersOlahRagaProductStatus($ppdb);

        $dateNow = Carbon::now();
        //update verificator
        if(empty($ppdb->verification_development_statement)){
            $verificator = [
                'username'=>Auth::user()->username,
                'verification_time'=>$dateNow->toDateTimeString()
            ];
            $ppdb->verification_development_statement = json_encode($verificator);
            $ppdb->save();
        }

        if ($statusVoucher) {
            //got free voucher for olah raga
            $voucher = (new VoucherService)->generateFreeVouchersForOlahRagaProduct($ppdb, false);
            //apply voucher to cart
            (new CartService)->applyVoucher(
                ['voucher' => $voucher->code],
                $ppdb->user->toArray()
            );
        }

        //sync keuangan with ERP db
        event(new \App\Events\PPDB\DevelopmentStatementConfirmed($ppdb));

        return true;
    }

    public function confirm($id, $params): bool
    {
        try {
            DB::beginTransaction();
            DB::connection('mysql_erp')->beginTransaction();
            $ppdbUser = PPDBUser::where('id', $id)->byUserRole()->where('status', PPDBUser::STATUS_SUBMITTED)->doesntHave('student')->firstOrFail();

            $unit_code = str_pad($params['unit_id'],2,"0",STR_PAD_LEFT);

            if ($this->generateStudent($ppdbUser, $params)) {
                $this->transferDevelopmentFinance($ppdbUser);
                $user = $ppdbUser->user;
                $ppdbUser->status = PPDBUser::STATUS_ACCEPTED;
                $ppdbUser->save();

                // change type user to student
                // so user ppdb can login to dashboard student sanmaru
                $user->user_account = $params['nis'].'.'.$unit_code;
                $user->type = User::STUDENT;
                $user->save();

                DB::commit();
                DB::connection('mysql_erp')->commit();
                $template = (new RegistrantConfirmed($user, $ppdbUser));
                (new EmailService())->sendMail($template, $user->email);
                return true;
            }
        } catch (Exception $e) {
            DB::rollBack();
            DB::connection('mysql_erp')->rollBack();
            Log::error($e->getMessage());
        }
        return false;
    }

    private function generateStudent($ppdbUser, $params)
    {
        $student = new Student(
            array(
                'user_id' => $ppdbUser->user_id,
                'name' => $ppdbUser->name,
                'email' => $ppdbUser->user->email,
                'mobile_phone' => $ppdbUser->user->mobile_phone,
                'school_year' => $ppdbUser->school_year,
                'address' => $ppdbUser->address,
                'unit_id' => $ppdbUser->unit_id,
                'payment_agreement_id' => null,
                'class_id' => $params['class_id'],
                'register_number' => $ppdbUser->register_number,
                'nis' => $params['nis'],
            )
        );
        return $student->save();
    }

    private function transferDevelopmentFinance($ppdbUser){
        $student = DB::connection('mysql_erp')->table('students')
                ->where('register_number', $ppdbUser->register_number)
                ->first();

        $fd = DB::connection('mysql_erp')->table('ppdb_finance_developments')
            ->where('ppdb_user_id',$ppdbUser->id)
            ->first();

        if($fd && $student){
            $id = DB::connection('mysql_erp')->table('finance_developments')
            ->insertGetId([
                'student_id' => $student->id,
                'nominal' => $fd->nominal,
                'installment_period' => $fd->installment_period,
                'period_start' => $fd->period_start,
                'period_closed' => $fd->period_closed,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::connection('mysql_erp')->table('ppdb_finance_development_details')
            ->where('ppdb_finance_development_id',$fd->id)
            ->get()
            ->each(function ($detail) use ($id){
                $nominal = max($detail->credit - $detail->debet, 0);
                if($nominal > 0) {
                    DB::connection('mysql_erp')->table('finance_development_details')
                    ->insert([
                        'finance_development_id' => $id,
                        'nominal' => $nominal,
                        'payment_date' => $detail->payment_date,
                        'payment_method' => $detail->payment_method,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
        }
    }

    private function populateDataFromParams($params)
    {
        return array(
            'name' => $params['name'],
            'address' => $params['address'],
            'gender' => $params['gender'],
            'place_of_birth' => $params['place_of_birth'],
            'date_of_birth' => $params['date_of_birth'],
            'city' => $params['city'],
            'region' => $params['region'],
            'country' => $params['country'],
            'religion' => $params['religion'],
            // 'nik' => $params['nik'],
            'school_year' => $params['school_year'],
            'origin_school' => $params['origin_school']
        );
    }

    private function generateUserData($params)
    {
        return array(
            'email' => $params['email'],
            'username' => $this->username($params['name']),
            'user_type' => User::PPDB,
            'mobile_phone' => app('phoneNormalizerService')->normalize($params['mobile_phone']),
            'password' => Hash::make(Str::random(8)),
            'register_token' => Hash::make($params['email'] . date('Y-m-d H:i:s')),
            'status' => 'active',
        );
    }

    private function username($name)
    {
        $generator = new Generator(['separator' => '.']);
        return $generator->generate($name);
    }

    public function syncStages($id, $input)
    {
        $shouldDeleted = [];

        if (isset($input['stage_id'])) {

            foreach ($input['stage_id'] as $key => $stageId) {
                if (
                    isset($input['passed'][$key])
                    && $input['passed'][$key] != null
                ) {
                    PPDBUserStage::updateOrCreate(
                        [
                            'ppdb_user_id' => $id,
                            'stage_id' => $stageId
                        ],
                        [
                            'passed' => $input['passed'][$key]
                        ]
                    );
                } else
                    $shouldDeleted[] = $stageId;
            }

            PPDBUserStage::where('ppdb_user_id', $id)
                        ->whereIn('stage_id', $shouldDeleted)
                        ->delete();
        }
    }

    public function uploadImages($ppdb, $input)
    {
        if (isset($input['payment_form'])) {
            $type = 'payment_form';
            if ($upload = $this->doUploadImage($input['payment_form'], $type)) {
                $ppdb->payment_form = $upload['path_upload'];
            }
        }
        if (isset($input['birth_certificate'])) {
            $type = 'birth_certificate';
            if ($upload = $this->doUploadImage($input['birth_certificate'], $type)) {
                $ppdb->birth_certificate = $upload['path_upload'];
            }
        }
        if (isset($input['photo'])) {
            $type = 'photo';
            if ($upload = $this->doUploadImage($input['photo'], $type)) {
                $ppdb->photo = $upload['path_upload'];
            }
        }
        if (isset($input['family_card'])) {
            $type = 'family_card';
            if ($upload = $this->doUploadImage($input['family_card'], $type)) {
                $ppdb->family_card = $upload['path_upload'];
            }
        }
        if (isset($input['parent_identity_card'])) {
            $type = 'parent_identity_card';
            if ($upload = $this->doUploadImage($input['parent_identity_card'], $type)) {
                $ppdb->parent_identity_card = $upload['path_upload'];
            }
        }
        if (isset($input['marriage_certificate'])) {
            $type = 'marriage_certificate';
            if ($upload = $this->doUploadImage($input['marriage_certificate'], $type)) {
                $ppdb->marriage_certificate = $upload['path_upload'];
            }
        }
        if (isset($input['report_cards'])) {
            $type = 'marriage_certificate';
            $files = [];
            foreach ($input['report_cards'] as $file) {
                if ($upload = $this->doUploadImage($input['marriage_certificate'], $type)) {
                    $files[] = $upload['path_upload'];
                }
            }

            if (count($files)) {
                $ppdb->report_card = json_encode($files);
            }
        }
        if (isset($input['award_photo'])) {
            $type = 'award_photo';
            if ($upload = $this->doUploadImage($input['award_photo'], $type)) {
                $ppdb->award_photo = $upload['path_upload'];
            }
        }
        if (isset($input['kartu_golongan_darah'])) {
            $type = 'kartu_golongan_darah';
            if ($upload = $this->doUploadImage($input['kartu_golongan_darah'], $type)) {
                $ppdb->kartu_golongan_darah = $upload['path_upload'];
            }
        }
        if (isset($input['kms'])) {
            $type = 'kms';
            if ($upload = $this->doUploadImage($input['kms'], $type)) {
                $ppdb->kms = $upload['path_upload'];
            }
        }
        if (isset($input['baptismal_certificate'])) {
            $type = 'baptismal_certificate';
            if ($upload = $this->doUploadImage($input['baptismal_certificate'], $type)) {
                $ppdb->baptismal_certificate = $upload['path_upload'];
            }
        }
        if (isset($input['rekomendasi_bk'])) {
            $type = 'rekomendasi_bk';
            if ($upload = $this->doUploadImage($input['rekomendasi_bk'], $type)) {
                $ppdb->rekomendasi_bk = $upload['path_upload'];
            }
        }
        if (isset($input['angket_peminatan'])) {
            $type = 'angket_peminatan';
            if ($upload = $this->doUploadImage($input['angket_peminatan'], $type)) {
                $ppdb->angket_peminatan = $upload['path_upload'];
            }
        }
        if (isset($input['statement_letter'])) {
            $type = 'statement_letter';
            if ($upload = $this->doUploadImage($input['statement_letter'], $type)) {
                $ppdb->statement_letter = $upload['path_upload'];
            }
        }

        $ppdb->save();
    }

    public function filter(array $params, int $paginate_limit = null, array $related = null, array $columns = null)
    {
        $query = PPDBUser::query();

        if (array_key_exists('name', $params) && $params['name']) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }

        if (array_key_exists('gender', $params) && $params['gender']) {
            $query->where('gender', $params['gender']);
        }

        if (array_key_exists('unit', $params) && $params['unit']) {
            $query->where('unit_id', $params['unit']);
        }

        if (array_key_exists('year', $params) && $params['year']) {
            $query->where('school_year', $params['year']);
        }

        if (array_key_exists('periode', $params) && $params['periode']) {
            $query->where('periode', $params['periode']);
        }

        if ($related) {
            $query->with($related);
        }

        if ($columns) {
            $query->select($columns);
        }

        if ($paginate_limit) {
            return $query->paginate($paginate_limit);
        } else {
            return $query->get();
        }
    }

    public function confirmRegistrations($id)
    {
        $ppdbUser = PpdbUser::where('id', $id)->firstOrFail();
        $ppdbUser->payment_date = date('Y-m-d H:i:s');
        $ppdbUser->status = PPDBUser::STATUS_CONFIRMED;


        if($ppdbUser->save()){
            $user = User::where('id',$ppdbUser->user_id)->first();
            $template = (new RegistrationConfirmed($user, $ppdbUser));
            (new EmailService())->sendMail($template, $user->email);
            return true;
        }
        return false;
    }

    public function failDevelopmentStatement($id)
    {
        $ppdb = PPDBUser::byUserRole()->with('user', 'user.ppdb')->findOrFail($id);

        $stages = $ppdb->stages()->filter(function($stage) {
            return $stage->is_opening_development_feature;
        });

        foreach ($stages as $stage) {
            $userStage = PPDBUserStage::firstOrNew([
                'id' => $stage->ppdb_user_stage_id,
                'ppdb_user_id' => $ppdb->id,
                'stage_id' => $stage->id
            ]);
            $userStage->passed = 0;
            $userStage->save();
        }
        return true;
    }

    public function acceptedStudent($id, $params): bool
    {
        try {
            DB::beginTransaction();
            DB::connection('mysql_erp')->beginTransaction();
            $ppdbUser = PPDBUser::where('id', $id)->byUserRole()->where('status', PPDBUser::STATUS_SUBMITTED)->doesntHave('student')->firstOrFail();

            $unit_code = str_pad($params['unit_id'],2,"0",STR_PAD_LEFT);

            if ($this->generateStudent($ppdbUser, $params)) {
                $this->transferDevelopmentFinance($ppdbUser);
                $user = $ppdbUser->user;
                $ppdbUser->status = PPDBUser::STATUS_ACCEPTED;
                $ppdbUser->save();

                // change type user to student
                // so user ppdb can login to dashboard student sanmaru
                $user->user_account = $params['nis'].'.'.$unit_code;
                $user->type = User::STUDENT;
                $user->save();

                DB::commit();
                DB::connection('mysql_erp')->commit();
                $template = (new RegistrantConfirmed($user, $ppdbUser));
                (new EmailService())->sendMail($template, $user->email);
                return true;
            }
        } catch (Exception $e) {
            DB::rollBack();
            DB::connection('mysql_erp')->rollBack();
            Log::error($e->getMessage());
        }
        return false;
    }

    public function confirmFromImport($id, $params): bool
    {
        try {
            DB::beginTransaction();
            DB::connection('mysql_erp')->beginTransaction();
            $ppdbUser = PPDBUser::where('id', $id)->byUserRole()->where('status', PPDBUser::STATUS_ACCEPTED)->doesntHave('student')->firstOrFail();

            $unit_code = str_pad($params['unit_id'],2,"0",STR_PAD_LEFT);

            if ($this->generateStudent($ppdbUser, $params)) {
                $this->transferDevelopmentFinance($ppdbUser);
                $user = $ppdbUser->user;
                $ppdbUser->status = PPDBUser::STATUS_ACCEPTED;
                $ppdbUser->save();

                // change type user to student
                // so user ppdb can login to dashboard student sanmaru
                $user->user_account = $params['nis'].'.'.$unit_code;
                $user->type = User::STUDENT;
                $user->save();

                DB::commit();
                DB::connection('mysql_erp')->commit();
                $template = (new RegistrantConfirmed($user, $ppdbUser));
                (new EmailService())->sendMail($template, $user->email);
                return true;
            }
        } catch (Exception $e) {
            DB::rollBack();
            DB::connection('mysql_erp')->rollBack();
            Log::error($e->getMessage());
        }
        return false;
    }

    public function syncStageDevelopmnet($id, $stage_id){
        $ppdb = PPDBUser::where('id', $id)->byUserRole()->firstOrFail();

        $stageUser = PPDBUserStage::where('ppdb_user_id', $id)->where('stage_id', $stage_id)->first();
        if($stageUser){
            if($stageUser->passed == 1){
                $stageUser->passed = 0;
                $stageUser->save();
                return true;
            }
        }

        return false;
    }

    public function studentBills($ppdb){
        $categories = [
            'registrasi',
            'activity',
            'development',
            'tuition',
            'other'
        ];
        $createdBills = [];

        foreach($categories as $category){
            switch ($category) {
                case 'registrasi':
                    $price = \App\Helpers\PriceHelper::registration($ppdb, false, null, true);
                    break;

                case 'activity':
                    $price = \App\Helpers\PriceHelper::activity($ppdb, true, null, true);
                    break;

                case 'development':
                    $price = \App\Helpers\PriceHelper::development($ppdb, true, null, true);
                    break;

                case 'tuition':
                    $price = \App\Helpers\PriceHelper::tuition($ppdb, true, null, true);
                    break;

                case 'other':
                    $price = \App\Helpers\PriceHelper::others($ppdb, true, null, true);
                    break;

                default:
                    $price = '';
                    break;
            }

            if(!empty($price)){
                $finance = Finance::where('code', $price['code'])->first();

                if ($finance) {
                    $is_payment_method = StudentBills::PAYMENT_METHOD_UNPAID;
                    $is_payment_term = '';
                    if($category == 'registrasi'){
                        $is_payment_method = StudentBills::PAYMENT_METHOD_PAID;
                        $is_payment_term = StudentBills::PAYMENT_TERM_FULL;
                    }

                    $checkBils = StudentBills::where('ppdb_user_id', $ppdb->id)->where('finance_id', $finance->id)->first();

                    if(empty($checkBils)){
                        $bills = new StudentBills();
                        $bills->ppdb_user_id = $ppdb->id;
                        $bills->finance_id = $finance->id;
                        $bills->type = $category;
                        $bills->amount = $price['nominal_default'];
                        $bills->due_date = $finance->start_date;
                        $bills->payment_method = $is_payment_method;
                        $bills->payment_term = $is_payment_term;
                        $bills->save();

                        $createdBills[] = $bills;
                    } else {
                        $createdBills[] = $checkBils;
                    }

                }
            }
        }
        return $createdBills;
    }

    public function getBills($id){
        $bills = StudentBills::where('ppdb_user_id', $id)->get();

        $dataBills = [];
        $totalBill = $billedFull = 0;
        foreach($bills as $bill){
            $dataBills[$bill->type]['id'] = $bill->id;
            $dataBills[$bill->type]['ppdb_user_id'] = $bill->ppdb_user_id;
            $dataBills[$bill->type]['finance_id'] = $bill->finance_id;
            $dataBills[$bill->type]['finance_name'] = $bill->finance->name;
            $dataBills[$bill->type]['type'] = $bill->type;
            $dataBills[$bill->type]['amount'] = $bill->amount;
            $dataBills[$bill->type]['payment_method'] = $bill->payment_method;
            $dataBills[$bill->type]['payment_term'] = $bill->payment_term;
            $dataBills[$bill->type]['due_date'] = $bill->due_date;
            $dataBills[$bill->type]['note'] = $bill->note;

            $totalBill += $bill->amount;
            if($bill->payment_method == StudentBills::PAYMENT_METHOD_PAID){
                $billedFull += $bill->amount;
            }
        }

        $billAmount = [
            'total_bill'=> $totalBill,
            'billed_full'=> $billedFull
        ];

        return ['bills' => $bills,'bill_amount'=> $billAmount,'data_bills'=>$dataBills];
    }

    public function getDevelopmentReport($params){
        $ppdbUser = PPDBUser::where(['unit_id'=>$params['unit'],'school_year'=> $params['year']])->get();

        $arr_data = [];
        foreach($ppdbUser as $ppdb){

                $checkVoucher = '';
                $voucher = '';
                if ($ppdb->development_fee_option == 'lunas') {
                    $checkVoucher = $this->checkVocuher($ppdb);
                    //cari voucher
                    $check = Voucher::where('code', $checkVoucher)
                        ->whereJsonContains('user_id', $ppdb->user_id)// Mengecek apakah 15706 ada dalam array user_id
                        ->where('active', 1)
                        ->exists();
                    if ($check) {
                        $voucher = $checkVoucher;
                    }
                }

                $label_payment = 'Belum Upload Surat Pernyataan';
                $payment = '<span class="badge-modern badge-soft-warning">Belum Upload Surat Pernyataan</span>';
                if($ppdb->development_fee_option == 'lunas'){
                    $payment = '<span class="badge-modern badge-soft-success">Pembayaran Lunas</span>';
                    $label_payment = 'Pembayaran Lunas';
                }else if($ppdb->development_fee_option  == 'cicilan'){
                    $payment = '<span class="badge-modern badge-soft-info">Pembayaran Cicilan</span>';
                    $label_payment = 'Pembayaran Cicilan';
                }

                $label_status = 'Belum Terkonfirmasi';
                $status = '<span class="badge-modern badge-soft-warning">Belum Terkonfirmasi</span>';
                if($ppdb->IsStatementLetterConfirmed){
                    $label_status = 'Sudah Terkonfirmasi';
                    $status = '<span class="badge-modern badge-soft-success">Sudah Terkonfirmasi</span>';
                }

                $arr_data[$ppdb->id]['name'] = $ppdb->name;
                $arr_data[$ppdb->id]['register_number'] = $ppdb->register_number;
                $arr_data[$ppdb->id]['unit'] = $ppdb->unit->name;
                $arr_data[$ppdb->id]['school_year'] = $ppdb->school_year;
                $arr_data[$ppdb->id]['payment'] = $payment;
                $arr_data[$ppdb->id]['label_payment'] = $label_payment;
                $arr_data[$ppdb->id]['voucher'] = $voucher;
                $arr_data[$ppdb->id]['status'] = $status;
                $arr_data[$ppdb->id]['label_status'] = $label_status;
        }

        return $arr_data;
    }

    function checkVocuher($user)
    {
        $code = "";
        $unit = 0;
        $periode = 0;
        if ($user->unit_id) {
            $unit = (int)$user->unit_id;
        }
        if ($user->register_number) {
            $periode = (int)substr($user->register_number, 0, 2);
        }

        $code = $code . sprintf("%02d%02d%02d", $unit, $periode, ($periode + 1));
        if ($user->gender && $user->gender === 'female') {
            $code = $code . 'PI';
        } else {
            $code = $code . 'PA';
        }

        return $code;
    }

    public function dashboardPPDB(){
        $totalRegistered = PPDBUser::where('school_year',date('Y'))->count();
        $latestRegistered = $this->latestRegistered();
        $verifEmail = PPDBUser::where([
            'school_year'=>date('Y'),
            'status'=>PPDBUser::STATUS_SUBMITTED
        ])->count();

        $statementLetter = PPDBUser::where([
            'school_year' => date('Y'),
            'status' => PPDBUser::STATUS_SUBMITTED,
        ])->whereNotNull('development_statement')->count();

        $studentAccepted = PPDBUser::where([
            'school_year' => date('Y'),
            'status' => PPDBUser::STATUS_ACCEPTED,
        ])->count();

        $totalRegisteredPerUnit = $this->registeredPerUnit();

        $genderRatio = PPDBUser::select('gender',DB::raw('count(*) as total'))
            ->where('school_year',date('Y'))
            ->whereNotNull('gender')
            ->where('gender', '!=', '')
            ->groupBy('gender')->get();

        $topOriginSchools = PPDBUser::select('origin_school', DB::raw('count(*) as total'))
            ->where('school_year', date('Y'))
            ->whereNotNull('origin_school')
            ->where('origin_school', '!=', '')
            ->groupBy('origin_school')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $data = [
            'totalRegistered'=>$totalRegistered,
            'latestRegistered'=>$latestRegistered,
            'verifEmail'=>$verifEmail,
            'statementLetter'=>$statementLetter,
            'studentAccepted'=>$studentAccepted,
            'totalRegisteredPerUnit'=>$totalRegisteredPerUnit['collection'] ?? [],
            'totalRegisteredBillPerUnit'=>$totalRegisteredPerUnit['collection_bill'] ?? [],
            'genderRatio'=> $genderRatio,
            'topOriginSchools'=> $topOriginSchools,

        ];

        return $data;
    }

    public function latestRegistered(){
        $collection = [];
        $latestRegistered = PPDBUser::orderBy('id', 'desc')->limit(5)->get();

        foreach($latestRegistered as $item){
            $collection[$item->id]['name'] = $item->name;
            $collection[$item->id]['unit'] = $item->unit->name;
            $collection[$item->id]['status'] = $this->getStatusPPDB($item->status);
        }

        return $collection;
    }

    public function registeredPerUnit(){
        $users = PPDBUser::where('school_year', date('Y'))
            ->where('status', PPDBUser::STATUS_SUBMITTED)
            ->with('unit')
            ->get();

        $collection = [];
        $collection_bill = [];
        foreach($users as $item){
            if ($item->unit) {
                if (!isset($collection[$item->unit_id])) {
                    $collection[$item->unit_id] = [
                        'name' => $item->unit->name,
                        'total' => 0
                    ];
                }
                $collection[$item->unit_id]['total']++;

                if (!isset($collection_bill[$item->unit_id])) {
                    $collection_bill[$item->unit_id] = [
                        'name' => $item->unit->name,
                        'total_full' => 0,
                        'total_installment' => 0,
                        'total_dispensation' => 0,
                    ];
                }

                $cek_bill = $this->cekBill($item->id);

                if (!$cek_bill) {
                    continue;
                }

                if (in_array($cek_bill->dispensation_mode, ['only_discount', 'full_setup'])) {
                    $collection_bill[$item->unit_id]['total_dispensation']++;
                    $detailsCount = $cek_bill->details()->count();
                    if ($detailsCount > 1) {
                        $collection_bill[$item->unit_id]['total_installment']++;
                    } elseif ($detailsCount == 1) {
                        $collection_bill[$item->unit_id]['total_full']++;
                    }
                } else {
                    if($cek_bill->status_payment == 'paid'){
                        $detailsCount = $cek_bill->details()->count();
                        if ($detailsCount == 1) {
                            $collection_bill[$item->unit_id]['total_full']++;
                        }
                    }else{
                        $detailsCount = $cek_bill->details()->count();
                        if ($detailsCount > 1) {
                            $collection_bill[$item->unit_id]['total_installment']++;
                        }

                    }
                }
            }
        }

        return [
            'collection' => $collection,
            'collection_bill' => $collection_bill
        ];
    }

    public function cekBill($id){
        $paymentDispensationService = app(PaymentDispensationsService::class);
        $dispensation = $paymentDispensationService->getByUserPpdb($id);


        return $dispensation;
    }

    public function getStatusPPDB($status) {
        switch ($status) {
            case PPDBUser::STATUS_INCOMPLETE:
                return 'Belum Verifikasi Email';
            case PPDBUser::STATUS_COMPLETE:
                return 'Sudah Verifikasi Email';
            case PPDBUser::STATUS_CONFIRMED:
                return 'Pembayaran Terverififkasi';
            case PPDBUser::STATUS_SUBMITTED:
                return 'Proses Administrasi';
            case PPDBUser::STATUS_REJECTED:
                return 'Data Tidak Lengkap';
            case PPDBUser::STATUS_ACCEPTED:
                return 'Siswa Diterima';
            case PPDBUser::STATUS_NOT_SELECTED:
                return 'Siswa Tidak Diterima';
            default:
                return '';
        }
    }

    public function getAdmissionReport($params){
        $ppdbUsers = PPDBUser::orderBy('ppdb_users.created_at', 'ASC');

        if (isset($params['unit']) && $params['unit'] != 'all') {
            $ppdbUsers->where('ppdb_users.unit_id', $params['unit']);
        }

        if (isset($params['period']) && $params['period'] != 'all') {
            $ppdbUsers->where('ppdb_users.periode', $params['period']);
        }

        if (isset($params['year']) && $params['year'] != 'all') {
            $ppdbUsers->where('ppdb_users.school_year', $params['year']);
        }

        $ppdbUsers = $ppdbUsers->get();

        $collections = [];
        foreach($ppdbUsers as $ppdbUser){
            $collections[$ppdbUser->id]['name'] = $ppdbUser->name;
            $collections[$ppdbUser->id]['register_number'] = $ppdbUser->register_number;
            $collections[$ppdbUser->id]['unit'] = $ppdbUser->unit->name;
            $collections[$ppdbUser->id]['school_year'] = $ppdbUser->school_year;
            $collections[$ppdbUser->id]['periode'] = $ppdbUser->period->name ?? '-';
            $collections[$ppdbUser->id]['created_at'] = $ppdbUser->created_at->format('d-m-Y H:i:s') ?? '-';
            $collections[$ppdbUser->id]['detail']['registration'] = ($ppdbUser->payment_date != '') ? true : false;
            $collections[$ppdbUser->id]['detail']['administrasi'] = ($ppdbUser->is_data_complete_whitout_bca) ? true : false;
            $collections[$ppdbUser->id]['detail']['statement_letter'] = ($ppdbUser->IsStatementLetterUploaded) ? true : false;
            $collections[$ppdbUser->id]['detail']['statement_letter_verif'] = ($ppdbUser->IsStatementLetterConfirmed) ? true : false;
            $collections[$ppdbUser->id]['detail']['order_uniform'] = ($ppdbUser->isOrderConfirmed) ? true : false;
            $collections[$ppdbUser->id]['detail']['final_accpetance'] = ($ppdbUser->status == PPDBUser::STATUS_ACCEPTED) ? true : false;
            $collections[$ppdbUser->id]['detail']['class_nisn'] = ($ppdbUser->status == PPDBUser::STATUS_ACCEPTED  && $ppdbUser->user->type == 'siswa') ? true : false;;
        }
        return $collections;
    }

    public function getBillingData($id){
        $studentBillService = app(StudentBillService::class);
        $bills = $this->getBills($id);

        $collections = [];
        foreach($bills['data_bills'] as $bill){
            $collections[$bill['finance_id']]['id'] = $bill['id'];
            $collections[$bill['finance_id']]['desc'] = $bill['finance_name'];
            $collections[$bill['finance_id']]['type'] = $studentBillService->getPaymentType($bill['type']);
            $collections[$bill['finance_id']]['type_bill'] = $bill['type'];
            $collections[$bill['finance_id']]['amount'] = $bill['amount'];
            $collections[$bill['finance_id']]['payment_method'] = $studentBillService->getPaymentMethod($bill['payment_method']);
            $collections[$bill['finance_id']]['payment_status'] = $bill['payment_method'];
            $collections[$bill['finance_id']]['payment_term'] = $studentBillService->getPaymentTerm($bill['payment_term']);
            $collections[$bill['finance_id']]['due_date'] = $bill['due_date'];
            $collections[$bill['finance_id']]['note'] = $bill['note'];
        }

        return $collections;
    }


}
