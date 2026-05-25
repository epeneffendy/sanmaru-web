<?php

namespace App\Services;


class PaymentVirtualAccountsService
{
    protected $paymentVirtualAccountsModel;

    public function __construct(\App\Models\PaymentVirtualAccounts $paymentVirtualAccountsModel)
    {
        $this->paymentVirtualAccountsModel = $paymentVirtualAccountsModel;
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
}

