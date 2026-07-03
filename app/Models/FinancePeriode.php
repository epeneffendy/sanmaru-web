<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancePeriode extends Model
{
    protected $table = 'finance_periode';

    protected $fillable = ['type', 'unit_id', 'start_date', 'end_date', 'status'];
}
