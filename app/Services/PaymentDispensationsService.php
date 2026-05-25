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

    public function getByUserPpdb($ppdb_user_id){
        $data = PaymentDispensations::where('ppdb_user_id', $ppdb_user_id)->where('status', PaymentDispensations::STATUS_ACTIVE)->orderBy('id', 'desc')->first();

        return $data;
    }

    public function getById($id){
        $data = PaymentDispensations::where('id', $id)->where('status', PaymentDispensations::STATUS_ACTIVE)->orderBy('id', 'desc')->first();
        return $data;
    }

    public function getDetailById($id){
        $data = PaymentDispensations::select('payment_dispensations.*', 'payment_dispensation_details.virtual_account', 'payment_dispensation_details.nominal', 'payment_dispensation_details.id as detail_id')
            ->join('payment_dispensation_details', 'payment_dispensations.id', '=', 'payment_dispensation_details.payment_dispensation_id')
            ->where('payment_dispensation_details.id', $id)
            ->where('payment_dispensations.status', PaymentDispensations::STATUS_ACTIVE)
            ->first();

        return $data;
    }

    public function create($params, $ppdb, $operator = 'user'){
        return \Illuminate\Support\Facades\DB::transaction(function () use ($params, $ppdb, $operator) {

            $paymentTerm = StudentBills::PAYMENT_TERM_FULL;
            if($params['dispensation_mode'] == PaymentDispensations::MODE_ONLY_DISCOUNT){
                if($operator == 'admin'){
                    $paymentDispensation = PaymentDispensations::create($params);
                }else{
                    $paymentDispensation = $this->getByUserPpdb($params['ppdb_user_id']);
                }
            }else{
                $paymentDispensation = PaymentDispensations::create($params);
            }

            if(($params['dispensation_mode'] == PaymentDispensations::MODE_FULL_SETUP)){
                $paymentTerm = StudentBills::PAYMENT_TERM_INSTALLMENT;
                $this->calculate($paymentDispensation->id, $params['total_final_fee'], $params['down_payment'], $params['remaining_balance'], $params['tenor'], $params['dispensation_mode'], $ppdb);
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
                    $this->calculate($paymentDispensation->id, $params['total_final_fee'], $dp, $params['remaining_balance'], $tenor, $mode, $ppdb);
                }

            }

            //update billing siswa
            $bills = StudentBills::where('ppdb_user_id', $ppdb->id)->where('type', StudentBills::BILL_TYPE_DEVELOPMENT)->orderBy('id', 'desc')->first();

            if($bills){
               $bills->payment_term = $paymentTerm;
               $bills->save();
            }
            return $paymentDispensation;
        });
    }

    public function calculate($paymentDispensationId, $total_final_fee, $down_payment, $remaining_balance, $tenor, $type, $ppdb){
        $arr_calculate = [];
        $nominal = $total_final_fee - $down_payment;
        $arr_calculate = $this->calculateInstallments($nominal, $down_payment, $tenor);

        $arr_dispensation = [];
        $const_type = 0;
        $is_installment = false;
        if(count($arr_calculate) > 1){
            $is_installment = true;
        }

        foreach($arr_calculate as $key => $value){
            $virtual_account_number = $this->virtualAccountNumber($ppdb, PaymentDispensations::TYPE_PENGEMBANGAN_LUNAS);
            if($is_installment){
                $const_type = PaymentDispensations::TYPE_PENGEMBANGAN_CICILAN;
                if($key == 0){
                    $const_type = PaymentDispensations::TYPE_PENGEMBANGAN_DP;
                }
                $virtual_account_number = $this->virtualAccountNumber($ppdb, $const_type, $key);
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

    public static function virtualAccountNumber($model, $const_type, $installment = 0)
    {

        $unit = null;
        $registrationNumber = null;
        $unitCode = null;
        $typePayment = null;
        $kodeBiller = null;
        $installmentCount= 0;
        $year = null;
        $paymentOption = 'BCA';

        if ($model instanceof PPDBUser) {
            $unit = $model->unit;
            $registrationNumber = $model->register_number;
            $typePayment = $const_type; //ppdb
            $year = substr($model->school_year, -2);

        }

        if (! $unit || ! $registrationNumber) {
            return null;
        }

        $unitCode = sprintf("%02d", $unit->id);
        $installmentCount = sprintf("%02d", $installment);
        $paymentInfo = PriceHelper::paymentInfo($unit, $paymentOption);
        $kodeBiller = $paymentInfo['kode_biller'];


        if (! $typePayment || ! $kodeBiller) {
            return null;
        }
        return "{$kodeBiller}{$unitCode}{$typePayment}{$registrationNumber}{$installmentCount}{$year}";
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

    public function confirmPayment($id, $detail_id, $virtual_account, $type, $nominal){

        $status = false;
        $detail = PaymentDispensationDetails::where('id', $detail_id)->first();
        if($detail){
            $status_payment = PaymentDispensationDetails::STATUS_PARTIAL;
            if($detail->nominal == $nominal){
                $status_payment = PaymentDispensationDetails::STATUS_PAID;
            }

            $detail->amount_paid = $nominal;
            $detail->status = $status_payment;
            $detail->date = Carbon::now()->format('Y-m-d'); // atau Carbon::now()->toDateString();
            $detail->save();

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

    public function confirmPaymentPartial($id, $virtual_account, $type, $nominal){

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
}
