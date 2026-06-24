<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Services\PaymentDispensationRequestService;
use App\Models\Unit;
use App\Http\Requests\PaymentDispensationReqRequest;
use App\Models\PaymentDispensationRequest as PaymentDispensationRequestModel;

class PaymentDispensationRequestController extends Controller
{
    private $page = [
        'parent' => 'dispensation-request',
        'child' => 'dispensation-request'
    ];

    public function index(PaymentDispensationRequestService $paymentDispensationRequestService, Request $request){
        $data = $paymentDispensationRequestService->get();
        return view('administrator.payment-dispensation-request.list', [
            'nav' => $this->page,
            'data' => $data
        ]);
    }

    public function show($id, Request $request, PaymentDispensationRequestService $paymentDispensationRequestService){
        $data = $paymentDispensationRequestService->find($id);

        return view('administrator.payment-dispensation-request.show', [
            'nav' => $this->page,
            'data' => $data
        ]);
    }

    public function add(Request $request){

        $start_year = date('Y') - 3;
        $school_year = [];
        for($start_year; $start_year <= date('Y'); $start_year++){
            $school_year[] = $start_year;
        }

        $dispensation_type = [
            [
                'value' => 'development',
                'label' => 'Uang Pengembangan'
            ],
            [
                'value' => 'activity',
                'label' => 'Uang Kegiatan'
            ]
        ];

        $params = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'school_year' => $school_year,
            'dispensation_type' => $dispensation_type
        ];

        return view('administrator/payment-dispensation-request/add', $params);
    }

    public function store(PaymentDispensationReqRequest $request, PaymentDispensationRequestService $paymentDispensationRequestService){
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->filled('id')) {
                $dispensation = PaymentDispensationRequestModel::findOrFail($request->id);
                $dispensation->update($data);
                $message = 'Berhasil diubah';
            } else {
                $store = $paymentDispensationRequestService->store($request->all(), $data);
                if ($store['success'] == true) {
                    DB::commit();
                    return redirect()->route('admin.dispensation-request.index')->with(['message' => 'Pengjuan Dispensasi Berhasil Disimpan', 'success' => true]);
                } else {
                    DB::rollBack();
                    return redirect()->route('admin.dispensation-request.index')->with(['message' => $store['message'], 'success' => false])->withErrors(new \Illuminate\Support\MessageBag());
                }
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('errors', collect([$th->getMessage()]))->withInput();
        }
    }

    public function update(Request $request){
        $id = $request->query('id');
        $dispensation = PaymentDispensationRequestModel::findOrFail($id);

        $start_year = date('Y') - 3;
        $school_year = [];
        for($start_year; $start_year <= date('Y'); $start_year++){
            $school_year[] = $start_year;
        }

        $dispensation_type = [
            [
                'value' => 'development',
                'label' => 'Uang Pengembangan'
            ],
            [
                'value' => 'activity',
                'label' => 'Uang Kegiatan'
            ]
        ];

        // Ensure we load the student options for the current selection
        $arr_student = $dispensation->ppdb_user_id;

        $params = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'school_year' => $school_year,
            'dispensation_type' => $dispensation_type,
            'dispensation' => $dispensation,
            'status' => 'edit',
            'arr_student' => $arr_student
        ];

        return view('administrator/payment-dispensation-request/add', $params);
    }
}
