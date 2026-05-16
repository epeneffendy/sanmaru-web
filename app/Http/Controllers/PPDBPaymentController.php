<?php

namespace App\Http\Controllers;

use App\Helpers\PriceHelper;
use App\Models\PaymentDispensations;
use App\Models\PPDBUser;
use App\Services\FinanceSystemConfigurationService;
use App\Services\PaymentDispensationsService;
use Illuminate\Http\Request;

class PPDBPaymentController extends Controller
{
    public function choisePayment(Request $request, PaymentDispensationsService $paymentDispensationsService, FinanceSystemConfigurationService $financeSystemConfigurationService)
    {
        $user = $request->session()->get('user');
        $dispensation = $paymentDispensationsService->getByUserPpdb($user['ppdb']['id']);
        $configuration = $financeSystemConfigurationService->findConfigurationActive();

        $ppdb = PPDBUser::where('id', $user['ppdb']['id'])->first();

        $dpOptions = [];
        $installmentOptions = [];
        if($configuration){
            $minDp = (int)$configuration->min_down_payment;
            $multiple = (int)$configuration->down_payment_multiple;
            $recommended = (int)$configuration->recommended_down_payment;
            $maxLimit = 80;
            $maxInstallment = (int)$configuration->max_absolute_installment;

            $dpOptions[$minDp] = ($minDp == $recommended)
                                ? "{$minDp}% (Rekomendasi)"
                                : "{$minDp}%";
            for ($i = $multiple; $i < $maxLimit; $i += $multiple) {
                if ($i == $minDp) continue;

                if ($i == $recommended) {
                    $dpOptions[$i] = "{$i}% (Rekomendasi)";
                } else {
                    $dpOptions[$i] = "{$i}%";
                }
            }

            for($i = 1; $i <= $maxInstallment; $i++){
                $installmentOptions[$i] = "{$i}x Bulan";
            }
        }

        if(!empty($dispensation)){
            $arr_value = json_decode($dispensation->value);
            $data =[
                'dispensation' => $dispensation,
                'ppdb' => $user['ppdb'],
                'dpOptions'=>$dpOptions,
                'installmentOptions'=> $installmentOptions,
                'configuration'=>$configuration,
                'va_full' => $arr_value->va_full_statement,
                'va_partial' =>$arr_value->va_partial,
            ];

            if(($dispensation->dispensation_mode == PaymentDispensations::MODE_FULL_SETUP) || ($dispensation->dispensation_mode == PaymentDispensations::MODE_REAL_PAYMENT)){

                if(($dispensation->dispensation_mode == PaymentDispensations::MODE_REAL_PAYMENT)){
                    if(count($dispensation->details) > 1){
                        return view('ppdb-billing.payment-bill-list', $data);
                    }else{
                        return view('ppdb-billing.payment-bill-full', $data);
                    }

                }else{
                    return view('ppdb-billing.payment-bill-list', $data);
                }
            }else{
                if(count($dispensation->details) > 1){
                    return view('ppdb-billing.payment-bill-list', $data);
                }else{
                    return view('ppdb-billing.payment-discount-only', $data);
                }

            }
        }else{
            $development = PriceHelper::development($ppdb, false);

            $data = [
                'ppdb' => $user['ppdb'],
                'dpOptions'=>$dpOptions,
                'installmentOptions'=> $installmentOptions,
                'configuration'=>$configuration,
                'total_bill'=>$development,
            ];
            return view('ppdb-billing.payment-options', $data);
        }



    }

    public function store(Request $request, PaymentDispensationsService $paymentDispensationService){
        try{
            $ppdb = PPDBUser::where('id', $request->ppdb_user_id)->first();
            $va_full_statement = $paymentDispensationService->virtualAccountNumber($ppdb, PaymentDispensations::TYPE_FULL);
            $va_partial = $paymentDispensationService->virtualAccountNumber($ppdb, PaymentDispensations::TYPE_PARTIAL);

            $dispensation = $paymentDispensationService->getByUserPpdb($request->ppdb_user_id);

            $input = [];
            $json_value = [];

            if(!empty($dispensation)){
                $dispensation_mode = PaymentDispensations::MODE_ONLY_DISCOUNT;
            }else{
                $dispensation_mode = PaymentDispensations::MODE_REAL_PAYMENT;

            }

            if($request->paymentType == 'cicilan'){
                $json_value['down_payment'] = (!empty($request->nominal_dp)) ? $request->nominal_dp : 0;
                $json_value['tenor'] = $request->tenor;
                $json_value['monthly'] = $request->cicilan_per_bulan;
            }

            $arr_nominal = [
                'total_fee'=>$request->total_bill,
                'actual_cost'=>$request->total_bi
            ];
            $payment_type = $request->paymentType;
            $input = $paymentDispensationService->fillable($ppdb, $request->total_bill, $request->total_bill, 'development', $dispensation_mode);

            $json_value['va_full_statement'] = $va_full_statement;
            $json_value['va_partial'] = $va_partial;
            $input['remaining_balance'] = $request->total_bill;
            $input['payment_type'] = $payment_type;

            $input['value'] = json_encode($json_value);

            $paymentDispensationService->create($input, $ppdb);
            //Redirect Route jika sudah berhasil simpan
            return redirect()->route('ppdb.bills.choise-payment')->with('message', 'Data berhasil disimpan!');
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function paymentBillList(Request $request, PaymentDispensationsService $paymentDispensationsService){

    }
}
