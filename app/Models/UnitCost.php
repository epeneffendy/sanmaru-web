<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitCost extends Model
{
    protected $fillable = [
        'unit_id',
        'title',
        'description'
    ];
}
