<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class ProductFitting extends Model
{
    protected $fillable = [
        'date', 'hour_start', 'hour_end', 'unit_id', 'quota', 'note'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function scopeByUserRole($query)
    {
        $user = Auth::user();
        if (!$user->role_units || ($user->role_units && !count($user->role_units))) {
            return $query;
        }

        return $query->whereIn('unit_id', $user->role_units);
    }

    public function users()
    {
        return $this->hasMany(ProductUserFitting::class, 'fitting_id', 'id');
    }

    public function getIsNotAvailableAttribute()
    {
        $users = $this->users;

        return $users->count('id') >= $this->quota;
    }

    public function getActiveLabelAttribute()
    {
        $attributes = [
            'class' => 'danger',
            'label' => 'Inactive'
        ];

        if ($this->active)
            $attributes = [
                'class' => 'success',
                'label' => 'Active'
            ];

        return "<span class='label label-{$attributes['class']}'>{$attributes['label']}</span>";
    }
}
