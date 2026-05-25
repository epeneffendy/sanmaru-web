<?php

namespace App\Http\Controllers;

use App\Helpers\PriceHelper;
use App\Models\PaymentDispensations;
use App\Models\PPDBUser;
use App\Services\FinanceSystemConfigurationService;
use App\Services\PaymentDispensationsService;
use App\Services\PaymentVirtualAccountsService;
use App\Models\PaymentVirtualAccounts;
use Illuminate\Http\Request;

class PPDBPaymentController extends Controller
{
    public function choisePayment(Request $request, PaymentDispensationsService $paymentDispensationsService, FinanceSystemConfigurationService $financeSystemConfigurationService, PaymentVirtualAccountsService $paymentVirtualAccountsService)
    {
        $user = $request->session()->get('user');
        $dispensation = $paymentDispensationsService->getByUserPpdb($user['ppdb']['id']);
        $configuration = $financeSystemConfigurationService->findConfigurationActive();
        $virtual_account_unpaid = $paymentVirtualAccountsService->findByUserPpdbUnpaid($user['ppdb']['id']);

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

            for($i = 2; $i <= $maxInstallment; $i++){
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
                'virtual_account_unpaid' => $virtual_account_unpaid,
            ];

            if(($dispensation->dispensation_mode == PaymentDispensations::MODE_FULL_SETUP) || ($dispensation->dispensation_mode == PaymentDispensations::MODE_REAL_PAYMENT)){

                if(($dispensation->dispensation_mode == PaymentDispensations::MODE_REAL_PAYMENT)){
                    if(count($dispensation->details) > 1){
                        // return view('ppdb-billing.payment-bill-list', $data);
                        return view('ppdb-billing.payment-list-bill-new', $data);
                    }else{
                        return redirect()->route('ppdb.bills.payment-now', ['id' =>$dispensation->id, 'type' => PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT])->with('message', 'Data berhasil disimpan!');
                        // return view('ppdb-billing.payment-bill-full', $data);
                    }

                }else{
                    return view('ppdb-billing.payment-list-bill-new', $data);
                }
            }else{
                if(count($dispensation->details) > 1){
                    return view('ppdb-billing.payment-list-bill-new', $data);
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
                'actual_cost'=>$request->total_bill
            ];
            $payment_type = $request->paymentType;
            $input = $paymentDispensationService->fillable($ppdb, ($request->total_bill - $request->nominal_diskon_lunas), $request->total_bill, 'development', $dispensation_mode);

            $json_value['va_full_statement'] = $va_full_statement;
            $json_value['va_partial'] = $va_partial;
            $input['remaining_balance'] =($request->total_bill - $request->nominal_diskon_lunas);
            $input['payment_type'] = $payment_type;

            $input['value'] = json_encode($json_value);

            $paymentDispensationService->create($input, $ppdb);
            if($request->paymentType == 'lunas'){
                //redirect ke link payment-now
                $dispensation = $paymentDispensationService->getByUserPpdb($request->ppdb_user_id);
                return redirect()->route('ppdb.bills.payment-now', ['id' =>$dispensation->id, 'type' => PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT])->with('message', 'Data berhasil disimpan!');
            }
            //Redirect Route jika sudah berhasil simpan
            return redirect()->route('ppdb.bills.choise-payment')->with('message', 'Data berhasil disimpan!');
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function paymentNow(Request $request, PaymentDispensationsService $paymentDispensationsService, PaymentVirtualAccountsService $paymentVirtualAccountsService){

        $dispensation = $paymentDispensationsService->getDetailById($request->id);
        if(($request->type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT) || $request->type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_PARTIAL){
            $dispensation = $paymentDispensationsService->getById($request->id);
        }

        $virtual_account_type = $virtual_account_number = $remaining_balance = null;
        $is_create =false;
        if($dispensation){
            if($request->type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT){
                $virtual_account_type = PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT;
                $virtual_account_number = json_decode($dispensation->value)->va_full_statement;
                $remaining_balance = $dispensation->remaining_balance;
            }

            if($request->type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_INSTALLMENT){
                $virtual_account_type = PaymentVirtualAccounts::VIRTUAL_ACCOUNT_INSTALLMENT;
                $virtual_account_number = $dispensation->virtual_account;
                $remaining_balance = $dispensation->nominal;
            }


            if($request->type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_PARTIAL){
                $virtual_account_type = PaymentVirtualAccounts::VIRTUAL_ACCOUNT_PARTIAL;
                $virtual_account_number = json_decode($dispensation->value)->va_partial;
                $remaining_balance = $request->nominal;
            }

            $va_unpaid = $paymentVirtualAccountsService->findByVirtualAccountUnpaid($virtual_account_number);

            if ($va_unpaid) {
                if (\Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($va_unpaid->expired_at))) {
                    $is_create = true;
                    $va_unpaid->status = PaymentVirtualAccounts::STATUS_EXPIRED;
                    $va_unpaid->save();
                }

                if($va_unpaid->total_payment != $request->nominal){
                    $is_create = true;
                    $va_unpaid->status = PaymentVirtualAccounts::STATUS_CANCELED;
                    $va_unpaid->save();
                }
            }

            if($is_create || !$va_unpaid){
                $type_payment = PaymentVirtualAccounts::PAYMENT_TYPE_DEVELOPMENT;
                $fillable = $paymentVirtualAccountsService->fillable($dispensation->ppdb_user_id,$type_payment, $virtual_account_number, $remaining_balance, $virtual_account_type);
                $paymentVirtualAccountsService->create($fillable);
            }

            $va_unpaid = $paymentVirtualAccountsService->findByVirtualAccountUnpaid($virtual_account_number);

            $data =[
                'dispensation' => $dispensation,
                'virtual_account_number' => $virtual_account_number,
                'virtual_account_unpaid' => $va_unpaid,
            ];

            return view('ppdb-billing.payment-bill-full', $data);

        }else{
            return redirect()->route('ppdb.bills.choise-payment')->with('error', 'Data tidak ditemukan!');
        }

    }

    public function paymentCancel(Request $request, PaymentVirtualAccountsService $paymentVirtualAccountsService){
        $va_unpaid = $paymentVirtualAccountsService->findByVirtualAccountUnpaid($request->virtual_account_number);
        if($va_unpaid){
            if($va_unpaid->status == PaymentVirtualAccounts::STATUS_UNPAID){
                $va_unpaid->status = PaymentVirtualAccounts::STATUS_CANCELED;
                $va_unpaid->save();
            }
            return redirect()->route('ppdb.bills.choise-payment')->with('message', 'Pembayaran berhasil dibatalkan!');
        }else{
            return redirect()->route('ppdb.bills.choise-payment')->with('error', 'Data tidak ditemukan!');
        }
    }


}
