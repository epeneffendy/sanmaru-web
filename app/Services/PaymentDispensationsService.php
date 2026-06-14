<?php

namespace App\Services;

use App\Helpers\PriceHelper;
use App\Lib\DbTrx;
use App\Models\PaymentDispensationDetails;
use App\Models\PaymentDispensations;
use App\Models\PPDBUser;
use App\Models\StudentBills;
use Carbon\Carbon;

class PaymentDispensationsService {

    public function get()
    {
        $data = PaymentDispensations::where('dispensation_mode', '<>', PaymentDispensations::MODE_REAL_PAYMENT)->get();
        return $data;
    }

    public function getAllBilling($ppdb_user_id){
        $data = PaymentDispensations::where('ppdb_user_id',$ppdb_user_id)->where('status', PaymentDispensations::STATUS_ACTIVE)->orderBy('id', 'desc')->first();
        return $data;
    }

    public function getByUserPpdb($ppdb_user_id, $type = null){
        $query = PaymentDispensations::where('ppdb_user_id', $ppdb_user_id);

        if (!is_null($type)) {
            $query->where('dispensation_type', $type);
        }

        $data = $query->where('status', PaymentDispensations::STATUS_ACTIVE)->orderBy('id', 'desc')->first();

        return $data;
    }

    public function getById($id){
        $data = PaymentDispensations::where('id', $id)->where('status', PaymentDispensations::STATUS_ACTIVE)->orderBy('id', 'desc')->first();
        return $data;
    }

    public function getByUserPpdbWithVirtualAccount($ppdb_user_id, $virtual_account_number){
        $data = PaymentDispensations::select('payment_dispensations.*', 'payment_dispensation_details.virtual_account', 'payment_dispensation_details.nominal', 'payment_dispensation_details.id as detail_id')
            ->join('payment_dispensation_details', 'payment_dispensations.id', '=', 'payment_dispensation_details.payment_dispensation_id')
            ->where('payment_dispensations.ppdb_user_id', $ppdb_user_id)
            ->where('payment_dispensation_details.virtual_account', $virtual_account_number)
            ->where('payment_dispensations.status', PaymentDispensations::STATUS_ACTIVE)
            ->orderBy('payment_dispensations.id', 'desc')
            ->first();

        return $data;
    }

    public function getDetailById($id){
        $data = PaymentDispensations::select('payment_dispensations.*', 'payment_dispensation_details.virtual_account', 'payment_dispensation_details.nominal', 'payment_dispensation_details.id as detail_id','payment_dispensation_details.amount_paid')
            ->join('payment_dispensation_details', 'payment_dispensations.id', '=', 'payment_dispensation_details.payment_dispensation_id')
            ->where('payment_dispensation_details.id', $id)
            ->where('payment_dispensations.status', PaymentDispensations::STATUS_ACTIVE)
            ->first();

        return $data;
    }

