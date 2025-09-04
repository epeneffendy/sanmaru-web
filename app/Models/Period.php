<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

class Period extends Model
{
    protected $dates = [
        'start_date', 'end_date'
    ];

    protected $fillable = [
        'name', 'description', 'unit_id', 'quota', 'start_register_number',
        'class_id', 'start_date', 'end_date', 'active', 'origin_school_options', 'school_year',
        'show_registration_popup', 'popup_content',
    ];

    protected $casts = [
        'origin_school_options' => 'array',
    ];

    public function scopeByUserRole($query)
    {
        $user = Auth::user();
        if (!$user->role_units || ($user->role_units && !count($user->role_units))) {
            return $query;
        }

        return $query->whereIn('unit_id', $user->role_units);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function ppdbUsers()
    {
        return $this->hasMany(PPDBUser::class, 'periode', 'id');
    }

    public function totalUsersPaymentNotYetSubmitted()
    {
        $collections = $this->ppdbUsers;

        if ($collections) {
            return $collections->filter(function($value) {
                return $value->status === 'complete' && $value->payment_form == '';
            })->count('id');
        }

        return 0;
    }

    public function totalUsersPaymentNotYetVerified()
    {
        $collections = $this->ppdbUsers;

        if ($collections) {
            return $collections->filter(function($value) {
                return $value->status === 'complete' && $value->payment_form != '';
            })->count('id');
        }

        return 0;
    }

    public function totalUsersEmailNotVerified()
    {
        $collections = $this->ppdbUsers;

        if ($collections) {
            return $collections->filter(function($value) {
                return $value->status === 'incomplete';
            })->count('id');
        }

        return 0;
    }

    public function setStartDateAttribute($value)
    {
        return $this->attributes['start_date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function setEndDateAttribute($value)
    {
        return $this->attributes['end_date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function getPeriodAttribute()
    {
        return Carbon::parse($this->start_date)->format('d/m/Y')
            . ' - ' .
            Carbon::parse($this->end_date)->format('d/m/Y');
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

    public function getShortDescAttribute()
    {
        $text = strip_tags($this->description);
        $short = Str::limit($text, 50);

        return $short;
    }

    public function getIsFeederSchoolAttribute()
    {
        return $this->origin_school_options && count($this->origin_school_options);
    }

    public function getSchoolYearPeriodAttribute()
    {
        if (! $this->school_year) {
            return null;
        }

        $nextYear = $this->school_year + 1;

        return "{$this->school_year}/{$nextYear}";
    }
}
