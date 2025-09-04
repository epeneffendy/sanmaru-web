<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'teachers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nik',
        'name',
        'email',
        'mobile_phone',
        'address'
    ];

    public function user()
    {
        return $this->hasOne(__NAMESPACE__.'\User', 'id', 'user_id');
    }
}
