<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUnit extends Model
{
    use SoftDeletes;
    protected $table = 'product_units';

    protected $fillable = [
        'product_id', 'unit_id',
    ];

    public function scopeByUserRole($query)
    {
        $user = Auth::user();
        if (!$user->role_units || ($user->role_units && !count($user->role_units))) {
            return $query;
        }

        return $query->whereIn('unit_id', $user->role_units);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
