<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PPDBUserTemplateExport;
use App\Helpers\Helper;
use App\Http\Requests\PPDBImportRequest;
use App\Imports\PPDBImport;
use App\Services\UnitService;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Http\Requests\StudentAcceptanceRequest;
use App\Http\Requests\NotificationRequest;
use App\Services\OpenApi\v1\PaymentBCAService;
use App\Http\Requests\PPDBAdminRequest;
use App\Services\NotificationService;
use App\Http\Controllers\Controller;
use App\Services\PPDBUserService;
use App\Exports\PPDBUsersExport;
use App\Services\ParentService;
use App\Mail\EmailVerification;
use App\Services\EmailService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\PPDBUser;
use App\Models\Parents;
use App\Models\Stage;
use App\Models\Unit;
use App\Models\User;
use App\Lib\DbTrx;
use Illuminate\Support\MessageBag;

class PPDBController extends Controller
{
    private $page = [
        "parent" => "ppdb",
        "child" => "ppdb"
    ];

    public function index(Request $request)
    {
        $data = PPDBUser::query();

        if ($request->input('search')) {
            if ($request->input('scope') == 'register_number') {
                $data = $data->where('register_number', 'like', '%' . $request->input('search') . '%');
            } else {
                $data = $data->where('name', 'like', '%' . $request->input('search') . '%');
            }
        }
        if ($request->input('unit')) {
            $data = $data->where('unit_id', $request->input('unit'));
        }

        if ($request->input('school_year')) {
            if ($request->input('school_year') != 'all') {
                // $data = $data->whereRaw("LEFT(`ppdb_users`.`register_number`, 2) = '". substr($request->input('school_year'), -2)."'");
                $data = $data->where('school_year', $request->input('school_year'));
            }
        } else {
            $data = $data->where('school_year', (now()->month > 6 ? (now()->year + 1) : now()->year));
        }

        if ($request->input('period') == 'ongoing') {
            $data = $data->ongoingPeriod();
        }

        if ($request->input('status')) {
            // get Data
            $data = $data->notAccepted()
                ->byUserRole()
                ->with(['unit', 'user', 'parents', 'order'])
                ->orderBy('register_number', 'desc')
                ->get();

            $collection = [];

            switch ($request->input('status')) {

                case 'email_verified':
                    $verifiedEmail = [];
                    $unverifiedEmail = [];

                    foreach ($data as $key => $value) {
                        if ($value->isEmailVerified) {
                            array_push($verifiedEmail, $value);
                        } else {
                            array_push($unverifiedEmail, $value);
                        }
                    }

                    $collection = array_merge($verifiedEmail, $unverifiedEmail); // Make verified data first before unverified data

                    break;

                case 'payment_status':
                    $confirmed = [];
                    $uploaded = [];
                    $null_data = [];

                    foreach ($data as $key => $value) {
                        if ($value->isPaymentStatusComplete) {
                            array_push($confirmed, $value);
                        } elseif ($value->isPaymentStatusVerified) {
                            array_push($uploaded, $value);
                        } else {
                            array_push($null_data, $value);
                        }
                    }

                    $collection = array_merge($confirmed, $uploaded, $null_data);

                    break;

                case 'student_data':
                    $CompleteData = [];
                    $UnCompleteData = [];

                    foreach ($data as $key => $value) {
                        if ($value->isDataComplete) {
                            array_push($CompleteData, $value);
                        } else {
                            array_push($UnCompleteData, $value);
                        }
                    }

                    $collection = array_merge($CompleteData, $UnCompleteData);

                    break;

                case 'parent_data':
                    $CompleteData = [];
                    $UnCompleteData = [];

                    foreach ($data as $key => $value) {
                        if ($value->isParentsComplete) {
                            array_push($CompleteData, $value);
                        } else {
                            array_push($UnCompleteData, $value);
                        }
                    }

                    $collection = array_merge($CompleteData, $UnCompleteData);

                    break;

                case 'statement_letter':
                    $confirmed = [];
                    $uploaded = [];
                    $null_data = [];

                    foreach ($data as $key => $value) {
                        if ($value->IsStatementLetterConfirmed) {
                            array_push($confirmed, $value);
                        } elseif ($value->isStatementLetterUploaded) {
                            array_push($uploaded, $value);
                        } else {
                            array_push($null_data, $value);
                        }
                    }

                    $collection = array_merge($confirmed, $uploaded, $null_data);

                    break;

                case 'accepted':
                    $accepted = [];
                    $rejected = [];

                    foreach ($data as $key => $value) {
                        if ($value->isSubmitted && $value->isOrderConfirmed) {
                            array_push($accepted, $value);
                        } else {
                            array_push($rejected, $value);
                        }
                    }

                    $collection = array_merge($accepted, $rejected);

                    break;

                default:
                    # code...
                    break;
            }

            $data = $collection;

            // Make Pagination Manually
            $total = count($data);
            $per_page = 15;
            $current_page = $request->input("page") ?? 1;

            $starting_point = ($current_page * $per_page) - $per_page;

            $data = array_slice($data, $starting_point, $per_page, true);

            $data = new Paginator($data, $total, $per_page, $current_page, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);

        } else {

            $data = $data->notAccepted()
                ->byUserRole()
                ->with(['unit', 'user', 'parents', 'order'])
                ->orderBy('register_number', 'desc')
                ->paginate();

        }

        return view('administrator.ppdb.list', [
            'data' => $data,
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'classes' => Classes::select('id', 'unit_id', 'name')->get(),
            'params' => $request->only(['search', 'scope', 'unit', 'school_year', 'period', 'status'])
        ]);
    }

