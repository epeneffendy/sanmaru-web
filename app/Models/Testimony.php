<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimony extends Model
{
    protected $table = 'testimonies';

    protected $fillable = [
        'unit_id',
        'subject',
        'job',
        'content',
        'photo_path'
    ];

    public function unit()
    {
        return $this->belongsTo(__NAMESPACE__. '\Unit');
    }

}
