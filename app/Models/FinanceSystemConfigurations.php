<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceSystemConfigurations extends Model
{
    protected $fillable = ['min_down_payment', 'down_payment_multiple', 'recommended_down_payment', 'max_absolute_installment', 'effective_date'];

    protected $table = 'finance_system_configurations';


}
