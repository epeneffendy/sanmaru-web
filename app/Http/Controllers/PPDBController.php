<?php

namespace App\Http\Controllers;

use App\Helpers\InputCollectionHelper;
use App\Helpers\PriceHelper;
use App\Helpers\ProductHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComplaintOrderRequest;
use App\Http\Requests\NewPasswordRequest;
use App\Http\Requests\PPDBImportRequest;
use App\Models\Cart;
use App\Models\ComplaintCategory;
use App\Models\ComplaintOrders;
use App\Models\ComplaintPeriode;
use App\Models\CustomForm;
use App\Models\CustomFormInput;
use App\Models\Faq;
use App\Models\Notification;
use App\Models\Parents;
use App\Models\PaymentDispensations;
use App\Models\PPDBUser;
use App\Models\PPDBUserStage;
use App\Models\Product;
use App\Models\ProductFitting;
use App\Models\ProductOrder;
use App\Models\ProductOrderDetail;
use App\Models\ProductUserFitting;
use App\Models\Provinces;
use App\Models\Regencies;
use App\Models\Stage;
use App\Models\UniformDeadline;
use App\Models\Unit;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Services\CartService;
use App\Services\ComplaintOrderService;
use App\Services\GeneralSettingService;
use App\Services\NotificationService;
use App\Services\PaymentDispensationsService;
use App\Services\PPDBUserService;
use App\Services\ProductOrderComplaintService;
use App\Services\ProductOrderService;
use App\Services\UserService;
use App\Services\VoucherService;
use App\Traits\ImageHandler;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PPDBController extends Controller
{
    use ImageHandler;

    private $page = "ppdb";

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome(Request $request, NotificationService $notificationService, PPDBUserService $ppdbUserService)
    {
        $currentDateTime = Carbon::now();
        $user = $request->session()->get('user');
        $user_ppdb = PPDBUser::where('user_id', $user['id'])->with('unit', 'period')->first();
        $notifications = $notificationService->filter([
            'notifiable_type' => PPDBUser::class,
            'notifiable_id' => $user['ppdb']['id']
        ]);


        // if($user_ppdb->is_upload_development_statement == null){
        //     $max_date = \App\Helpers\PriceHelper::developmentStudent($user_ppdb);
        //     if($max_date != 0){
        //         if(date('Y-m-d') > $max_date){
        //             $stage = Stage::where(['periode'=> $user_ppdb->periode,'unit_id'=>$user_ppdb->unit_id,'is_opening_development_feature'=>1])->first();
        //             if($stage){
        //                 $userStage = PPDBUserStage::where('ppdb_user_id', $user_ppdb->id)->where('stage_id', $stage->id)->first();
        //                 if($userStage){
        //                     if($userStage->passed != 3 || $userStage->passed != 1 || $userStage->passed != 2){
        //                         $userStage->passed = 0;
        //                         $userStage->save();
        //                     }
        //                 }else{
        //                     $validation_stage = $ppdbUserService->failDevelopmentStatement($user_ppdb->id);
        //                 }
        //             }
        //         }
        //     }
        // }

        $is_stage = false;
        if ($user_ppdb->payment_option == 'BCA') {
            if ($user_ppdb->isStatusCompleteWhitoutBca) {
                $is_stage = true;
            }
        } else {
            if ($user_ppdb->isStatusComplete) {
                $is_stage = true;
            }
        }

        $stageResults = $user_ppdb->stages();

        $data = array(
            'user' => $user_ppdb,
            'ppdbUser' => $user_ppdb,
            'stages' => $stageResults,
            'notifications' => $notifications,
            'currentDateTime' => $currentDateTime,
            'is_stage' => $is_stage,
            'nav' => ['parent' => 'home', 'child' => 'Home'],
        );
        $view = 'new-welcome';

        return view('ppdb-online.' . $view, $data);
    }

    public function welcomeStudentSubmit(Request $request, PPDBUserService $ppdbUserService)
    {
        $user = $request->session()->get('user');
        $user_ppdb = PPDBUser::where('user_id', $user['id'])->first();

        $response = [
            'status' => 'failed',
            'message' => 'submit gagal, harap lengkapi data'
        ];

        if ($user_ppdb->isReadyToSubmit) {
            try {
                $bills = $ppdbUserService->studentBills($user_ppdb);
                if(count($bills) > 0){
                    $user_ppdb->update([
                        'status' => PPDBUser::STATUS_SUBMITTED,
                        'is_cost' => 1
                    ]);
                }else{
                    $user_ppdb->update(['status' => PPDBUser::STATUS_SUBMITTED]);
                }

                $response = [
                    'status' => 'success'
                ];
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error welcomeStudentSubmit: " . $e->getMessage());
                $response = [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan sistem saat menyimpan data administrasi, silakan coba lagi.'
                ];
            }

        }

        return response()->json($response);
    }

    public function dataSiswaPpdb(Request $request)
    {
            $user = $request->session()->get('user');
            $user_ppdb = PPDBUser::where('user_id', $user['id'])
                ->where('status', '<>', 'incomplete')
                //->where('status', '<>', 'complete')
                ->with('unit')
                ->firstOrFail();

            $stageResults = $user_ppdb->stages();
            $data = array(
                'user' => $user_ppdb,
                'stages' => $stageResults,
                'nav' => ['parent' => 'data', 'child' => 'Data Siswa']
            );

            if ($user_ppdb->status == 'complete') {
                return response()->view('ppdb-online/embed/noaccess', $data, 403);
            }

            $data['customForms'] = CustomForm::where('unit_id', $user_ppdb->unit_id)->whereHas('periods', function ($q) use ($user_ppdb) {
                $q->where('id', $user_ppdb->periode);
            })->get();

            return view('ppdb-online.welcome', $data);
    }

    public function informasiPpdb(Request $request)
    {
        $id = $request->input('id');
        $user = $request->session()->get('user');
        $stage = PPDBUserStage::where([
            ['stage_id', $id],
            ['ppdb_user_id', $user['ppdb']['id']],
            ['passed', 1]
        ])->with('stage')->firstOrFail();

        $data = array(
            'stage' => $stage,
            'nav' => ['parent' => 'home', 'child' => 'Informasi PPDB']
        );
        return view('ppdb-online.informasi-ppdb', $data);
    }

    public function biayaPengembanganPpdb(Request $request)
    {
        $user = $request->session()->get('user');
        $ppdb = PPDBUser::where('id', $user['ppdb']['id'])->first();
//        $max_date = \App\Helpers\PriceHelper::developmentStudent($ppdb);
//        if($max_date != 0){
//            $deadline = $max_date;
//        }

        if ($ppdb->development_fee_option && in_array($ppdb->development_fee_option, ['lunas', 'cicilan', 'lainnya'])) {
            return redirect(route('ppdb.biaya-pengembangan.' . $ppdb->development_fee_option));
        }

        $data = array(
            'ppdb' => $ppdb,
//            'deadline'=>Carbon::parse($deadline)->format('d-m-Y '),
            'nav' => ['parent' => 'home', 'child' => 'Informasi PPDB']
        );
        return view('ppdb-online.biaya-pengembangan.index', $data);
    }

    public function biayaPengembanganCicilanPpdb(Request $request)
    {

        $user = $request->session()->get('user');
        $ppdb = PPDBUser::where('id', $user['ppdb']['id'])->first();

        if ($ppdb->development_fee_option && in_array($ppdb->development_fee_option, ['lunas', 'lainnya'])) {
            return redirect(route('ppdb.biaya-pengembangan.' . $ppdb->development_fee_option));
        }

        $total_angsuran = 0.5 * \App\Helpers\PriceHelper::development($ppdb);

//        $deadline = 0;
//        $max_date = \App\Helpers\PriceHelper::developmentStudent($ppdb);
//        if($max_date != 0){
//            $deadline = $max_date;
//        }

        $angsuran = round(($total_angsuran) / 5);

        $data = array(
            'ppdb' => $ppdb,
            'total_angsuran' => $total_angsuran,
            'angsuran' => $angsuran,
//            'deadline'=>Carbon::parse($deadline)->format('d-m-Y '),
            'nav' => ['parent' => 'home', 'child' => 'Informasi PPDB']
        );

        return view('ppdb-online.biaya-pengembangan.cicilan', $data);
    }

    public function biayaPengembanganLunasPpdb(Request $request, GeneralSettingService $generalSettingService)
    {
        $discount = 0;
        $user = $request->session()->get('user');
        $ppdb = PPDBUser::where('id', $user['ppdb']['id'])->first();
        $development_discount = $generalSettingService->getBySlug('development-fee-discount');
        if($development_discount){
            $discount = $development_discount->value;
        }

        if ($ppdb->development_fee_option && in_array($ppdb->development_fee_option, ['lainnya', 'cicilan'])) {
            return redirect(route('ppdb.biaya-pengembangan.' . $ppdb->development_fee_option));
        }

//        $max_date = \App\Helpers\PriceHelper::developmentStudent($ppdb);
//        if($max_date != 0){
//            $deadline = $max_date;
//        }

        $data = array(
            'ppdb' => $ppdb,
            'discount'=>$discount,
//            'deadline'=>Carbon::parse($deadline)->format('d-m-Y '),
            'nav' => ['parent' => 'home', 'child' => 'Informasi PPDB']
        );
        return view('ppdb-online.biaya-pengembangan.lunas', $data);
    }

    public function postBiayaPengembanganCicilanPpdb(Request $request)
    {

        $user = $request->session()->get('user');
        $ppdb = PPDBUser::where('id', $user['ppdb']['id'])->firstOrFail();

        if ($ppdb->development_fee_option && in_array($ppdb->development_fee_option, ['lainnya', 'lunas'])) {
            return redirect(route('ppdb.biaya-pengembangan.' . $ppdb->development_fee_option));
        }

        $startAngsuran = PriceHelper::getDevelopmentStartDateFinance($ppdb);
        $limitDate = \Carbon\Carbon::parse($startAngsuran)->addMonths(5)->toDateString();

        $params = Validator::make($request->merge([
            'development_fee_option' => 'cicilan',
            'total_angsuran' => $request->input('cicilan_1') + $request->input('cicilan_2') + $request->input('cicilan_3') + $request->input('cicilan_4') + $request->input('cicilan_5')
        ])->all(), [
            'angsuran_1' => 'required|date|date_format:Y-m-d|before:' . $limitDate,
            'angsuran_2' => 'required|date|date_format:Y-m-d|before:' . $limitDate,
            'angsuran_3' => 'required|date|date_format:Y-m-d|before:' . $limitDate,
            'angsuran_4' => 'required|date|date_format:Y-m-d|before:' . $limitDate,
            'angsuran_5' => 'required|date|date_format:Y-m-d|before:' . $limitDate,
            'cicilan_1' => 'required|numeric',
            'cicilan_2' => 'required|numeric',
            'cicilan_3' => 'required|numeric',
            'cicilan_4' => 'required|numeric',
            'cicilan_5' => 'required|numeric',
            'total_angsuran' => 'required|numeric|size:' . (0.5 * \App\Helpers\PriceHelper::development($ppdb)),
            'development_fee_option' => 'required|in:cicilan',
        ])->validate();

        $ppdb->update($params);
        $ppdb->refresh();

//        $max_date = \App\Helpers\PriceHelper::developmentStudent($ppdb);
//        if($max_date != 0){
//            $deadline = $max_date;
//        }

        $data = array(
            'ppdb' => $ppdb,
//            'deadline'=>Carbon::parse($deadline)->format('d-m-Y '),
            'nav' => ['parent' => 'home', 'child' => 'Informasi PPDB']
        );
        return view('ppdb-online.biaya-pengembangan.cicilan', $data);
    }

    public function biayaPengembanganLainnyaPpdb(Request $request)
    {
        $user = $request->session()->get('user');
        $ppdb = PPDBUser::where('id', $user['ppdb']['id'])->first();

        if ($ppdb->development_fee_option && in_array($ppdb->development_fee_option, ['lunas', 'cicilan'])) {
            return redirect(route('ppdb.biaya-pengembangan.' . $ppdb->development_fee_option));
        }

        $data = array(
            'ppdb' => $ppdb,
            'nav' => ['parent' => 'home', 'child' => 'Informasi PPDB']
        );
        return view('ppdb-online.biaya-pengembangan.lainnya', $data);
    }

    public function faqPpdb()
    {
        $data = array(
            'faqs' => Faq::where([
                ['category', 'web-PPDB'],
                ['published', 1]
            ])->get(),
            'nav' => ['parent' => 'home', 'child' => 'FAQ']
        );
        return view('ppdb-online.faq-ppdb', $data);
    }

    public function notifikasiPpdb()
    {
        abort(404);
        $data = array(
            'nav' => ['parent' => 'home', 'child' => 'Notifikasi']
        );
        return view('ppdb-online.notifikasi-ppdb', $data);
    }


    public function profileSiswa()
    {
        $user = request()->session()->get('user');
        $ppdbUser = PPDBUser::where('id', $user['ppdb']['id'])->with('parents')->firstOrFail();

        $data = array(
            'user' => $user,
            'ppdb_user' => $ppdbUser,
            'nav' => ['parent' => 'profile', 'child' => 'Profile Siswa']
        );

        return view('ppdb-online.profile-siswa', $data);
    }

    public function newPassword()
    {
        $data = array(
            'nav' => ['parent' => 'profile', 'child' => 'Ubah Password']
        );
        return view('ppdb-online.new-password', $data);
    }

    public function updatePassword(NewPasswordRequest $request, UserService $userService)
    {
        $params = $request->validated();
        $user = $userService->findOrFailByAttribute('id', $request->session()->get('user')['id']);
        try {
            $userService->updatePassword($user, $params['old_password'], $params['password']);
        } catch (\Exception $e) {
            throw \Illuminate\Validation\ValidationException::withMessages(['Password lama anda salah']);
        }
        return redirect()->route('ppdb.profile-siswa')->with('message', 'Password berhasil diubah!');
    }

    public function product()
    {
        $data = array(
            'nav' => ['parent' => 'product', 'child' => 'Seragam']
        );
        return view('ppdb-online.product.index', $data);
    }

    public function showProduct()
    {
        $data = array(
            'nav' => ['parent' => 'product', 'child' => 'Seragam']
        );
        return view('ppdb-online.product.show', $data);
    }

    public function cart()
    {
        $data = array(
            'nav' => ['parent' => 'product', 'child' => 'Keranjang Belanja']
        );
        return view('ppdb-online.product.cart', $data);
    }

    public function formStudent(Request $request)
    {
        $user = $request->session()->get('user');
        $user_ppdb = PPDBUser::where('user_id', $user['id'])->first();
        $cities = DB::table('ppdb_users')->selectRaw('lower(place_of_birth) AS city_name')->where('place_of_birth', '!=', NULL)->groupby('place_of_birth')->distinct('city_name')->get();

        $provinces = Provinces::get();

        $arr_stepper = ['Identitas Siswa', 'Data Tambahan', 'Asal Sekolah', 'Riwayat Kesehatan', 'Prestasi & Potensi'];
        if ($user_ppdb->unit->unit_code != '05') {
            unset($arr_stepper[4]);
        }

        $data = array(
            'ppdbUser' => $user_ppdb,
            'cities' => $cities,
            'stepper' => $arr_stepper,
            'provinces' => $provinces,
            'nav' => ['parent' => 'data', 'child' => 'Data Siswa']
        );

        return view('ppdb-online/form-student-administration', $data);
        // dd($cities);
    }

    public function formStudentSubmit(Request $request, PPDBUserService $ppdbUserService)
    {
        $input = $request->all();

        $user = $request->session()->get('user');

        $unit = Unit::find($user['ppdb']['unit_id']);

        if ($request->place_of_birth == 'another_city') {
            $input['place_of_birth'] = $input['another_city'];
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required'],
            'place_of_birth' => ['required'],
            'date_of_birth' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'region' => ['required'],
            'country' => ['required'],
            'religion' => ['required'],
            'nik_siswa' => ['required', 'numeric', 'digits:16'],
            'nik_ortu' => ['required', 'numeric', 'digits:16'],
            'additional_info' => ['nullable'],
        ];

        $additionalRules = InputCollectionHelper::additionalData($unit);
        $rules = array_merge($rules, $additionalRules->all());

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $update = array(
                'name' => $input['name'],
                'gender' => $input['gender'],
                'place_of_birth' => $input['place_of_birth'],
                'date_of_birth' => date('Y-m-d', strtotime($input['date_of_birth'])),
                'address' => $input['address'],
                'city' => $input['city'],
                'region' => $input['region'],
                'country' => $input['country'],
                'religion' => $input['religion'],
                'nik_siswa' => $input['nik_siswa'],
                'nik_ortu' => $input['nik_ortu'],
            );

            foreach ($additionalRules->all() as $key => $rule) {
                if (isset($input[$key])) {
                    $update[$key] = $input[$key];
                }
            }

            if ($user) {
                //cek gender
                $ppdb = PPDBUser::where('user_id', $user['id'])->firstOrFail();

                $new_generate_voucher = false;
                if (!empty($ppdb->development_statement)) {
                    if ($ppdb->gender != $input['gender']) {
                        (new VoucherService)->removeGeneratedFreeVouchersForOlahRagaProduct($ppdb);
                        $new_generate_voucher = true;
                    }
                }


                PPDBUser::where('user_id', $user['id'])
                    ->firstOrFail()
                    ->update($update);

                if ($new_generate_voucher) {
                    $ppdbUserService->confirmDevelopmentStatement($ppdb->id);
                }
            }

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
        return redirect(route('ppdb.welcome'))->with('message', 'Data berhasil disimpan.');
        // dd($request);
    }

    public function formParent(Request $request)
    {
        $user = $request->session()->get('user');
        $ppdb = PPDBUser::where('user_id', $user['id'])->with('parents')->firstOrFail();
        $arr_stepper = ['Data Ayah', 'Data Ibu'];

        $provinces = Provinces::get();

        $data = array(
            'dad' => Parents::where('children_id', $user['id'])->where('type', 'father')->first(),
            'mom' => Parents::where('children_id', $user['id'])->where('type', 'mother')->first(),
            'wali' => Parents::where('children_id', $user['id'])->where('type', 'wali')->first(),
            'ppdb' => $ppdb,
            'stepper' => $arr_stepper,
            'provinces' => $provinces,
            'nav' => ['parent' => 'data', 'child' => 'Data Siswa']
        );

        if ($ppdb->isWaliRequired) {
            return view('ppdb-online/form-parent-guardian-administration', $data);
        } else {
            return view('ppdb-online/form-parent-administration', $data);
        }

    }

    public function formParentSubmit(Request $request)
    {
        $input = $request->all();
        $user = $request->session()->get('user');
        $ppdb = PPDBUser::where('user_id', $user['id'])->firstOrFail();

        $rules = [
            'father_name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'f_phone' => ['required', 'phone:ID,mobile'],
            'f_place_of_birth' => ['required'],
            'f_date_of_birth' => ['required'],
            'f_address' => ['required'],
            'f_city' => ['required'],
            'f_region' => ['required'],
            'f_country' => ['required'],
            'f_religion' => ['required'],
            'f_job' => ['required'],
            'f_salary' => ['required'],
            'f_education' => ['required'],
            'm_phone' => ['required', 'phone:ID,mobile'],
            'm_place_of_birth' => ['required'],
            'm_date_of_birth' => ['required'],
            'm_address' => ['required'],
            'm_city' => ['required'],
            'm_region' => ['required'],
            'm_country' => ['required'],
            'm_religion' => ['required'],
            'm_job' => ['required'],
            'm_salary' => ['required'],
            'm_education' => ['required'],
        ];

        if ($ppdb->isWaliRequired) {
            $rules = [
                'wali_name' => ['required', 'string', 'max:255'],
                'w_phone' => ['required', 'phone:ID,mobile'],
                'w_place_of_birth' => ['required'],
                'w_date_of_birth' => ['required'],
                'w_address' => ['required'],
                'w_city' => ['required'],
                'w_region' => ['required'],
                'w_country' => ['required'],
                'w_religion' => ['required'],
                'w_job' => ['required'],
                'w_salary' => ['required'],
                'w_education' => ['required']
            ];
        }

        $text = 'Nomor telepon yang dimasukkan tidak valid. Silahkan periksa kembali';
        $validator = Validator::make($input, $rules, [
            'f_phone.phone' => $text,
            'f_phone.mobile' => $text,
            'm_phone.phone' => $text,
            'm_phone.mobile' => $text,
            'w_phone.phone' => $text,
            'w_phone.mobile' => $text,
            'phone' => $text,
            'mobile' => $text,
        ]);


        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {

            if (!$ppdb->isWaliRequired) {
                $dads_data = array(
                    'name' => $input['father_name'],
                    'place_of_birth' => $input['f_place_of_birth'],
                    'date_of_birth' => date('Y-m-d', strtotime($input['f_date_of_birth'])),
                    'address' => $input['f_address'],
                    'city' => $input['f_city'],
                    'region' => $input['f_region'],
                    'country' => $input['f_country'],
                    'religion' => $input['f_religion'],
                    'job' => $input['f_job'],
                    'type' => 'father',
                    'phone' => app('phoneNormalizerService')->normalize($input['f_phone']),
                    'education' => $input['f_education'],
                    'salary' => $input['f_salary'],
                    'children_id' => $user['id']
                );


                $moms_data = array(
                    'name' => $input['mother_name'],
                    'place_of_birth' => $input['m_place_of_birth'],
                    'date_of_birth' => date('Y-m-d', strtotime($input['m_date_of_birth'])),
                    'address' => $input['m_address'],
                    'city' => $input['m_city'],
                    'region' => $input['m_region'],
                    'country' => $input['m_country'],
                    'religion' => $input['m_religion'],
                    'job' => $input['m_job'],
                    'type' => 'mother',
                    'education' => $input['m_education'],
                    'salary' => $input['m_salary'],
                    'phone' => app('phoneNormalizerService')->normalize($input['m_phone']),
                    'children_id' => $user['id']
                );

                $father = Parents::firstOrNew([
                    'children_id' => $user['id'],
                    'type' => 'father'
                ]);
                $father->fill($dads_data);
                $father->save();

                $mom = Parents::firstOrNew([
                    'children_id' => $user['id'],
                    'type' => 'mother'
                ]);
                $mom->fill($moms_data);
                $mom->save();

            } else {
                $wali_data = array(
                    'name' => $input['wali_name'],
                    'place_of_birth' => $input['w_place_of_birth'],
                    'date_of_birth' => date('Y-m-d', strtotime($input['w_date_of_birth'])),
                    'address' => $input['w_address'],
                    'city' => $input['w_city'],
                    'region' => $input['w_region'],
                    'country' => $input['w_country'],
                    'religion' => $input['w_religion'],
                    'job' => $input['w_job'],
                    'type' => 'wali',
                    'education' => $input['w_education'],
                    'salary' => $input['w_salary'],
                    'phone' => app('phoneNormalizerService')->normalize($input['w_phone']),
                    'children_id' => $user['id']
                );

                $wali = Parents::firstOrNew([
                    'children_id' => $user['id'],
                    'type' => 'wali'
                ]);
                $wali->fill($wali_data);
                $wali->save();
            }

        } catch (\Exception $e) {
            dd($e);
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
        return redirect(route('ppdb.welcome'))->with('message', 'Data berhasil disimpan.');
    }

    public function uploadPaymentForm(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'payment_form');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadBirthCertificate(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'birth_certificate');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadPhoto(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'photo');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadFamilyCard(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'family_card');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadKartuGolonganDarah(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'kartu_golongan_darah');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadKms(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'kms');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadParentIdentityCard(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'parent_identity_card');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadBaptismalCertificate(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'baptismal_certificate');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadAwardPhoto(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'award_photo');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function downloadStatementLetter()
    {
        $user = session()->get('user');
        $user_ppdb = PPDBUser::where('user_id', $user['id'])->with('unit')->first();

        return response()->download(public_path("docs/surat-pernyataan/{$user_ppdb->unit->name_with_file_format}.pdf"));
    }

    public function downloadAngketPeminatan()
    {
        $user = session()->get('user');
        $user_ppdb = PPDBUser::where('user_id', $user['id'])->with('unit')->first();

        // return response()->download(public_path("docs/angket-peminatan/{$user_ppdb->unit->name_with_file_format}.pdf"));
        return response()->download(public_path("docs/angket-peminatan/{$user_ppdb->unit->name_with_file_format}.docx"));
    }

    public function uploadStatementLetter(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'statement_letter');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadAngketPeminatan(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'angket_peminatan');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadMarriageCertificate(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'marriage_certificate');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadRekomendasiBk(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'rekomendasi_bk');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadReportCard(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'report_card');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function uploadDevelopmentFee(Request $request)
    {
        $data = [];
        try {
            $data = $this->uploadImage($request, 'development_statement');
            $data['preview'] = route('ppdb.download-development-statement-letter');
            if ($request->input('development_fee_option')) {
                $user = $request->session()->get('user');
                $ppdb = PPDBUser::where('user_id', $user['id'])->firstOrFail();
                $ppdb->development_fee_option = $request->input('development_fee_option');
                $ppdb->is_upload_development_statement = 1;
                $ppdb->save();
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        return response()->json($data, 200);
    }

    public function deleteReportCard(Request $request)
    {
        $user = session()->get('user');
        $user_ppdb = PPDBUser::where('id', $request->input('id'))->where('user_id', $user['id'])->firstOrFail();

        $reports = $user_ppdb->report_cards;
        foreach (@$reports as $key => $report) {
            if ('report_card/' . $request->input('filename') == $report) {
                unset($reports[$key]);
            }
        }

        $user_ppdb->update(['report_cards' => json_encode($reports)]);
        return response()->json([
            'status' => 'success',
            'is_ready_to_submit' => $user_ppdb->isReadyToSubmit
        ], 200);
    }

    public function downloadDevelopmentStatement(string $type = 'lunas')
    {

        if (request()->input('type')) {
            $type = request()->input('type');
        }

        if (!in_array($type, ['lunas', 'cicilan', 'lainnya'])) {
            abort(404);
        }

        $user = session()->get('user');
        $ppdb = PPDBUser::where('user_id', $user['id'])->with('unit', 'user', 'parents')->firstOrFail();
        $filename = $type;
        $jurusan = "";
        if ($ppdb->unit_id == 5) {
            foreach ($ppdb->stages() as $stage) {
                if ($stage->note) {
                    $note = strtoupper($stage->note);
                    $jurusan = Str::contains(strtoupper($stage->note), 'MIPA') ? 'MIPA' : $jurusan;
                    $jurusan = Str::contains(strtoupper($stage->note), 'IPS') ? 'IPS' : $jurusan;
                    $jurusan = Str::contains(strtoupper($stage->note), 'BAHASA') ? 'Bahasa' : $jurusan;
                }
            }
            $filename = $type . '-' . 'sma';
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path("docs/surat-pernyataan-pengembangan/" . $filename . ".docx"));

        $startAngsuran = PriceHelper::getDevelopmentStartDateFinance($ppdb);

        $totalBayarDiskon = PriceHelper::rupiah(PriceHelper::development($ppdb));
        $keteranganDiskon = "";

        $statusDiskon = PriceHelper::getDevelopmentDiscountStatus($ppdb);
        if ($statusDiskon) {
            $totalBayarDiskon = PriceHelper::rupiah((95 / 100) * PriceHelper::development($ppdb));
            $keteranganDiskon = "mendapatkan Diskon 5%";
        }

        $statusVoucher = PriceHelper::getFreeVouchersOlahRagaProductStatus($ppdb, $type);
        if ($statusVoucher) {
            if ($keteranganDiskon != "") {
                $keteranganDiskon .= ' dan ';
            }
            $keteranganDiskon .= "Free Seragam OR";
        }

        if ($keteranganDiskon != "") {
            $keteranganDiskon = "(" . $keteranganDiskon . ")";
        }

        // Hardcoded
        $deadline = UniformDeadline::where([
            'unit_id' => $ppdb->unit_id,
            'status' => 1
        ])->first();
        $uniform_deadline = date('Y') . ' - ' . (date('Y') + 1);
        $school_year = '';
        if ($deadline) {
            $uniform_deadline = $deadline->uniform_payment_deadline;
            $school_year = $deadline->school_year . ' - ' . ($deadline->school_year + 1);
        }

        $uniformPaymentDeadline = strtolower($ppdb->unit->city) != 'pacet' ? ', ' . $uniform_deadline : '';

        $installment = PaymentDispensations::where('ppdb_user_id', $ppdb->id)->where('status', PaymentDispensations::STATUS_ACTIVE)->orderBy('id', 'desc')->first();
        $dataAngsuran = [];
        if($installment){
            foreach($installment->details as $item){
                $dataAngsuran[] = [
                    'desc'       => ($item->installment_number == 0) ? 'Pembayaran DP' : 'Angsuran ke-'.$item->installment_number,
                    'plan_date'  => \Carbon\Carbon::parse($item->plan_date)->format('d M Y'),
                    'nominal'    => PriceHelper::rupiah($item->nominal) // Langsung format rupiah di sini
                ];
            }
        }

        $templateProcessor->setValues([
            'register_number' => $ppdb->register_number,
            'nama_lengkap' => $ppdb->name,
            'alamat' => $ppdb->address,
            'nomor_hp' => $ppdb->user->mobile_phone,
            'nama_ayah' => $ppdb->father() ? $ppdb->father()->name : NULL,
            'nama_ibu' => $ppdb->mother() ? $ppdb->mother()->name : NULL,
            'total_bayar' => PriceHelper::development($ppdb, true),
            'keterangan' => PriceHelper::getDescriptionFinance($ppdb, 'development'),
            'total_bayar_kegiatan' => PriceHelper::activity($ppdb, true),
            'keterangan_kegiatan' => PriceHelper::getDescriptionFinance($ppdb, 'activity'),
            'total_bayar_spp' => PriceHelper::tuition($ppdb, true),
            'keterangan_spp' => PriceHelper::getDescriptionFinance($ppdb, 'tuition'),
            'total_bayar_diskon' => $totalBayarDiskon,
            'keterangan_diskon' => $keteranganDiskon,
            'total_bayar_awal' => PriceHelper::rupiah((50 / 100) * PriceHelper::development($ppdb)),
            'batas_angsuran' => $startAngsuran ? @\Carbon\Carbon::parse($startAngsuran)->addMonths(count($dataAngsuran))->format('M Y') : null,
            'nama_unit' => $ppdb->unit->letter_header_name,
            'alamat_unit' => Str::title($ppdb->unit->address),
            'email_unit' => $ppdb->unit->email,
            'kota_unit' => $ppdb->unit->letter_header_city,
            'telp_unit' => $ppdb->unit->telp ? implode(', ', $ppdb->unit->telp) : null,
            'fax_unit' => $ppdb->unit->fax ? implode(', ', $ppdb->unit->fax) : null,
            // 'angsuran_1' => @\Carbon\Carbon::parse($ppdb->angsuran_1)->format('d M Y'),
            // 'angsuran_2' => @\Carbon\Carbon::parse($ppdb->angsuran_2)->format('d M Y'),
            // 'angsuran_3' => @\Carbon\Carbon::parse($ppdb->angsuran_3)->format('d M Y'),
            // 'angsuran_4' => @\Carbon\Carbon::parse($ppdb->angsuran_4)->format('d M Y'),
            // 'angsuran_5' => @\Carbon\Carbon::parse($ppdb->angsuran_5)->format('d M Y'),
            // 'cicilan_1' => @PriceHelper::rupiah($ppdb->cicilan_1),
            // 'cicilan_2' => @PriceHelper::rupiah($ppdb->cicilan_2),
            // 'cicilan_3' => @PriceHelper::rupiah($ppdb->cicilan_3),
            // 'cicilan_4' => @PriceHelper::rupiah($ppdb->cicilan_4),
            // 'cicilan_5' => @PriceHelper::rupiah($ppdb->cicilan_5),
            'jurusan' => $jurusan,
            'pembayaran' => $ppdb->unit->payment_option,
            'kota' => $ppdb->unit->city,
            // Hardcode for uniform deadline
            'uniform_payment_deadline' => $uniformPaymentDeadline,
            'school_year' => $school_year,
        ]);

        // PERUBAHAN DISINI: Proses Loop Dynamic Table menggunakan cloneRow
        if (!empty($dataAngsuran)) {
            // Kita jadikan placeholder 'desc' sebagai acuan untuk menduplikasi baris tabel
            $templateProcessor->cloneRow('desc', count($dataAngsuran));

            foreach ($dataAngsuran as $index => $angsuran) {
                $rowNumber = $index + 1; // PHPWord penomoran baris hasil klon dimulai dari 1
                $templateProcessor->setValue('desc#' . $rowNumber, $angsuran['desc']);
                $templateProcessor->setValue('plan_date#' . $rowNumber, $angsuran['plan_date']);
                $templateProcessor->setValue('nominal#' . $rowNumber, $angsuran['nominal']);
            }
        } else {
            // Antisipasi jika data angsuran kosong (misal tipe lunas), agar template tidak rusak/bocor code
            $templateProcessor->cloneRow('desc', 1);
            $templateProcessor->setValue('desc#1', '-');
            $templateProcessor->setValue('plan_date#1', '-');
            $templateProcessor->setValue('nominal#1', '-');
        }

        header("Content-Description: File Transfer");
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=" . $filename . ".docx");
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: public");

        $templateProcessor->saveAs('php://output');
    }

    public function downloadProofRegistration()
    {
        $user = session()->get('user');
        $user_ppdb = PPDBUser::where('user_id', $user['id'])->with('unit')->first();

        $pdf = \PDF::loadView('ppdb-online.proof-registration', compact('user', 'user_ppdb'));
        return $pdf->stream();
    }

    private function uploadImage($request, string $type)
    {
        if ($request->hasFile($type) && $upload = $this->doUploadImage($request->file($type), $type)) {
            $user = $request->session()->get('user');
            $update = array(
                $type => $upload['path_upload']
            );

            $ppdb = PPDBUser::where('user_id', $user['id'])->firstOrFail();
            $ppdb->update($update);

            return array_merge($upload, ['is_ready_to_submit' => $ppdb->isReadyToSubmit]);
        }
    }

    public function embedProduct(Request $request)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $fittings = ProductFitting::where('unit_id', $user['ppdb']['unit_id'])->with('users')->get();
        $ppdbUser = PPDBUser::where('id', $user['ppdb']['id'])->first();

        $orders = ProductOrder::where('status', ProductOrder::STATUS_NEW_ORDER)
            ->whereNull('payment_image')
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($orders as $order) {
            if (Carbon::now()->format('Y-m-d H:i:s') > $order->getExpiredAtAttribute()->toDateTimeString()) {
                $order->status = ProductOrder::STATUS_CANCEL;
                $order->save();
                if ($order->voucher !== NULL) {
                    VoucherUsage::where('product_order_id', $order->id)
                        ->where('voucher_id', json_decode($order->voucher, TRUE)['id'])
                        ->delete();
                }
            }
        }

        $data = [
            'products' => ProductHelper::suitableProducts($ppdbUser),
            'fittings' => $fittings,
            'user_fittings' => ProductUserFitting::where('user_id', $user['id'])->whereIn('fitting_id', $fittings->pluck('id'))->get(),
            'nav' => ['parent' => 'product', 'child' => 'Seragam']
        ];

        return view('ppdb-online/embed/index', $data);
    }

    public function embedProductDetail(Request $request, $id)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $fittings = ProductFitting::where('unit_id', $user['ppdb']['unit_id'])->with('users')->get();

        $data = [
            'product' => Product::published()->where('id', $id)->whereHas('productUnits', function ($query) use ($user) {
                return $query->where('unit_id', $user['ppdb']['unit_id']);
            })->with('details')->firstOrFail(),
            'fittings' => $fittings,
            'user_fittings' => ProductUserFitting::where('user_id', $user['id'])->whereIn('fitting_id', $fittings->pluck('id'))->get(),
            'nav' => ['parent' => 'product', 'child' => 'Seragam']
        ];

        return view('ppdb-online/embed/detail', $data);
    }

    public function embedProductCart(Request $request, VoucherService $voucherService)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $fittings = ProductFitting::where('unit_id', $user['ppdb']['unit_id'])->with('users')->get();
        $vouchers = Voucher::eligible($user);
        $cart = Cart::where('user_id', $user['id'])->with('details', 'details.product', 'details.product.details', 'details.product_detail')->first();
        $orders = $this->checkOrders($user['id']);

        if ($cart && $voucher = json_decode($cart->voucher, TRUE)) {
            if ($updatedVoucher = $vouchers->filter(function ($q) use ($voucher) {
                return $q->code === $voucher['code'];
            })->first()
            ) {
                $updatedVoucher = $updatedVoucher->only(['id', 'code', 'rule', 'note', 'type', 'usage_limit']);
                $cart->voucher = json_encode($updatedVoucher);
            } else {
                $cart->voucher = null;
            }
            $cart->save();
        }

        $isCartVoucherFulfilled = true;
        $cartVoucherProducts = collect();

        if ($cart && $cart->voucher) {
            $voucher = json_decode($cart->voucher, true);
            $voucher = $vouchers->filter(function ($q) use ($voucher) {
                return $q->code === $voucher['code'];
            })->first();

            if ($voucher && $voucher['type'] == 'free_product') {
                $rule = json_decode($voucher['rule'], true);
                $isCartVoucherFulfilled = false;
                if ($count = $cart->details->filter(function ($q) use ($rule) {
                    return in_array($q->product_id, $rule);
                })->count()
                ) {
                    $isCartVoucherFulfilled = ($count === count($rule));
                }

                $cartVoucherProducts = Product::select('id', 'name')->whereIn('id', $rule)->get();
            }
        }

        $getVoucher = [];
        foreach ($vouchers as $ind => $voucher) {
            $getVoucher[$ind] = $voucherService->getVoucher($voucher);
        }

        $data = [
            'cart' => $cart,
            'fittings' => $fittings,
            'vouchers' => $getVoucher,
            'isCartVoucherFulfilled' => $isCartVoucherFulfilled,
            'cartVoucherProducts' => $cartVoucherProducts,
            'orders' => $orders['orders'],
            'no_invoice' => $orders['no_invoice'],
            'user_fittings' => ProductUserFitting::where('user_id', $user['id'])->whereIn('fitting_id', $fittings->pluck('id'))->get(),
            'nav' => ['parent' => 'product', 'child' => 'Keranjang Belanja']
        ];

        return view('ppdb-online/embed/cart', $data);
    }

    public function embedDetailPayment(Request $request, $id)
    {
        $user = $request->session()->get('user');
        $this->validatePassedShopStages();

        $order = ProductOrder::where([
            'id' => $id,
            'user_id' => $user['id']
        ])->with('productOrderDetails', 'productOrderDetails.productDetail', 'productOrderDetails.product')->firstOrFail();

        $data = [
            'order' => $order,
            'user' => PPDBUser::where('user_id', $user['id'])->firstOrFail(),
            'nav' => ['parent' => 'product', 'child' => 'Pesanan']
        ];
        return view('ppdb-online.embed.detail-payment', $data);
    }

    public function postFitting(Request $request)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $params = $request->validate([
            'id' => ['required', 'exists:product_fittings,id', new \App\Rules\ProductfittingAvailableRule],
        ]);

        if (ProductUserFitting::create([
            'fitting_id' => $params['id'],
            'user_id' => $user['id']
        ])
        ) {
            return Response::json(['status' => 'success']);
        }
    }

    public function postProduct(Request $request, CartService $cartService)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $params = $request->validate([
            'id' => 'required|exists:products,id',
            'detail_id' => 'required|exists:product_details,id',
            'qty' => 'required|numeric',
            'note' => 'nullable|string'
        ]);

        $validateShop = $cartService->add($params, $user);