    public function show($id, PPDBUserService $ppdbUserService, ParentService $parentService)
    {
        $ppdbUser = $ppdbUserService->show($id);
        $mom = $parentService->show(Parents::TYPE_MOTHER, $ppdbUser->user_id);
        $dad = $parentService->show(Parents::TYPE_FATHER, $ppdbUser->user_id);
        $wali = $parentService->show(Parents::TYPE_WALI, $ppdbUser->user_id);
        $data = array(
            'data' => $ppdbUser,
            'nav' => $this->page,
            'dad' => $dad,
            'mom' => $mom,
            'wali' => $wali
        );
        return view('administrator/ppdb/show', $data);
    }

    public function showPayment($id, PPDBUserService $ppdbUserService, ParentService $parentService)
    {
        $ppdbUser = $ppdbUserService->show($id);
        $data = array(
            'data' => $ppdbUser,
            'nav' => $this->page,
        );
        return view('administrator/ppdb/show_payment', $data);
    }

    public function add()
    {
        $data = [
            'data' => '',
            'nav' => $this->page,
            'units' => Unit::byUserRole()->select('id', 'name', 'unit_code')->with('periods')->get()->keyBy('id')->toArray()
        ];

        return view('administrator/ppdb/add-new', $data);
    }

    public function ajax(Request $request)
    {
        $data = [];
        switch ($request->input('type')) {
            case 'auto':
                $unit_id = $request->input('unit_id');
                $period_id = $request->input('periode');
                $data = (new \App\Services\UserService())->generateRegisterNumber(null, $unit_id, $period_id, true);
                break;
            case 'check':
                $registerNumber = $request->input('register_number');
                $data = PPDBUser::select('register_number')->where('register_number', $registerNumber)->first();
                break;
        }

        return response()->json($data);
    }

    public function insert(
        PPDBAdminRequest $request,
        UserService      $userService,
        EmailService     $emailService,
        ParentService    $parentService,
        PPDBUserService  $ppdbUserService
    )
    {
        $input = $request->validated();
        if (!isset($input['send_confirmation'])) {
            $emailService = null;
        }

        DbTrx::useTrx(
            function () use ($userService, $input, $emailService, $parentService, $ppdbUserService) {
                $user = $userService->register(User::PPDB, $input, $emailService, true);
                $input['user_id'] = $user->id;
                if ($input['tinggal_dengan'] === 'wali') {
                    $parentService->createWali($input);
                } else {
                    $parentService->createFather($input);
                    $parentService->createMother($input);
                }
                $ppdbUserService->uploadImages($user->ppdb, $input);
            }
        );
        return redirect()->route('admin.ppdb.index')->with('message', 'Berhasil ditambahkan');
    }

    public function edit($id, PPDBUserService $ppdbUserService, ParentService $parentService)
    {
        $ppdbUser = $ppdbUserService->show($id);
        $mom = $parentService->show(Parents::TYPE_MOTHER, $ppdbUser->user_id);
        $dad = $parentService->show(Parents::TYPE_FATHER, $ppdbUser->user_id);
        $data = array(
            'data' => $ppdbUser,
            'method' => 'edit',
            'nav' => $this->page,
            'dad' => $dad,
            'mom' => $mom,
        );
        return view('administrator/ppdb/add', $data);
    }

    public function update(
        PPDBAdminRequest $request,
                         $id,
        PPDBUserService  $ppdbUserService,
        ParentService    $parentService
    )
    {
        $input = $request->validated();

        DbTrx::useTrx(
            function () use ($ppdbUserService, $parentService, $input, $id) {
                $ppdbUserService->update($id, $input);
                $parentService->updateFather($id, $input);
                $parentService->updateMother($id, $input);
            }
        );

        return redirect()->route('admin.ppdb.index')->with('message', 'Berhasil diedit');
    }

