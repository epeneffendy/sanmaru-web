<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionOrders extends Model
{
    protected $table = 'distribution_orders';

    const STATUS_ACTIVE = 'active'; // baru saja yayasan create data, belum di kirim
    const STATUS_SEND = 'send'; // yayasan telah memvalidasi dan dikirim ke unit
    const STATUS_CONFIRMED = 'confirmed'; // unit telah menerima dan konfirmasi penerimaan
    const STATUS_REJECTED = 'rejected'; // yayasan membatalkan distribusi

    protected $fillable = [
        'unit_id',
        'date',
        'date_range',
        'type_student',
        'description',
        'status',
        'created_by',
    ];

    public function unit()
    {
        return $this->hasOne(__NAMESPACE__ . '\Unit', 'id', 'unit_id');
    }
}
