<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillCategory extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'bill_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    protected $dates = ['deleted_at'];
}