    public function create($params, $ppdb, $type, $operator = 'user'){
        return \Illuminate\Support\Facades\DB::transaction(function () use ($params, $ppdb, $type, $operator) {

            $paymentTerm = StudentBills::PAYMENT_TERM_FULL;
            if($params['dispensation_mode'] == PaymentDispensations::MODE_ONLY_DISCOUNT){
                if($operator == 'admin'){
                    $paymentDispensation = PaymentDispensations::create($params);
                }else{
                    $paymentDispensation = $this->getByUserPpdb($params['ppdb_user_id'], $type);
                }
            }else{
                $paymentDispensation = PaymentDispensations::create($params);
            }

            if(($params['dispensation_mode'] == PaymentDispensations::MODE_FULL_SETUP)){
                $paymentTerm = StudentBills::PAYMENT_TERM_INSTALLMENT;
                $this->calculate($paymentDispensation->id, $params['total_final_fee'], $params['down_payment'], $params['remaining_balance'], $params['tenor'], $params['dispensation_mode'], $ppdb, $type);
            }

            if(($params['dispensation_mode'] == PaymentDispensations::MODE_REAL_PAYMENT) || ($params['dispensation_mode'] == PaymentDispensations::MODE_ONLY_DISCOUNT)){
                $arr_value = json_decode($params['value']);

                $tenor = $dp = 0;
                if($params['payment_type'] == 'cicilan'){
                    $paymentTerm = StudentBills::PAYMENT_TERM_INSTALLMENT;
                    $tenor = isset($arr_value->tenor) ? $arr_value->tenor : 0;
                    $dp = isset($arr_value->down_payment) ? $arr_value->down_payment : 0;
                }

                $is_save_detail = true;
                if($params['dispensation_mode'] == PaymentDispensations::MODE_ONLY_DISCOUNT && $operator == 'admin'){
                    $is_save_detail = false;
                }

                if($is_save_detail){
                    $mode = $params['dispensation_mode'];
                    $this->calculate($paymentDispensation->id, $params['total_final_fee'], $dp, $params['remaining_balance'], $tenor, $mode, $ppdb, $type);
                }

            }

            //update billing siswa
            $bills = StudentBills::where('ppdb_user_id', $ppdb->id)->where('type', $type)->orderBy('id', 'desc')->first();

            if($bills){
               $bills->payment_term = $paymentTerm;
               $bills->save();
            }
            return $paymentDispensation;
        });
    }

    public function calculate($paymentDispensationId, $total_final_fee, $down_payment, $remaining_balance, $tenor, $type, $ppdb, $dispensation_type){

        $arr_calculate = [];
        $nominal = $total_final_fee - $down_payment;
        $arr_calculate = $this->calculateInstallments($nominal, $down_payment, $tenor);

        $arr_dispensation = [];
        $const_type = 0;
        $is_installment = false;
        if(count($arr_calculate) > 1){
            $is_installment = true;
        }

        $code_payment = PaymentDispensations::CODE_PAYMENT_DEVELOPMENT;
        if($dispensation_type == PaymentDispensations::DISPENSATION_TYPE_ACTIVITY){
            $code_payment = PaymentDispensations::CODE_PAYMENT_ACTIVITY;
        }

        foreach($arr_calculate as $key => $value){
            $virtual_account_number = $this->virtualAccountNumber($ppdb, $code_payment, PaymentDispensations::TYPE_PENGEMBANGAN_LUNAS);

            if($is_installment){
                $const_type = PaymentDispensations::TYPE_PENGEMBANGAN_CICILAN;
                if($key == 0){
                    $const_type = PaymentDispensations::TYPE_PENGEMBANGAN_DP;
                }
                $virtual_account_number = $this->virtualAccountNumber($ppdb,$code_payment, $const_type, $key);
            }

            $arr_dispensation[] = [
                'payment_dispensation_id' => $paymentDispensationId,
                'installment_number' => $key,
                'virtual_account' => $virtual_account_number,
                'nominal' => $value,
                'status' => PaymentDispensationDetails::STATUS_UNPAID,
            ];
        }

        PaymentDispensationDetails::insert($arr_dispensation);
    }

    private function calculateInstallments($remaining_balance, $down_payment, $tenor)
    {
        $arr_calculate = [];
        if($tenor > 0){
            $installment = round($remaining_balance / $tenor);
            $arr_calculate[0] = (int)$down_payment;
            for($i = 0; $i < (int)$tenor; $i++){
                $arr_calculate[$i + 1]= $installment;
            }
        }else{
            $arr_calculate[0] = $remaining_balance;
        }

        return $arr_calculate;
    }

