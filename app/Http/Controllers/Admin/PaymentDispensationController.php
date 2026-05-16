<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentDispensationsRequest;
use App\Models\PaymentDispensations;
use App\Services\PaymentDispensationsService;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\PPDBUser;


class PaymentDispensationController extends Controller
{
     private $page = [
        'parent' => 'finance-configuration',
        'child' => 'dispensations'
    ];

    public function index(PaymentDispensationsService $paymentDispensationService, Request $request){
        $datas = $paymentDispensationService->get();

        $dispensations = [];
        foreach ($datas as $dispensation) {
            $dispensations[$dispensation->id]['id'] = $dispensation->id;
            $dispensations[$dispensation->id]['ppdb_user_id'] = $dispensation->ppdb_user_id;
            $dispensations[$dispensation->id]['name'] = $dispensation->ppdb->name;
            $dispensations[$dispensation->id]['unit_id'] = $dispensation->unit_id;
            $dispensations[$dispensation->id]['unit_name'] = $dispensation->ppdb->unit->name;
            $dispensations[$dispensation->id]['school_year'] = $dispensation->school_year;
            $dispensations[$dispensation->id]['dispensation_type'] = $dispensation->dispensation_type;
            $dispensations[$dispensation->id]['total_final_fee'] = $dispensation->total_final_fee;
            $dispensations[$dispensation->id]['remaining_balance'] = $dispensation->remaining_balance;
            $dispensations[$dispensation->id]['actual_cost'] = $dispensation->actual_cost;
            $dispensations[$dispensation->id]['dispensation_mode'] = ($dispensation->dispensation_mode == PaymentDispensations::MODE_FULL_SETUP) ? 'Full Setup (Admin Tentukan Cicilan)' : 'Hanya Potongan (Siswa Pilih Skema)';
            $dispensations[$dispensation->id]['dispensation'] = $dispensation->dispensation_mode;

            if(!empty($dispensation->value)){
                foreach(json_decode($dispensation->value) as $key => $value){
                    $dispensations[$dispensation->id][$key] = $value;
                }
            }
        }

        return view('administrator.payment-dispensations.list', [
            'nav' => $this->page,
            'dispensations' => $dispensations
        ]);
    }

    public function add(Request $request)
    {
        $start_year = date('Y') - 3;
        $school_year = [];
        for($start_year; $start_year <= date('Y'); $start_year++){
            $school_year[] = $start_year;
        }
        $params = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'school_year' => $school_year
        ];

        return view('administrator/payment-dispensations/add', $params);
    }

    public function fetchStudent(Request $request)
    {
        $users = PpdbUser::with('user')
            ->where('school_year', $request->school_year)
            ->where('unit_id', $request->unit_id)
            ->whereHas('user', function($query) {
                $query->where('type', 'ppdb');
            })
            ->get();
        $collections = collect();
        foreach ($users as $user) {
            $collections->put($user->id, '[' . $user->register_number . '] ' . $user->name);
        }

        return $collections;
    }

    public function fetchAnualCost(Request $request, PaymentDispensationsService $paymentDispensationService){
        $price = 0;
        $status = 'error';
        $message = 'Tipe dispensasi tidak valid.';
        $ppdb = PPDBUser::where('id', $request->ppdb_user_id)->first();
        if ($ppdb) {
            if($request->type == 'development'){
                $dispensation = $paymentDispensationService->getByUserPpdb($request->ppdb_user_id);
                if(!empty($dispensation)){
                    $price = $dispensation->remaining_balance;
                    $status = 'success';
                    if($dispensation->dispensation_mode == PaymentDispensations::MODE_REAL_PAYMENT){
                        $message = 'Siswa '. $ppdb->name .' sudah menentukan pembayaran dan muungkin sudah melakukan pembayaran';
                    }else{
                        $message = 'Siswa '. $ppdb->name .' sudah mendapatkan dispensasi dan muungkin sudah melakukan pembayaran';
                    }
                }else{
                    $price = \App\Helpers\PriceHelper::development($ppdb, false);
                    if(!$price){
                        $message = 'Biaya pengembangan tidak ditemukan untuk siswa ini.';
                    }else{
                        $status = 'success';
                        $message = '';
                    }
                }
            }
        } else {
            $message = 'Data siswa tidak ditemukan.';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'actual_cost' => $price
        ]);
    }

    public function store(PaymentDispensationsRequest $request, PaymentDispensationsService $paymentDispensationService){

        try {
            $input = $request->validated();
            $ppdb = PPDBUser::where('id', $input['ppdb_user_id'])->first();
            $va_full_statement = $paymentDispensationService->virtualAccountNumber($ppdb, PaymentDispensations::TYPE_FULL);
            $va_partial = $paymentDispensationService->virtualAccountNumber($ppdb, PaymentDispensations::TYPE_PARTIAL);
            $json_value = [];

            if($input['dispensation_mode'] == PaymentDispensations::MODE_FULL_SETUP){
                $json_value['down_payment'] = (!empty($input['down_payment'])) ? $input['down_payment'] : 0;
                $json_value['tenor'] = $input['tenor'];
            }
            $input['remaining_balance'] = $input['total_final_fee'];
            $json_value['va_full_statement'] = $va_full_statement;
            $json_value['va_partial'] = $va_partial;
            $input['payment_type'] = 'cicilan';
            $input['value'] = json_encode($json_value);
            $paymentDispensationService->create($input, $ppdb, 'admin');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('admin.dispensation.index')->with('errors', collect(['Gagal ditambahkan']));
        }
        return redirect()->route('admin.dispensation.index')->with('message', 'Berhasil ditambahkan');
    }
}
