<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FinanceUser extends Pivot 
{
    protected $table = 'finance_user';
}