    public static function virtualAccountNumber($model, $code_payment, $const_type, $installment = null)
    {
        if (!$model instanceof PPDBUser || empty($model->unit) || empty($model->register_number)) {
            return null;
        }

        $installment_type = '';
        if($code_payment == PaymentDispensations::CODE_PAYMENT_DEVELOPMENT){
            $installment_type = PaymentDispensations::TYPE_PENGEMBANGAN_CICILAN;
        }

        $typePayment = $const_type;
        $paymentInfo = PriceHelper::paymentInfo($model->unit, 'BCA');
        $kodeBiller = $paymentInfo['kode_biller'] ?? null;

        if (empty($typePayment) || empty($kodeBiller)) {
            return null;
        }

        $unitCode = sprintf("%02d", $model->unit->id);
        $virtualAccount = "{$kodeBiller}{$unitCode}{$code_payment}{$model->register_number}";

        //Jika pembayaran lunas
        if ($const_type == PaymentDispensations::TYPE_PENGEMBANGAN_LUNAS) {
            return $virtualAccount;
        }

        if ($installment !== null) {
            $virtualAccount .= "{$typePayment}".sprintf("%02d", $installment);
        }else{
            $virtualAccount .= "{$installment_type}{$typePayment}";
        }

        return $virtualAccount;
    }

    public function fillable($ppdb,$total_final, $actual_cost, $type, $mode){
        $fillable = [
            'ppdb_user_id' => $ppdb->id,
            'unit_id' => $ppdb->unit_id,
            'school_year' => $ppdb->school_year,
            'total_final_fee' => $total_final,
            'actual_cost' => $actual_cost,
            'dispensation_mode' =>$mode,
            'dispensation_type'=>$type,
        ];

        return $fillable;
    }

    public function confirmPayment($id, $detail_id, $virtual_account, $nominal_payment){

        $status = false;
        $is_part = false;
        $nominal = $nominal_payment;
        $status_payment = PaymentDispensationDetails::STATUS_PAID;
        $detail = PaymentDispensationDetails::where('id', $detail_id)->first();
        if($detail){
            $status_payment = PaymentDispensationDetails::STATUS_PARTIAL;
            if($detail->nominal == $nominal){
                $status_payment = PaymentDispensationDetails::STATUS_PAID;
            }else{
                if($detail->nominal == ($detail->amount_paid + $nominal)){
                    $is_part = true;
                    $nominal = ($detail->amount_paid + $nominal);
                    $status_payment = PaymentDispensationDetails::STATUS_PAID;
                }
            }

            $detail->amount_paid = $nominal;
            $detail->status = $status_payment;
            $detail->date = Carbon::now()->format('Y-m-d'); // atau Carbon::now()->toDateString();
            $detail->save();

            $dispensation = PaymentDispensations::where('id', $id)->first();
            if($dispensation){
                if($is_part){
                    $nominal = $nominal_payment;
                }
                $remaining_balance = $dispensation->remaining_balance - $nominal;
                $dispensation->remaining_balance = $remaining_balance;
                $dispensation->status_payment = PaymentDispensations::PAYMENT_STATUS_UNPAID;
                $is_paid = StudentBills::PAYMENT_METHOD_PARTIAL;
                if($remaining_balance <= 0){
                    $is_paid = StudentBills::PAYMENT_METHOD_PAID;
                    $dispensation->status_payment = PaymentDispensations::PAYMENT_STATUS_PAID;
                }
                $dispensation->save();

                $bill = StudentBills::where('ppdb_user_id', $dispensation->ppdb_user_id)->where('type', StudentBills::BILL_TYPE_DEVELOPMENT)->orderBy('id', 'desc')->first();
                if($bill){
                    $bill->payment_method = $is_paid;
                    $bill->save();

                    $status = true;
                }
            }
        }
        return $status;
    }

