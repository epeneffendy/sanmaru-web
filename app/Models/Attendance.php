<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id','date','type_code','reason'
    ];

    protected $dates = ['deleted_at'];

    const TYPE_ENUM_MAP = array(
        0 => 'absent',
        1 => 'present',
        2 => 'onleave'
    );

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function setTypeAttribute($value)
    {
        $this->type_code = array_flip($this::TYPE_ENUM_MAP)[$value];
    }

    public function getTypeAttribute()
    {
        return $this::TYPE_ENUM_MAP[$this->type_code];
    }
}
