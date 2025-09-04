<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'class_id'
    ];

    public function class()
    {
        return $this->hasOne(__NAMESPACE__.'\Classes', 'id', 'class_id');
    }
}
