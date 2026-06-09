<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDispensations extends Model
{
    protected $table = 'payment_dispensations';

    protected $fillable = [
        'ppdb_user_id',
        'unit_id',
        'school_year',
        'dispensation_type',
        'total_final_fee',
        'actual_cost',
        'dispensation_mode',
        'remaining_balance',
        'value'
    ];

    const MODE_FULL_SETUP = 'full_setup';     // Untuk Total Biaya Baru, DP, dan Cicilan
    const MODE_ONLY_DISCOUNT = 'only_discount';  // Hanya potongan harga saja
    const MODE_REAL_PAYMENT = 'real_payment';  // Tanpa dispensasi pembayaran

    const PAYMENT_TYPE_LUNAS = 'full';
    const PAYMENT_TYPE_CICILAN = 'installment';

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_STATUS_UNPAID = 'unpaid';
    const PAYMENT_STATUS_PAID = 'paid';

    const DISPENSATION_TYPE_DEVELOPMENT = 'development';

    const TYPE_PENGEMBANGAN_LUNAS   = 21;
    const TYPE_PENGEMBANGAN_DP      = 22;
    const TYPE_PENGEMBANGAN_CICILAN = 23;

    const CODE_PAYMENT_DEVELOPMENT = '03';
    const CODE_PAYMENT_ACTIVITY = '06';

    const TYPE_PARTIAL = 98;
    const TYPE_FULL = 99;

    public static function getModeLabels()
    {
        return [
            self::MODE_FULL_SETUP => 'Full Setup (Biaya, DP & Cicilan)',
            self::MODE_ONLY_DISCOUNT => 'Hanya Potongan',
        ];
    }

    public function ppdb()
    {
    	return $this->belongsTo(PPDBUser::class, 'ppdb_user_id', 'id');
    }

    public function details()
    {
    	return $this->hasMany(PaymentDispensationDetails::class, 'payment_dispensation_id', 'id');
    }
}
