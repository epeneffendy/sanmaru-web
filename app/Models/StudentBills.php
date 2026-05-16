<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentBills extends Model
{
    protected $table = 'student_bills';

    const PAYMENT_METHOD_UNPAID = 'unpaid';
    const PAYMENT_METHOD_PARTIAL = 'partial';
    const PAYMENT_METHOD_PAID = 'paid';
    const PAYMENT_METHOD_CANCELED = 'canceled';

    const PAYMENT_TERM_FULL = 'full_payment';
    const PAYMENT_TERM_INSTALLMENT = 'installment_payment';

    const BILL_TYPE_DEVELOPMENT = 'development';

    protected $fillable = [
        'ppdb_user_id',
        'finance_id',
        'type',
        'amount',
        'payment_method',
        'status',
        'due_date',
    ];
}
