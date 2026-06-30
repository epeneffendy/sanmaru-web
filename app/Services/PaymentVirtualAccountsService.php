<?php

namespace App\Services;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;
use App\Models\PaymentDispensations;
use App\Models\PaymentVirtualAccounts;
use App\Mail\BillPaymentConfirmed;
use App\Models\PaymentDispensationDetails;
use App\Services\EmailService;


class PaymentVirtualAccountsService
{
    protected $paymentVirtualAccountsModel;
    protected $paymentDispensationsService;

    public function __construct(\App\Models\PaymentVirtualAccounts $paymentVirtualAccountsModel, PaymentDispensationsService $paymentDispensationsService)
    {
        $this->paymentVirtualAccountsModel = $paymentVirtualAccountsModel;
        $this->paymentDispensationsService = $paymentDispensationsService;
    }

    public function create($data)
    {
        return $this->paymentVirtualAccountsModel->create($data);
    }

    public function findById($id)
    {
        return $this->paymentVirtualAccountsModel->where('id', $id)->first();
    }

    public function findByVirtualAccountUnpaid($virtual_account_number)
    {
        return $this->paymentVirtualAccountsModel
            ->where('virtual_account_number', $virtual_account_number)
            ->where('status', \App\Models\PaymentVirtualAccounts::STATUS_UNPAID)
            ->first();
    }

    public function findByUserPpdbUnpaid($ppdb_user_id, $type)
    {
        return $this->paymentVirtualAccountsModel
            ->where('ppdb_user_id', $ppdb_user_id)
            ->where('type', $type)
            ->where('status', \App\Models\PaymentVirtualAccounts::STATUS_UNPAID)
            ->first();
    }

    public function fillable($ppdb_user_id, $type, $virtual_account_number, $total_payment, $va_account, $expired_at)
    {
        return [
            'ppdb_user_id' => $ppdb_user_id,
            'type' => $type,
            'virtual_account_number' => $virtual_account_number,
            'total_payment' => $total_payment,
            'status' => \App\Models\PaymentVirtualAccounts::STATUS_UNPAID,
            'virtual_account_type' => $va_account,
            'payment_option'=> 'BCA',
            'expired_at' => $expired_at,
        ];
    }

    public function confirmDevelopment($id, $nominal, $type, $dispensation_type)
    {
        DB::beginTransaction();
        try {
            $paymentVirtualAccount = $this->findById($id);

            if (!$paymentVirtualAccount) {
                DB::rollBack();
                return false;
            }

            $register_number = isset($paymentVirtualAccount->ppdb) ? $paymentVirtualAccount->ppdb->register_number :'00';
            $detail_id = 0;
            $virtual_account_number = $paymentVirtualAccount->virtual_account_number;
            $nominal = $paymentVirtualAccount->total_payment;

            $paymentVirtualAccount->status = PaymentVirtualAccounts::STATUS_PAID;
            $paymentVirtualAccount->payment_date = now();
            $paymentVirtualAccount->invoice_number = $this->generateInvoice($paymentVirtualAccount->type, $paymentVirtualAccount->virtual_account_number, $paymentVirtualAccount->payment_dispensation_detail_id, $register_number );

            $confirmed = false;
            if ($paymentVirtualAccount->save()) {
                $dispensation = $this->paymentDispensationsService->getByUserPpdb($paymentVirtualAccount->ppdb_user_id, $dispensation_type);

                if ($dispensation) {
                    $char_virtual_account = strlen($virtual_account_number);
                    $is_full_payment = true;
                    $is_part = false;
                    if($char_virtual_account > 16){
                        $is_full_payment = false;
                        $payment_code = substr($virtual_account_number, -2);
                        // if($payment_code == PaymentDispensations::TYPE_FULL){
                        //     $is_full_payment = true;
                        // }
                    }

                    if($is_full_payment){
                        $detail_id = $dispensation->details[0]->id;
                        $confirmed = $this->paymentDispensationsService->confirmPayment($dispensation->id, $detail_id, $virtual_account_number, $nominal);
                    }else{
                        if($payment_code == PaymentDispensations::TYPE_PARTIAL){
                            $confirmed = $this->paymentDispensationsService->confirmPaymentPartial($dispensation->id, $virtual_account_number, $nominal);
                        }else if($payment_code == PaymentDispensations::TYPE_FULL){
                            $confirmed = $this->paymentDispensationsService->confirmPaymentFullSettlement($dispensation->id, $virtual_account_number, $nominal);
                        }else{
                            $dispensation_detail = $this->paymentDispensationsService->getByUserPpdbWithVirtualAccount($paymentVirtualAccount->ppdb_user_id, $virtual_account_number);
                            $detail_id = $dispensation_detail ? $dispensation_detail->detail_id : 0;
                            $confirmed = $this->paymentDispensationsService->confirmPayment($dispensation->id, $detail_id, $virtual_account_number, $nominal);
                        }

                    }
                }

                if($confirmed){
                    DB::commit();
                    $email = $paymentVirtualAccount->ppdb->user->email;
                    $template = (new BillPaymentConfirmed($dispensation, $paymentVirtualAccount));
                    (new EmailService())->sendMail($template, $email);
                    return true;
                }
            }

            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming development payment: ' . $e->getMessage());
            return false;
        }
    }

    public function generateInvoice($type, $virtual_account_number, $detail_id, $register_number){
        $yearMonth = date('ym');
        $paymentCode = '00';
        if ($type === 'development') {
            $paymentCode = '03';
        } elseif ($type === 'activity') {
            $paymentCode = '06';
        }

        $initialCode = '';
        $dispensation_detail = PaymentDispensationDetails::where('id', $detail_id)->first();
        if($dispensation_detail){
            $initialCode = sprintf("%02d", $dispensation_detail->installment_number);
        }

        $char_virtual_account = strlen($virtual_account_number);
        if($char_virtual_account == 16){
            $initialCode = 11;
        }

        $prefix = "{$yearMonth}{$paymentCode}{$register_number}{$initialCode}";

        $lastTransaction = PaymentVirtualAccounts::selectRaw('RIGHT(invoice_number, 4) as urutan')
        ->where('invoice_number', 'like', $yearMonth . '%')
        ->orderByRaw('RIGHT(invoice_number, 4) DESC')
        ->first();

        if (!$lastTransaction) {
            $urutan = 0;
        } else {
            $urutan = (int) $lastTransaction->urutan;
        }

        $urutan++;
        $sequenceCode = sprintf("%04d", $urutan);

        return "{$prefix}{$sequenceCode}";
    }
}