    public function delete(Request $request, $id)
    {
        try {
            PPDBUser::where('id', '=', $id)
                ->delete();
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.ppdb.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.ppdb.index')->with('message', 'Berhasil dihapus');
    }

    public function confirmPayment($id, PPDBUserService $ppdbUserService)
    {
        if ($ppdbUserService->confirmPayment($id))
            return redirect()->route('admin.ppdb.index')->with('message', 'Pembayaran Berhasil dikonfirmasi');
        return redirect()->route('admin.ppdb.index')->with('errors', 'Gagal dikonfirmasi');
    }

    public function rejectPayment($id, Request $request, PPDBUserService $ppdbUserService)
    {
        if ($ppdbUserService->rejectPayment($id, $request->all()))
            return redirect()->route('admin.ppdb.index')->with('message', 'Pembayaran telah ditolak');
        return redirect()->route('admin.ppdb.index')->with('errors', 'Penolakan gagal');
    }

    public function confirmDevelopmentStatement($id, PPDBUserService $ppdbUserService)
    {
        if ($ppdbUserService->confirmDevelopmentStatement($id))
            return redirect()->back()->with('message', 'Surat Pernyatan Berhasil dikonfirmasi');
        return redirect()->back()->with('errors', 'Gagal dikonfirmasi');
    }

    public function confirm($id, StudentAcceptanceRequest $request, PPDBUserService $ppdbUserService)
    {
        $params = $request->validated();

        if ($ppdbUserService->confirm($id, $params)) {
            return response()->json(['message' => 'Berhasil dikonfirmasi'], 200);
        } else {
            return response()->json(['message' => 'Gagal dikonfirmasi'], 500);
        }
        //return redirect()->route('admin.ppdb.index')->with('message', 'Berhasil dikonfirmasi');
        //return redirect()->route('admin.ppdb.index')->with('errors', 'Gagal dikonfirmasi');
    }

    public function sendConfirmation($id, EmailService $emailService)
    {
        $ppdbUser = PPDBUser::where('id', $id)->firstOrFail();
        $user = $ppdbUser->user;

        $template = (new EmailVerification($user, $ppdbUser));
        if (isset($emailService) && $emailService)
            $emailService->sendMail($template, $user->email);
    }

    public function export(Request $request)
    {
        $PPDBUsersExport = new PPDBUsersExport($request->all());
        $title = "Exports Data PPDB Users " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $PPDBUsersExport->setTemplate(true);
            $title = "Template Import PPDB Users.xlsx";
        }

        return $PPDBUsersExport->download($title);
    }

    public function getDevelopmentStatementLetterFile($id)
    {
        $ppdbUser = PPDBUser::where('id', $id)->firstOrFail();
        $filename = $ppdbUser->getDevelopmentStatementUrl();

        $type = (strpos($filename, '.jpg') !== false) ? "image/jpeg" : ((strpos($filename, '.jpeg') !== false) ? "image/jpeg" : ((strpos($filename, '.pdf') !== false) ? 'application/pdf' : "image/png"));

        return response($ppdbUser->getDevelopmentStatementFile())->withHeaders([
            'Content-Type' => $type,
        ]);
    }

    public function resetDevelopmentPaymentMethod(PPDBUser $ppdbUser, NotificationRequest $request, NotificationService $notificationsService)
    {
        $resetDevelopmentPayment = $ppdbUser->resetDevelopmentPaymentMethod();
        if ($resetDevelopmentPayment) {
            $notificationsService->create($request->validated());
            return redirect()->route('admin.ppdb.index')->with('message', 'Berhasil direset');
        } else {
            return redirect()->route('admin.ppdb.index')->with('error', 'Terjadi Kesalahan pada Server');
        }

    }

