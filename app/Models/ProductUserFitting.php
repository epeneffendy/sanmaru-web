<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductUserFitting extends Model
{
    protected $fillable = [
        'user_id', 'fitting_id'
    ];
}