    public function confirmPaymentPartial($id, $virtual_account, $nominal){

        $status = false;
        $dispensation = PaymentDispensations::where('id', $id)->first();

        if($dispensation){
            $remaining_balance = $dispensation->remaining_balance - $nominal;
            $dispensation->remaining_balance = $remaining_balance;
            $dispensation->status_payment = PaymentDispensations::PAYMENT_STATUS_UNPAID;

            $is_paid = StudentBills::PAYMENT_METHOD_PARTIAL;
            if($remaining_balance <= 0){
                $is_paid = StudentBills::PAYMENT_METHOD_PAID;
                $dispensation->status_payment = PaymentDispensations::PAYMENT_STATUS_PAID;
            }
            $dispensation->save();

            $details = PaymentDispensationDetails::where('payment_dispensation_id', $id)
                        ->whereIn('status', [PaymentDispensationDetails::STATUS_UNPAID, PaymentDispensationDetails::STATUS_PARTIAL])
                        ->orderBy('installment_number', 'asc')
                        ->get();

            $remaining_nominal = $nominal;

            foreach($details as $detail){
                if($remaining_nominal <= 0){
                    break;
                }

                $detail_remaining_balance = $detail->nominal - $detail->amount_paid;

                if($remaining_nominal >= $detail_remaining_balance){
                    $detail->amount_paid = $detail->nominal;
                    $detail->status = PaymentDispensationDetails::STATUS_PAID;
                    $remaining_nominal -= $detail_remaining_balance;
                } else {
                    $detail->amount_paid = $detail->amount_paid + $remaining_nominal;
                    $detail->status = PaymentDispensationDetails::STATUS_PARTIAL;
                    $remaining_nominal = 0;
                }
                $detail->date = Carbon::now()->format('Y-m-d');
                $detail->save();
            }

            $bill = StudentBills::where('ppdb_user_id', $dispensation->ppdb_user_id)->where('type', StudentBills::BILL_TYPE_DEVELOPMENT)->orderBy('id', 'desc')->first();
            if($bill){
                $bill->payment_method = $is_paid;
                $bill->save();
            }

            $status = true;
        }

        return $status;
    }

    public function confirmPaymentFullSettlement($id, $virtual_account, $nominal){
        $status = false;
        $message = '';
        $dispensation = PaymentDispensations::where('id', $id)->first();
        if($dispensation){
            if($dispensation->remaining_balance == $nominal){
                if(count($dispensation->details) > 0){
                    foreach($dispensation->details as $detail){
                        if($detail->status == PaymentDispensations::PAYMENT_STATUS_PAID){
                            continue;
                        }
                        $detail->status = PaymentDispensations::PAYMENT_STATUS_PAID;
                        $detail->amount_paid = $detail->amount_paid + ($detail->nominal - $detail->amount_paid);
                        $detail->date = Carbon::now()->format('Y-m-d');
                        $detail->save();
                        $status = true;
                    }
                }
            }
        }

        $remaining_balance = $dispensation->remaining_balance - $nominal;
        $dispensation->remaining_balance = $remaining_balance;
        $dispensation->status_payment = PaymentDispensations::PAYMENT_STATUS_UNPAID;

        $is_paid = StudentBills::PAYMENT_METHOD_PARTIAL;
        if($remaining_balance <= 0){
            $is_paid = StudentBills::PAYMENT_METHOD_PAID;
            $dispensation->status_payment = PaymentDispensations::PAYMENT_STATUS_PAID;
        }
        $dispensation->save();

        $bill = StudentBills::where('ppdb_user_id', $dispensation->ppdb_user_id)->where('type', StudentBills::BILL_TYPE_DEVELOPMENT)->orderBy('id', 'desc')->first();
        if($bill){
            $bill->payment_method = $is_paid;
            $bill->save();

            $status = true;
        }

        return $status;
    }

