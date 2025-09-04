<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillUser extends Model
{
    use Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'bill_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','bill_id','bill_name','bill_due_date','bill_amount','bill_category_id','status','paid_date'
    ];
    protected $dates = ['deleted_at'];

    public function scopeWithUserAndCategory($query, int $userId, int $categoryId)
    {
        return $query->where('user_id',$userId)->where('bill_category_id', $categoryId);
    }
}
