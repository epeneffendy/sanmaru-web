<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use SoftDeletes;

    const TYPE_MOTHER = 'mother';
    const TYPE_FATHER = 'father';
    const TYPE_WALI = 'wali';

    /**
     * @var string
     */
    protected $table = 'parents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'place_of_birth',
        'date_of_birth',
        'address',
        'city',
        'region',
        'country',
        'religion',
        'phone',
        'job',
        'type',
        'card_identity',
        'children_id',
        'salary',
        'education'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotDeleted($query)
    {
    }
    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotConfirm($query)
    {
    }
}
