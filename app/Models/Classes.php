<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use DB;

class Classes extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unit_id',
        'name',
        'unit_class'
    ];

    public function unit()
    {
        return $this->hasOne(__NAMESPACE__.'\Unit', 'id', 'unit_id');
    }

    public function scopeWithUnit($query)
    {
        return $this->join('units', 'units.id', '=', 'classes.unit_id')
                ->addSelect('classes.*', DB::raw("CONCAT(classes.name, ' [', units.name, ']') AS name_class_unit"));
    }

    public function courses()
    {
        return $this->belongsToMany('App\Models\Course', 'course_classes');
    }

    public function classSchedules()
    {
        return $this->hasMany('App\Models\ClassSchedule', 'class_id');
    }
}
