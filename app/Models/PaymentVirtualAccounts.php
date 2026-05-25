<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentVirtualAccounts extends Model
{
    protected $table = 'payment_virtual_accounts';

    const STATUS_UNPAID = 'unpaid';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELED = 'canceled';
    const STATUS_EXPIRED = 'expired';

    const PAYMENT_TYPE_DEVELOPMENT = 'development';

    const VIRTUAL_ACCOUNT_FULL_STATEMENT = 'full_statement';
    const VIRTUAL_ACCOUNT_PARTIAL = 'partial';
    const VIRTUAL_ACCOUNT_INSTALLMENT = 'installment';


    protected $fillable = [
        'ppdb_user_id',
        'type',
        'virtual_account_number',
        'virtual_account_type',
        'total_payment',
        'payment_date',
        'status',
        'payment_option',
        'payment_inquiry_id',
        'expired_at',
        'callback_raw'
    ];
}
