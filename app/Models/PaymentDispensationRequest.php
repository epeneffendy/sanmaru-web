<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDispensationRequest extends Model
{
    protected $table = 'payment_dispensation_request';

    protected $fillable = [
        'ppdb_user_id',
        'unit_id',
        'school_year',
        'dispensation_type',
        'description',
        'reason',
        'attachment',
        'status',
        'verified_date',
        'verified_user_id',
    ];

    const STATUS_WAITING = 'waiting'; //pengajuan awal
    const STATUS_APPROVED = 'approved'; //disetujui oleh admin
    const STATUS_CONFIRMED = 'confirmed'; //sudah di ajukan dispensasi oleh admin
    const STATUS_SUBMITTED = 'submitted'; // sudah di gunakan oleh siswa
    const STATUS_REJECTED = 'rejected'; //di tolak oleh admin

    public function ppdb()
    {
    	return $this->belongsTo(PPDBUser::class, 'ppdb_user_id', 'id');
    }
}
