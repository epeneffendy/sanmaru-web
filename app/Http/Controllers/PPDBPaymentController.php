<?php

namespace App\Http\Controllers;

use App\Helpers\PriceHelper;
use App\Models\PaymentDispensationDetails;
use App\Models\PaymentDispensations;
use App\Models\PPDBUser;
use App\Services\FinanceSystemConfigurationService;
use App\Services\PaymentDispensationsService;
use App\Services\PaymentVirtualAccountsService;
use App\Models\PaymentVirtualAccounts;
use App\Models\StudentBills;
use App\Services\GeneralSettingService;
use Illuminate\Http\Request;

class PPDBPaymentController extends Controller
{
    public function choisePayment($type, Request $request, PaymentDispensationsService $paymentDispensationsService, FinanceSystemConfigurationService $financeSystemConfigurationService, PaymentVirtualAccountsService $paymentVirtualAccountsService, GeneralSettingService $generalSettingService)
    {

        $discount = 0;
        $user = $request->session()->get('user');
        $dispensation = $paymentDispensationsService->getByUserPpdb($user['ppdb']['id'], $type);
        $configuration = $financeSystemConfigurationService->findConfigurationActive();
        $virtual_account_unpaid = $paymentVirtualAccountsService->findByUserPpdbUnpaid($user['ppdb']['id'], $type);
        $development_discount = $generalSettingService->getBySlug('development-fee-discount');


        if($development_discount){
            if($type == PaymentDispensations::DISPENSATION_TYPE_DEVELOPMENT){
                $discount = $development_discount->value;
            }
        }

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
                'discount'=>$discount,
                'virtual_account_number' => isset($virtual_account_unpaid) ? $virtual_account_unpaid->virtual_account_number : '',
                'dispensation_type'=> $type,
                'type'=>$type
            ];

