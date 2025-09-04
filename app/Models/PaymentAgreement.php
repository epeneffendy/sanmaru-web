<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PaymentAgreement extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'payment_agreements';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'desc',
    ];
}