    public function getDispensationReport($params){
        $ppdbUsers = PPDBUser::select(
                'ppdb_users.*',
                'payment_dispensations.id as dispensation_id',
                'payment_dispensations.dispensation_type',
                'payment_dispensations.dispensation_mode',
                'payment_dispensations.total_final_fee',
                'payment_dispensations.remaining_balance',
                'payment_dispensations.status_payment',
                'payment_dispensations.actual_cost',
                'payment_dispensations.created_at',
                'payment_dispensation_details.id as detail_id',
                'payment_dispensation_details.virtual_account',
                'payment_dispensation_details.nominal as detail_nominal',
                'payment_dispensation_details.amount_paid',
                'payment_dispensation_details.status as detail_status',
                'payment_dispensation_details.installment_number',
                'payment_dispensation_details.date as payment_date',
                'payment_dispensation_details.status as status'
            )
            ->join('payment_dispensations', 'ppdb_users.id', '=', 'payment_dispensations.ppdb_user_id')
            ->leftJoin('payment_dispensation_details', 'payment_dispensations.id', '=', 'payment_dispensation_details.payment_dispensation_id')
            ->where('payment_dispensations.status', PaymentDispensations::STATUS_ACTIVE)
            ->where('payment_dispensations.dispensation_mode', '!=', PaymentDispensations::MODE_REAL_PAYMENT)
            ->orderBy('payment_dispensations.created_at', 'ASC');

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
            $collections[$ppdbUser->dispensation_id]['name'] = $ppdbUser->name;
            $collections[$ppdbUser->dispensation_id]['register_number'] = $ppdbUser->register_number;
            $collections[$ppdbUser->dispensation_id]['unit'] = $ppdbUser->unit->name;
            $collections[$ppdbUser->dispensation_id]['dispensation_type'] = $this->getDispensationType($ppdbUser->dispensation_type);
            $collections[$ppdbUser->dispensation_id]['dispensation_mode'] = $this->getDispensationMode($ppdbUser->dispensation_mode);
            $collections[$ppdbUser->dispensation_id]['actual_cost'] = $ppdbUser->actual_cost;
            $collections[$ppdbUser->dispensation_id]['total_final_fee'] = $ppdbUser->total_final_fee;
            $collections[$ppdbUser->dispensation_id]['remaining_balance'] = $ppdbUser->remaining_balance;
            // $collections[$ppdbUser->dispensation_id]['status_payment'] = $this->getStatusBayar($ppdbUser->status_payment);
            $collections[$ppdbUser->dispensation_id]['created_at'] = Carbon::parse($ppdbUser->created_at)->format('d M Y H:i:s');
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['installment_number'] = ($ppdbUser->installment_number == 0) ? 'Pembayaran DP' : 'Cicilan Ke - '.$ppdbUser->installment_number ;
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['virtual_account'] = $ppdbUser->virtual_account;
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['date'] = (!empty($ppdbUser->payment_date)) ? Carbon::parse($ppdbUser->payment_date)->format('d M Y') : '-';
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['nominal'] = $ppdbUser->detail_nominal;
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['amount_paid'] = $ppdbUser->amount_paid;
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['status'] = $this->getStatusBayar($ppdbUser->status);
        }

