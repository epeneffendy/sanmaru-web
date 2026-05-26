<?php

namespace App\Services;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;
use App\Models\PaymentDispensations;
use App\Models\PaymentVirtualAccounts;


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

    public function findByUserPpdbUnpaid($ppdb_user_id)
    {
        return $this->paymentVirtualAccountsModel
            ->where('ppdb_user_id', $ppdb_user_id)
            ->where('status', \App\Models\PaymentVirtualAccounts::STATUS_UNPAID)
            ->first();
    }

    public function fillable($ppdb_user_id, $type, $virtual_account_number, $total_payment, $va_account)
    {
        return [
            'ppdb_user_id' => $ppdb_user_id,
            'type' => $type,
            'virtual_account_number' => $virtual_account_number,
            'total_payment' => $total_payment,
            'status' => \App\Models\PaymentVirtualAccounts::STATUS_UNPAID,
            'virtual_account_type' => $va_account,
            'payment_option'=> 'BCA',
            'expired_at' => now()->addDays(1),
        ];
    }

    public function confirmDevelopment($id, $nominal, $type)
    {
        DB::beginTransaction();
        try {
            $paymentVirtualAccount = $this->findById($id);
            if (!$paymentVirtualAccount) {
                DB::rollBack();
                return false;
            }

            $detail_id = 0;
            $virtual_account_number = $paymentVirtualAccount->virtual_account_number;
            $nominal = $paymentVirtualAccount->total_payment;

            $paymentVirtualAccount->status = PaymentVirtualAccounts::STATUS_PAID;
            $paymentVirtualAccount->payment_date = now();

            $confirmed = false;
            if ($paymentVirtualAccount->save()) {
                $dispensation = $this->paymentDispensationsService->getByUserPpdb($paymentVirtualAccount->ppdb_user_id);

                if ($dispensation) {

                    // 99 = full payment, 98 = partial payment, 22 = down payment, 23 = installment payment
                    if($type == 21){
                        $detail_id = $dispensation->details[0]->id;
                        $confirmed = $this->paymentDispensationsService->confirmPayment($dispensation->id, $detail_id, $virtual_account_number, $nominal);
                    }

                    if(($type == 98) || ($type == 99)){
                        $confirmed = $this->paymentDispensationsService->confirmPaymentPartial($dispensation->id, $virtual_account_number, $nominal);
                    }

                    if(($type == 22) || ($type == 23)){
                        $dispensation_detail = $this->paymentDispensationsService->getByUserPpdbWithVirtualAccount($paymentVirtualAccount->ppdb_user_id, $virtual_account_number);
                        $detail_id = $dispensation_detail ? $dispensation_detail->detail_id : 0;
                        $confirmed = $this->paymentDispensationsService->confirmPayment($dispensation->id, $detail_id, $virtual_account_number, $nominal);
                    }
                }

                if($confirmed){
                    DB::commit();
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
}
