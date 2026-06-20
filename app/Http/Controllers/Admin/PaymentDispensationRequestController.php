<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PaymentDispensationRequestService;
use App\Models\Unit;

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
}
