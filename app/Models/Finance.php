<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    const TYPE_REGISTRASI = 'registrasi';
    const TYPE_PEMBINAAN = 'development';
    const TYPE_SERAGAM = 'uniform';
    const TYPE_SPP = 'tuition';
    const TYPE_KEGIATAN = 'activity';
    const TYPE_LAIN_LAIN = 'other';

    protected $fillable = ['code', 'name', 'nominal_default', 'unit_id', 'user_id', 'period_id', 'type', 'year', 'description', 'start_date', 'is_insider'];

    protected $appends = ['user_ids'];

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function period()
    {
        return $this->hasOne(Period::class, 'id', 'period_id');
    }

    public static function getTypes()
    {
        return [
            'registrasi', 'development', 'uniform', 'tuition', 'activity', 'other'
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function financeUser()
    {
        return $this->hasMany('App\Models\FinanceUser');
    }

    public function getUserIdsAttribute()
    {
        return $this->financeUser->pluck('user_id')->all();
    }
}