//        if ($cartService->add($params, $user)) {
        return Response::json([
            'status' => $validateShop['status'],
            'message' => $validateShop['message'],
        ]);
//        }
    }

    public function postCart(Request $request, CartService $cartService)
    {
        $user = $request->session()->get('user');
        try {
            $this->validatePassedShopStages();
            $params = $request->validate([
                'products' => 'required',
                'products.*.qty' => ['required', 'numeric', 'min:1'],
                'products.*.price' => ['required', 'numeric'],
                'products.*.id' => ['required', 'exists:cart_details,id'],
                'products.*.include' => ['required', 'in:true,false'],
                'products.*.note' => ['nullable', 'string'],
            ]);
            if ($order = $cartService->store($params, $user)) {
                return Response::json([
                    'status' => 'success',
                    'order' => $order->id
                ]);
            }
        } catch (ValidationException $e) {
            return Response::json([
                'status' => 'false',
                'order' => $order->id
            ]);
        }
    }

    public function postVoucher(Request $request, CartService $cartService)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $params = $request->validate([
            'voucher' => 'required|string|exists:vouchers,code'
        ]);

        if ($eligible = $cartService->applyVoucher($params, $user)) {
            return Response::json([
                'status' => 'success',
                'voucher' => $eligible
            ]);
        }
    }

    public function deleteCart(Request $request, CartService $cartService)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $params = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'cart_detail_id' => 'required|exists:cart_details,id'
        ]);

        if ($cartService->delete($params, $user)) {
            return Response::json(['status' => 'success']);
        }
    }

    public function deleteVoucher(Request $request, CartService $cartService)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        if ($cartService->deleteVoucher($user)) {
            return Response::json(['status' => 'success']);
        }
    }

    public function cancelOrder(Request $request, ProductOrderService $productOrderService)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $params = $request->validate([
            'product_order_id' => 'required|exists:product_orders,id'
        ]);

        if ($productOrderService->cancel($params, $user, 1)) {
            return Response::json(['status' => 'success']);
        }
    }

    public function getOrder(Request $request, $id)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();
        $now = Carbon::now()->format('Y-m-d');

        $order = ProductOrder::where([
            'id' => $id,
            'user_id' => $user['id']
        ])->with('productOrderDetails', 'productOrderDetails.productDetail', 'productOrderDetails.product')->firstOrFail();

        $periodComplaint = ComplaintPeriode::where('type', 'ppdb')->first();

        $is_complaint = false;
        if ($periodComplaint) {
            if ($periodComplaint->status == 'all') {
                $is_complaint = true;
            } else {
                if (($now >= $periodComplaint->date_start) && ($now <= $periodComplaint->date_end)) {
                    $is_complaint = true;
                }
            }
        }


        $data = [
            'order' => $order,
            'user' => PPDBUser::where('user_id', $user['id'])->firstOrFail(),
            'is_complaint' => $is_complaint,
            'periodComplaint' => $periodComplaint,
            'nav' => ['parent' => 'product', 'child' => 'Pesanan']
        ];

        return view('ppdb-online/embed/order', $data);
    }

    public function showPdf(Request $request, $id)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $productOrder = ProductOrder::where([
            'id' => $id,
            'user_id' => $user['id']
        ])->with('productOrderDetails', 'productOrderDetails.productDetail', 'productOrderDetails.product')->firstOrFail();

        $data = [
            'productOrder' => $productOrder,
            'user' => PPDBUser::where('user_id', $user['id'])->firstOrFail(),
        ];

        $pdf = \PDF::loadView('ppdb-online.embed.pdf', $data);
        return $pdf->download("detail-transaksi-$productOrder->invoice_no.pdf");
    }

    public function uploadOrderConfirmation(Request $request)
    {
        $data = [];
        $type = 'payment_image';
        $user = $request->session()->get('user');
        try {
            if ($request->hasFile($type)) {
                $upload = $this->doUploadImage($request->file($type), $type);

                $update = array(
                    $type => $upload['path_upload'],
                );

                $order = ProductOrder::where('id', $request->input('id'))->where('user_id', $user['id'])->firstOrFail();
                $order->update($update);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

        return response()->json($upload, 200);
    }

    public function getOrderList(Request $request)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $orders = ProductOrder::where([
            'user_id' => $user['id']
        ])->with('productOrderDetails', 'productOrderDetails.product')->orderBy('created_at', 'desc')->get();

        $data = [
            'orders' => $orders,
            'nav' => ['parent' => 'product', 'child' => 'Daftar Pesanan']
        ];

        return view('ppdb-online/embed/order-list', $data);
    }

    private function validatePassedShopStages()
    {
        $user = request()->session()->get('user');

        $ppdb = PPDBUser::where('user_id', $user['id'])->firstOrFail();

        $shopStagesPassed = $ppdb->stages()->filter(function ($item) {
            return $item->is_opening_shop_feature && $item->passed == PPDBUserStage::TEXT_LOLOS;
        })->count();

        if (!$shopStagesPassed) { //&& !$ppdb->is_statement_letter_confirmed) {
            $data = ['nav' => ['parent' => 'product', 'child' => 'Seragam']];
            abort(response()->view('ppdb-online/embed/noaccess', $data));
        }

    }

    public function getDevelopmentStatementLetterFile()
    {
        $user = request()->session()->get('user');
        $ppdbUser = PPDBUser::where('user_id', $user['id'])->firstOrFail();
        $filename = $ppdbUser->getDevelopmentStatementUrl();

        $type = (strpos($filename, '.jpg') !== false) ? "image/jpeg" : ((strpos($filename, '.jpeg') !== false) ? "image/jpeg" : ((strpos($filename, '.pdf') !== false) ? 'application/pdf' : "image/png"));

        return response($ppdbUser->getDevelopmentStatementFile())->withHeaders([
            'Content-Type' => $type,
        ]);
    }

    public function postResetDevelopmentFee(Request $request)
    {
        $user = request()->session()->get('user');
        $ppdbUser = PPDBUser::where('user_id', $user['id'])->firstOrFail();

        $ppdbUser->development_fee_option = null;
        $ppdbUser->development_statement = null;
        $ppdbUser->angsuran_1 = null;
        $ppdbUser->angsuran_2 = null;
        $ppdbUser->angsuran_3 = null;
        $ppdbUser->angsuran_4 = null;
        $ppdbUser->angsuran_5 = null;
        $ppdbUser->cicilan_1 = null;
        $ppdbUser->cicilan_2 = null;
        $ppdbUser->cicilan_3 = null;
        $ppdbUser->cicilan_4 = null;
        $ppdbUser->cicilan_5 = null;

        $ppdbUser->save();
        $ppdbUser->refresh();

        return response()->json(['status' => 'success'], 200);
    }

    public function detailVoucher(Request $request, Voucher $voucher)
    {
        $data = Voucher::where('id', $request->id)->first();

        return view('ppdb-online/embed/_modal_detail_voucher', ['voucher' => $data]);
    }

    public function checkOrders($user_id)
    {
        $orders = ProductOrder::where(['status' => 'new_order', 'user_id' => $user_id])->get();

        $no_invoice = '';
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $no_invoice .= $order->invoice_no . ', ';
            }
            $no_invoice = substr($no_invoice, 0, -2);
        }

        return ['orders' => count($orders), 'no_invoice' => $no_invoice];
    }

    public function postCancelOrder(Request $request, ProductOrderService $productOrderService)
    {
        $user = $request->session()->get('user');

        $this->validatePassedShopStages();

        $params = $request->validate([
            'product_order_id' => 'required|exists:product_orders,id',
            'payment_cancel_reason' => 'required|string'
        ]);

        if ($productOrderService->cancel($params, $user, 2)) {
            return Response::json(['status' => 'success']);
        }
    }

    public function notificationIndex(Request $request, NotificationService $notificationService)
    {
        $user = $request->session()->get('user');
        $notifications = $notificationService->filter([
            'notifiable_type' => PPDBUser::class,
            'notifiable_id' => $user['ppdb']['id']
        ]);

        return view('ppdb-online/notification/index', [
            'notifications' => $notifications
        ]);
    }

    public function notificationMarkRead(Notification $notification)
    {
        $notification->markAsRead();

        return Redirect::route('ppdb.notification.index')->with('message', 'Success mark as read notification');
    }

    public function notificationDelete(Notification $notification)
    {
        $notification->delete();
        return Redirect::route('ppdb.notification.index')->with('message', 'Success delete notification');
    }

    public function customFormInput($slug, Request $request)
    {
        $user = $request->session()->get('user');
        $user_ppdb = PPDBUser::where('user_id', $user['id'])->with('unit', 'period')->first();

        $customForm = CustomForm::with([
            'columns' => function ($queryColumn) {
                $queryColumn->orderBy('order', 'ASC');
            },
            'columnInputs' => function ($queryInput) use ($user_ppdb) {
                $queryInput->where('ppdb_user_id', $user_ppdb->id);
            }
        ])->whereSlug($slug)->first();

        return view('ppdb-online.custom_form.index', [
            'customForm' => $customForm
        ]);
    }

    public function customFormInputPost($slug, Request $request)
    {
        $user = $request->session()->get('user');
        $user_ppdb = PPDBUser::where('user_id', $user['id'])->with('unit', 'period')->first();

        foreach ($request->except('_token') as $column_id => $value) {
            CustomFormInput::updateOrCreate(
                [
                    'custom_form_column_id' => $column_id,
                    'ppdb_user_id' => $user_ppdb->id,
                ], [
                    'value' => $value,
                ]
            );
        }

        return Redirect::route('ppdb.data-siswa-ppdb')->with('message', 'Success saved');
    }

    public function getPaymentRegistration(Request $request)
    {
        $user_ppdb = PPDBUser::where('id', $request->id)->with('unit', 'period')->first();

        return view('ppdb-online.detail-payment-register', ['data' => $user_ppdb]);
    }

    public function repaymentRegistration(Request $request)
    {
        $ppdbUser = PPDBUser::where('id', $request->id)->firstOrFail();
        $bank_account = \App\Helpers\PriceHelper::paymentInfo($ppdbUser->unit, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL)['bank'];
        $va_account = \App\Helpers\PriceHelper::virtualAccountNumber($ppdbUser, false, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL);

        $ppdbUser->total_payment_form = \App\Helpers\PriceHelper::registration($ppdbUser, false);
        $ppdbUser->expired_at = Carbon::parse($this->expiredRemining())->format('Y-m-d H:i:s');
        $ppdbUser->payment_option = $bank_account;
        $ppdbUser->virtual_account_number = $va_account;

        $ppdbUser->save();

    }

    public function expiredRemining()
    {

        $currentDateTime = Carbon::now();
        $expired_at = Carbon::now()->addDay();

        return $expired_at;
    }

    public function complaint(Request $request)
    {
        $productOrder = ProductOrder::whereId($request->id)->first();

        $historyComplaint = ComplaintOrders::where('product_order_id', $productOrder->id)->orderBy('id', 'desc')->get();

        $products = [];
        foreach ($productOrder->productOrderDetails as $item) {
            $products[$item->id] = $item->product->name . ' (Size : ' . $item->productDetail->size . ')';
        }

        $complaintCategory = ComplaintCategory::where('status', 1)->get();

        $data = [
            'productOrder' => $productOrder,
            'products' => $products,
            'historyComplaint' => $historyComplaint,
            'complaintCategory' => $complaintCategory
        ];
        return view('ppdb-online.embed.complaint', $data);
    }

    public function fetchProductOrder(Request $request)
    {
        $productOrderDetail = ProductOrderDetail::whereId($request->id)->first();

        $html = '<div style="margin-left: 2em" class="text-title-3 font-italic text-black">Qty : ' . $productOrderDetail->quantity . '</div>';
        $html .= '<div style="margin-left: 2em" class="text-title-3 font-italic text-black">Size : ' . $productOrderDetail->productDetail->size . '</div>';
        $html .= '<div style="margin-left: 2em" class="text-title-3 font-italic text-black">Note : ' . $productOrderDetail->note . '</div><br><br>';

        return $html;
    }

    public function complaintStore(ComplaintOrderRequest $request, ProductOrderComplaintService $productOrderComplaintService)
    {
        DB::beginTransaction();
        try {
            $validate = $request->validated();

            $productOrderDetail = ProductOrderDetail::whereId($request->product_id)->first();

            if (isset($productOrderDetail)) {
                $dataCompaint = ComplaintOrders::where([
                    'product_order_id' => $request->product_order_id,
                    'product_order_detail_id' => $request->product_id
                ])->first();

                if (empty($dataCompaint)) {

                    $user = $request->session()->get('user');
                    $payload = [
                        'product_order_id' => $productOrderDetail->product_order_id,
                        'product_id' => $productOrderDetail->product_id,
                        'product_detail_id' => $productOrderDetail->product_detail_id,
                        'user_id' => $user['id'],
                        'type' => 'ppdb'
                    ];

                    $store = $productOrderComplaintService->store($request->all(), $payload, $user);

                    if ($store['success'] == true) {
                        DB::commit();
                        return redirect()->route('ppdb.embed-product.complaint', ['id' => $productOrderDetail->product_order_id])->with(['message' => 'Komplain sudah terkirim, silahkan tunggu konfirmasi admin', 'success' => true]);
                    } else {
                        DB::rollBack();
                        return redirect()->route('ppdb.embed-product.complaint', ['id' => $productOrderDetail->product_order_id])->with(['message' => $store['message'], 'success' => false])->withErrors(new \Illuminate\Support\MessageBag());
                    }

                } else {
                    return redirect()->route('ppdb.embed-product.complaint', ['id' => $productOrderDetail->product_order_id])->with(['message' => 'Anda telah mengajukan komplain untuk product ini!', 'success' => false])->withErrors(new \Illuminate\Support\MessageBag());
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function cancelComplaint(Request $request, ComplaintOrderService $complaintOrderService)
    {
        $data = ComplaintOrders::whereId($request->id)->first();

        $update = $complaintOrderService->changeStatus($request->id, ComplaintOrders::STATUS_CANCEL, '');
        return redirect()->route('ppdb.embed-product.complaint', ['id' => $data->productOrderDetail->product_order_id]);
    }

    public function showComplaintPdf(Request $request, $id)
    {
        $this->fillDataVarible();

        $complaintOrder = ComplaintOrders::where([
            'id' => $id,
        ])->firstOrFail();

        $productOrder = ProductOrder::where([
            'id' => $complaintOrder->product_order_id
        ])->firstOrFail();

        $orderDetail = ProductOrderDetail::where('id', $complaintOrder->product_order_detail_id)->firstOrFail();
        $user = User::where('id', Auth::user()->id)->first();

        $data = [
            'complaintOrder' => $complaintOrder,
            'productOrder' => $productOrder,
            'orderDetail' => $orderDetail,
            'user' => $user,
        ];

//        return view('student-dashboard.shop.pdf_complaint', $data);

        $pdf = \PDF::loadView('student-dashboard.shop.pdf_complaint', $data);
        $date_complaint = Carbon::parse($complaintOrder->created_at)->format('Ymd');
        return $pdf->download("detail-complaint-" . $date_complaint . "-" . $complaintOrder->user->name . ".pdf");
    }

    private function fillDataVarible()
    {
        $this->user = Auth::guard('siswa')->user();
        $this->user->loadMissing('ppdb', 'student', 'student.class', 'student.class.unit');
        $this->student = $this->user->student;
        $this->class = $this->student->class;
    }

    public function getCities(Request $request)
    {
        //cari provisi
        $getProvince = Provinces::where('name', $request->province_id)->first();
        if (isset($getProvince)) {
            $cities = Regencies::where('province_id', $getProvince->id)
                ->orderBy('name', 'asc')
                ->get(['id', 'name']);
        }

        return response()->json($cities);
    }

    public function financeBills(Request $request, PPDBUserService $ppdbUserService, PaymentDispensationsService $paymentDispensationsService)
    {
        $user = $request->session()->get('user');
        $ppdbUser = PPDBUser::where('id', $user['ppdb']['id'])->first();

        $is_show = false;
        // if($ppdbUser->development_fee_option == PPDBUser::DEVELOPMENT_FEE_ANGSURAN){
        //     $is_show = true;
        // }

        $bills = $ppdbUserService->getBills($user['ppdb']['id']);

        $dispensation = $paymentDispensationsService->getAllBilling($user['ppdb']['id']);

        $is_dispensation = false;
        if($dispensation){
            if($dispensation->dispensation_mode != PaymentDispensations::MODE_REAL_PAYMENT){
                $is_dispensation = true;
            }
        }
        $data = array(
            'bills' => $bills['bills'],
            'bill_amount' => $bills['bill_amount'],
            'ppdb'=>$user['ppdb'],
            'is_dispensation'=>$is_dispensation,
            'dispensation'=>$dispensation,
            'nav' => ['parent' => 'data', 'child' => 'Data Siswa']
        );

        return view('ppdb-online.finance.form-finance-bills', $data);
    }

    public function registrationPaymentReceipt($id){
        $ppdb = PPDBUser::where('id', $id)->first();

        $data = array(
            'data' => $ppdb,
            'nav' => ['parent' => 'data', 'child' => 'Data Siswa']
        );

        return view('ppdb-online.finance.partial._registration_receipt', $data);
    }

}
