<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'vendors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'city',
        'mobile_phone',
        'pic',
        'nota_number',
        'nota_date'
    ];

    public function user()
    {
        return $this->hasOne(__NAMESPACE__.'\User', 'id', 'user_id');
    }
}
