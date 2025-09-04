<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;
use Auth;

class Stage extends Model
{
    use Compoships;

    protected $fillable = [
        'name',
        'unit_id',
        'information',
        'periode',
        'is_opening_shop_feature',
        'is_opening_development_feature',
        'active'
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
        return $this->belongsTo(Unit::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'periode', 'id');
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
