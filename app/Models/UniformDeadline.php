<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniformDeadline extends Model
{
    //
    protected $table = 'uniform_deadline';
    protected $fillable = [
        'unit_id',
        'school_year',
        'uniform_payment_deadline',
        'status'
    ];

    public function unit()
    {
        return $this->hasOne(__NAMESPACE__ . '\Unit', 'id', 'unit_id');
    }
}