            if(($dispensation->dispensation_mode == PaymentDispensations::MODE_FULL_SETUP) || ($dispensation->dispensation_mode == PaymentDispensations::MODE_REAL_PAYMENT)){
                if(($dispensation->dispensation_mode == PaymentDispensations::MODE_REAL_PAYMENT)){

                    if(count($dispensation->details) > 1){
                        if($virtual_account_unpaid){
                            if($virtual_account_unpaid->status == PaymentVirtualAccounts::STATUS_UNPAID){
                                return view('ppdb-billing.payment-bill-full', $data);
                            }
                        }else{
                            return view('ppdb-billing.payment-list-bill-new', $data);
                        }
                    } else{
                        if($virtual_account_unpaid){
                            if($virtual_account_unpaid->status == PaymentVirtualAccounts::STATUS_UNPAID){
                                // if($dispensation->dispensation_mode == PaymentDispensations::MODE_REAL_PAYMENT){
                                    return view('ppdb-billing.payment-bill-full', $data);
                                    // return redirect()->route('ppdb.bills.check-payment-status', ['virtual_account_number' => $virtual_account_unpaid->virtual_account_number, 'dispensation_type' => $type])->with('message', 'Data berhasil disimpan!');
                                // }
                            }
                        }else{
                            return view('ppdb-billing.payment-list-bill-new', $data);
                        }
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
            if($type == PaymentDispensations::DISPENSATION_TYPE_DEVELOPMENT){
                $total_bill = PriceHelper::development($ppdb, false);
            }else{
                $total_bill = PriceHelper::activity($ppdb, false);
            }

            $data = [
                'ppdb' => $user['ppdb'],
                'dpOptions'=>$dpOptions,
                'installmentOptions'=> $installmentOptions,
                'configuration'=>$configuration,
                'total_bill'=>$total_bill,
                'discount'=>$discount,
                'type'=>$type
            ];
            return view('ppdb-billing.payment-options', $data);
        }
    }

    public function store(Request $request, PaymentDispensationsService $paymentDispensationService){

        try{
            $paymentType = $request->paymentType;
            $type = $request->type;
            $code_payment = PaymentDispensations::CODE_PAYMENT_DEVELOPMENT;
            if($type == PaymentDispensations::DISPENSATION_TYPE_ACTIVITY){
                $code_payment = PaymentDispensations::CODE_PAYMENT_ACTIVITY;
            }

            $ppdb = PPDBUser::where('id', $request->ppdb_user_id)->first();
            $va_full_statement = $paymentDispensationService->virtualAccountNumber($ppdb,$code_payment, PaymentDispensations::TYPE_FULL);
            $va_partial = $paymentDispensationService->virtualAccountNumber($ppdb,$code_payment, PaymentDispensations::TYPE_PARTIAL);
            $dispensation = $paymentDispensationService->getByUserPpdb($request->ppdb_user_id, $type);


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
            $input = $paymentDispensationService->fillable($ppdb, ($request->total_bill - $request->nominal_diskon_lunas), $request->total_bill, $type, $dispensation_mode);

            $json_value['va_full_statement'] = $va_full_statement;
            $json_value['va_partial'] = $va_partial;
            $input['remaining_balance'] =($request->total_bill - $request->nominal_diskon_lunas);
            $input['payment_type'] = $payment_type;

            $input['value'] = json_encode($json_value);

            $paymentDispensationService->create($input, $ppdb, $type);

            if($request->paymentType == 'lunas'){
                //redirect ke link payment-now
                $dispensation = $paymentDispensationService->getByUserPpdb($request->ppdb_user_id, $type);

                return redirect()->route('ppdb.bills.payment-now', ['id' =>$dispensation->id, 'type' => PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT, 'payment_type'=>$paymentType,'dispensation_type'=>$type])->with('message', 'Data berhasil disimpan!');
            }
            //Redirect Route jika sudah berhasil simpan
            return redirect()->route('ppdb.bills.choise-payment',['type'=>$type])->with('message', 'Data berhasil disimpan!');
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function paymentNow(Request $request, PaymentDispensationsService $paymentDispensationsService, PaymentVirtualAccountsService $paymentVirtualAccountsService){

        $dispensation_type = $request->dispensation_type;

        $dispensation = $paymentDispensationsService->getDetailById($request->id);
        if(($request->type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT) || $request->type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_PARTIAL){
            $dispensation = $paymentDispensationsService->getById($request->id);
        }

        $virtual_account_type = $virtual_account_number = $remaining_balance = $installment_number =$installment_type = null;
        $is_create =false;
        if($dispensation){

            if($request->type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT){
                $virtual_account_type = PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT;
                $virtual_account_number = json_decode($dispensation->value)->va_full_statement;
                $remaining_balance = $dispensation->remaining_balance;
                if(isset($request->payment_type)){
                    if($request->payment_type == 'lunas'){
                        $virtual_account_number = $dispensation->details[0]->virtual_account;
                    }
                }
            }

            if($request->type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_INSTALLMENT){
                $virtual_account_type = PaymentVirtualAccounts::VIRTUAL_ACCOUNT_INSTALLMENT;
                $virtual_account_number = $dispensation->virtual_account;
                $remaining_balance = $dispensation->nominal - $dispensation->amount_paid;
                $installment_number = $dispensation->installment_number;
                $installment_type = ($installment_number == 0) ? 'down_payment' : 'installment';
            }


            if($request->type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_PARTIAL){
                $virtual_account_type = PaymentVirtualAccounts::VIRTUAL_ACCOUNT_PARTIAL;
                $virtual_account_number = json_decode($dispensation->value)->va_partial;
                $remaining_balance = $request->nominal;
            }

            if(!$request->has('refresh')){
                $va_unpaid = $paymentVirtualAccountsService->findByVirtualAccountUnpaid($virtual_account_number);

                if ($va_unpaid) {
                    if (\Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($va_unpaid->expired_at))) {
                        $is_create = true;
                        $va_unpaid->status = PaymentVirtualAccounts::STATUS_EXPIRED;
                        $va_unpaid->save();
                    }

                    if($va_unpaid->total_payment != $remaining_balance){
                        $is_create = true;
                        $va_unpaid->status = PaymentVirtualAccounts::STATUS_CANCELED;
                        $va_unpaid->save();
                    }
                }

                if($is_create || !$va_unpaid){
                    $type_payment = $dispensation_type;

                    $expired_at = now()->addDays(1);
                    if($installment_type == 'down_payment'){
                        $expired_at = now()->addDays(7);
                    }
                    $fillable = $paymentVirtualAccountsService->fillable($dispensation->ppdb_user_id,$type_payment, $virtual_account_number, $remaining_balance, $virtual_account_type, $expired_at);
                    $paymentVirtualAccountsService->create($fillable);
                }
            }

            $va_unpaid = $paymentVirtualAccountsService->findByVirtualAccountUnpaid($virtual_account_number);

            $data =[
                'dispensation' => $dispensation,
                'virtual_account_number' => $virtual_account_number,
                'virtual_account_unpaid' => $va_unpaid,
                'dispensation_type'=> $dispensation_type

            ];

            return view('ppdb-billing.payment-bill-full', $data);

        }else{
            return redirect()->route('ppdb.bills.choise-payment',['type'=>$dispensation_type])->with('error', 'Data tidak ditemukan!');
        }

    }

    public function paymentCancel(Request $request, PaymentVirtualAccountsService $paymentVirtualAccountsService){
        $va_unpaid = $paymentVirtualAccountsService->findByVirtualAccountUnpaid($request->virtual_account_number);
        $dispensation_type = $request->dispensation_type;
        if($va_unpaid){
            if($va_unpaid->status == PaymentVirtualAccounts::STATUS_UNPAID){
                    if($va_unpaid->virtual_account_type == PaymentVirtualAccounts::VIRTUAL_ACCOUNT_FULL_STATEMENT){
                    //cek disepensation jika mode real payment maka cancel juga dispensasi
                    $dispensation = PaymentDispensations::where([
                        'ppdb_user_id' => $va_unpaid->ppdb_user_id,
                        'dispensation_type'=>$dispensation_type,
                        'status' => PaymentDispensations::STATUS_ACTIVE,
                    ])->first();

                    if($dispensation){
                        if(count($dispensation->details) == 1){
                            if($dispensation->dispensation_mode == PaymentDispensations::MODE_REAL_PAYMENT){
                                $dispensation->status = PaymentDispensations::STATUS_CANCELLED;
                                $dispensation->save();
                            }
                        }
                    }
                }

                if($va_unpaid->status == PaymentVirtualAccounts::STATUS_UNPAID){
                    $va_unpaid->status = PaymentVirtualAccounts::STATUS_CANCELED;
                    $va_unpaid->save();
                }
                return redirect()->route('ppdb.bills.choise-payment', ['type'=>$dispensation_type])->with('message', 'Pembayaran berhasil dibatalkan!');
            }else{
                return redirect()->route('ppdb.bills.choise-payment', ['type'=>$dispensation_type])->with('message', 'Pembayaran sudah lunas!');
            }

        }else{
            return redirect()->route('ppdb.bills.choise-payment', ['type'=>$dispensation_type])->with('error', 'Data tidak ditemukan!');
        }
    }

    public function paymentPaidReceipt(){

        return view('ppdb-billing.payment-development-receipt');
    }

    public function developmentPaymentReceipt(Request $request, PaymentDispensationsService $paymentDispensationsService)
    {
        $id = $request->id;
        $user = $request->session()->get('user');
        $bill = StudentBills::where('id', $request->id)->first();
        $dispensation = $paymentDispensationsService->getByUserPpdb($user['ppdb']['id'], $bill->type);

        if (!$dispensation || $dispensation->ppdb_user_id !== $user['ppdb']['id']) {
            return redirect()->route('ppdb.bills.choise-payment')->with('error', 'Data tagihan tidak ditemukan.');
        }

        if ($dispensation->remaining_balance > 0) {
            return redirect()->route('ppdb.bills.choise-payment')->with('error', 'Tagihan ini belum lunas.');
        }

        $ppdb = PPDBUser::find($user['ppdb']['id']);
        $title = 'Uang Pengembangan';
        if($dispensation->dispensation_type == 'activity'){
            $title = 'Uang Kegiatan';
        }

        $data = [
            'dispensation' => $dispensation,
            'ppdb' => $ppdb,
            'title'=> $title,
            'nav' => ['parent' => 'finance', 'child' => 'Bukti Pembayaran']
        ];

        return view('ppdb-billing.payment-development-receipt', $data);
    }

    public function paymentPlanDate(Request $request, PaymentDispensationsService $paymentDispensationsService){
        $user = $request->session()->get('user');
        $confirm = $paymentDispensationsService->confirmPlanDate($request->all(), $user['ppdb']['id']);
        if($confirm){
            return redirect()->back()->with('message', 'Tanggal rencana pembayaran berhasil disimpan!');
        }

        return redirect()->back()->with('error', 'Gagal menyimpan tanggal rencana pembayaran!');
    }

    public function changePaymentMethod(Request $request){
        $dispensation = PaymentDispensations::where('id', $request->id)->first();
        $type = $request->dispensation_type;
        if($dispensation){
            if($type == 'development'){
                $ppdbUser = PPDBUser::find($dispensation->ppdb_user_id);
                if($ppdbUser){
                    $ppdbUser->development_fee_option = null;
                    $ppdbUser->development_statement = null;

                    $ppdbUser->save();
                    $ppdbUser->refresh();
                }
            }

            PaymentDispensationDetails::where('payment_dispensation_id', $dispensation->id)->delete();
            $dispensation->delete();
            return redirect()->route('ppdb.bills.choise-payment', ['type' => $request->dispensation_type])->with('message', 'Cara Bayar Berhasil Batalkan!');

        }
        return redirect()->route('ppdb.bills.choise-payment', ['type' => $request->dispensation_type])->with('message', 'Data Tidak Ditemukan!');
    }

    public function checkPaymentStatus(Request $request, \App\Services\OpenApi\v1\PaymentBCAService $paymentBCAService, PaymentVirtualAccountsService $paymentVirtualAccountsService)
    {

        $virtual_account_number = $request->virtual_account_number;
        $dispensation_type = $request->dispensation_type;
        $va_unpaid = $paymentVirtualAccountsService->findByVirtualAccountUnpaid($virtual_account_number);

        if (!$va_unpaid) {
            return redirect()->route('ppdb.bills.choise-payment', ['type' => $dispensation_type])->with('message', 'Cek status berhasil, pembayaran sudah terbayar dan terkonfirmasi.');
            // return redirect()->back()->with('error', 'Tagihan tidak ditemukan atau sudah kadaluarsa.');
        }

        if (!\App\Helpers\Helper::isVaBcaEnable()) {
            return redirect()->back()->with('message', 'Pembayaran menggunakan Virtual Account BCA sedang dinonaktifkan.');
        }

        try {
            if($va_unpaid->status == PaymentVirtualAccounts::STATUS_UNPAID){
                return redirect()->back()->with('message', 'Cek status berhasil, belum ada pembayaran yang diterima.');
            }else{
                return redirect()->route('ppdb.bills.choise-payment', ['type' => $dispensation_type])->with('message', 'Cek status berhasil, pembayaran sudah terbayar dan terkonfirmasi.');
                // return redirect()->back()->with('message', 'Cek status berhasil, belum ada pembayaran yang diterima.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengecek status: ' . $e->getMessage());
        }
    }

}