    public function checkInquiryStatus($id, PaymentBCAService $paymentBCAService, PPDBUserService $ppdbUserService)
    {
        $ppdbUser = PPDBUser::where('id', $id)->firstOrFail();

        if (isset($ppdbUser)) {

            $virtualAccountNo = \App\Helpers\PriceHelper::virtualAccountNumber($ppdbUser, true, \App\Helpers\Helper::isVaBcaEnable() ? 'BCA' : NULL);
            $customerNo = substr($virtualAccountNo, 5, 16);
            $lengthString = 8 - (strlen(env('PAYMENT_BCA_API_CORP_ID')));
            $lengthVA = strlen($virtualAccountNo);

            $params = [
                'partnerServiceId' => str_pad(env('PAYMENT_BCA_API_CORP_ID', '13977'), 8, " ", STR_PAD_LEFT),
                'customerNo' => $customerNo,
                'virtualAccountNo' => str_pad($virtualAccountNo, $lengthVA + $lengthString, " ", STR_PAD_LEFT),
                'inquiryRequestId' => !empty($productOrder->payment_inquiry_id) ? $productOrder->payment_inquiry_id : '202212150953591397700040992856',
                'paymentRequestId' => !empty($productOrder->payment_inquiry_id) ? $productOrder->payment_inquiry_id : '202212150953591397700040992856',
                'additionalInfo' => (object)array(),
            ];

            $validator = validator($params, [
                'partnerServiceId' => ['string'],
                'customerNo' => ['required', 'string', 'max:18'],
                'virtualAccountNo' => ['required', 'string', 'max:18'],
            ], [], [
                'partnerServiceId' => 'Company Code',
                'customerNo' => 'Customer Number',
                'virtualAccountNo' => 'Customer Number',
            ]);

            try {
                $isoTime = date('o-m-d') . 'T' . date('H:i:s') . date('P');
                $token = $paymentBCAService->getAccessToken($isoTime);
                if ($token != null) {
                    $result = $paymentBCAService->inquiryStatus($params, $token, $isoTime);
                    if ($result->responseCode == '2002600') {
                        if ($result->virtualAccountData->paymentFlagStatus == '00') {
                            $billDetails = $result->virtualAccountData->billDetails;
                            $status = $result->virtualAccountData->paymentFlagReason->english;
                            foreach ($billDetails as $bill) {
                                if ($result->virtualAccountData->inquiryRequestId == $ppdbUser->payment_inquiry_id && $status == 'Success') {
                                    $confirmed = $paymentBCAService->getDebug() || $ppdbUserService->confirmRegistrations($id);
                                    if ($confirmed) {
                                        return redirect()
                                            ->back()
                                            ->with('message', 'Cek status berhasil, pembelian sudah dibayar dan terkonfirmasi');
                                    }
                                }
                            }
                        } else {
                            $billDetails = $result->virtualAccountData->billDetails;
                            foreach ($billDetails as $bill) {
                                if ($bill->status == '01') {
                                    return redirect()
                                        ->back()
                                        ->with(
                                            'errors',
                                            collect([
                                                'Error cek status pembayaran : ' . $bill->reason->indonesia
                                            ])
                                        );
                                }

                            }
                        }
                        return redirect()
                            ->back()
                            ->with('message', 'Cek status berhasil, belum ada pembayaran untuk siswa ' . $ppdbUser->name);
                    } else {
                        return redirect()
                            ->back()
                            ->with(
                                'errors',
                                collect([
                                    'Error cek status pembayaran : [' . $result->responseCode . '] ' . $result->responseMessage
                                ])
                            );
                    }
                } else {
                    return redirect()
                        ->back()
                        ->with('message', 'Cek status berhasil, belum ada pembayaran untuk siswa' . $ppdbUser->name);
                }

            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with(
                        'errors',
                        collect([
                            $e->getMessage()
                        ])
                    );
            }
        } else {
            return redirect()
                ->back()
                ->with('errors', [
                    'Data PPDB tidak ditemukan'
                ]);
        }
    }

    public function downloadTemplate(Request $request, UnitService $unitService)
    {
        $ppdbExport = new PPDBUserTemplateExport($request->all());

        $unit = Unit::whereId($request->unit)->first();

        $title = "Download Template Siswa PPDB " . $unit->name . '-' . $request->school_year . '_' . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $ppdbExport->setTemplate(true);
            $title = "Laporan Klaim Voucher.xlsx";
        }

        return $ppdbExport->download($title);
    }


    public function import(PPDBImportRequest $request, UserService $userService, PPDBUserService $PPDBUserService){

        ini_set('max_execution_time', '60');
        $sessionFlash = [];
        $input = $request->validated();

        $ppdbImport = new PPDBImport($userService, $PPDBUserService);


        $ppdbImport->import($input['file']);
        $reports = $ppdbImport->getReport();

        $sessionFlash = [
            'message' => count($reports['success']) . ' data berhasil diimport',
        ];

        if (isset($reports['failure']) && count($reports['failure'])) {
            $sessionFlash['errors'] = new MessageBag([
                'errors' => [
                    count($reports['failure']) . ' data gagal diimport<br/>' . implode('<br/>', $reports['failure'])
                ]
            ]);
        }
        // BANDAID SOLUTION FOR https://aimsis.atlassian.net/browse/AIMSIS-10509
        // RESOLVE IN THE FUTURE IMMEDIATELY
        ini_set('max_execution_time', '30');

        return redirect()->route('admin.ppdb.index')->with($sessionFlash);
    }

}