        return $collections;
    }

    public function getDevelopmentPaymentReport($params){
        $ppdbUsers = PPDBUser::select(
                'ppdb_users.*',
                'payment_dispensations.id as dispensation_id',
                'payment_dispensations.dispensation_type',
                'payment_dispensations.dispensation_mode',
                'payment_dispensations.total_final_fee',
                'payment_dispensations.remaining_balance',
                'payment_dispensations.status_payment',
                'payment_dispensations.actual_cost',
                'payment_dispensations.created_at',
                'payment_dispensation_details.id as detail_id',
                'payment_dispensation_details.virtual_account',
                'payment_dispensation_details.nominal as detail_nominal',
                'payment_dispensation_details.amount_paid',
                'payment_dispensation_details.status as detail_status',
                'payment_dispensation_details.installment_number',
                'payment_dispensation_details.date as payment_date',
                'payment_dispensation_details.status as status'
            )
            ->join('payment_dispensations', 'ppdb_users.id', '=', 'payment_dispensations.ppdb_user_id')
            ->leftJoin('payment_dispensation_details', 'payment_dispensations.id', '=', 'payment_dispensation_details.payment_dispensation_id')
            ->where('payment_dispensations.status', PaymentDispensations::STATUS_ACTIVE)
            ->orderBy('payment_dispensations.created_at', 'ASC');

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
            $collections[$ppdbUser->dispensation_id]['name'] = $ppdbUser->name;
            $collections[$ppdbUser->dispensation_id]['register_number'] = $ppdbUser->register_number;
            $collections[$ppdbUser->dispensation_id]['unit'] = $ppdbUser->unit->name;
            $collections[$ppdbUser->dispensation_id]['dispensation_type'] = $this->getDispensationType($ppdbUser->dispensation_type);
            $collections[$ppdbUser->dispensation_id]['dispensation_mode'] = $this->getDispensationMode($ppdbUser->dispensation_mode);
            $collections[$ppdbUser->dispensation_id]['is_dispensation'] = $ppdbUser->dispensation_mode != PaymentDispensations::MODE_REAL_PAYMENT ? 'Menerima Dispensasi' : '-';
            $collections[$ppdbUser->dispensation_id]['actual_cost'] = $ppdbUser->actual_cost;
            $collections[$ppdbUser->dispensation_id]['total_final_fee'] = $ppdbUser->total_final_fee;
            $collections[$ppdbUser->dispensation_id]['remaining_balance'] = $ppdbUser->remaining_balance;
            // $collections[$ppdbUser->dispensation_id]['status_payment'] = $this->getStatusBayar($ppdbUser->status_payment);
            $collections[$ppdbUser->dispensation_id]['created_at'] = Carbon::parse($ppdbUser->created_at)->format('d M Y H:i:s');
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['installment_number'] = ($ppdbUser->installment_number == 0) ? 'Pembayaran DP' : 'Cicilan Ke - '.$ppdbUser->installment_number ;
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['virtual_account'] = $ppdbUser->virtual_account;
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['date'] = (!empty($ppdbUser->payment_date)) ? Carbon::parse($ppdbUser->payment_date)->format('d M Y') : '-';
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['nominal'] = $ppdbUser->detail_nominal;
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['amount_paid'] = $ppdbUser->amount_paid;
            $collections[$ppdbUser->dispensation_id]['detail'][$ppdbUser->detail_id]['status'] = $this->getStatusBayar($ppdbUser->status);
        }

        return $collections;
    }

    public function getStatusBayar($status) {
        switch ($status) {
            case PaymentDispensationDetails::STATUS_UNPAID:
                return 'Belum Dibayar';
            case PaymentDispensationDetails::STATUS_PAID:
                return 'Sudah Dibayar';
            case PaymentDispensationDetails::STATUS_PARTIAL:
                return 'Pembayaran Sebagian';
            default:
                return '';
        }
    }

    public function getDispensationMode($status) {
        switch ($status) {
            case PaymentDispensations::MODE_FULL_SETUP:
                return 'Full Setup';
            case PaymentDispensations::MODE_ONLY_DISCOUNT:
                return 'Hanya Potongan Nominal';
            case PaymentDispensations::MODE_REAL_PAYMENT:
                return 'Pembayaran Tanpa Dispensasi';
            default:
                return '';
        }
    }

    public function getDispensationType($status) {
        switch ($status) {
            case PaymentDispensations::DISPENSATION_TYPE_DEVELOPMENT:
                return 'Uang Pengenmbangan';
            default:
                return '';
        }
    }

    public function confirmPlanDate($params, $ppdb_id){
        $dispensation = $this->getByUserPpdb($ppdb_id);

        if($dispensation){
            foreach($params['dates'] as $key => $value){
                $detail = PaymentDispensationDetails::where('id', $key)->first();
                if($detail){
                    $detail->plan_date = $value;
                    $detail->save();
                }
            }
        }

        return true;
    }
}
