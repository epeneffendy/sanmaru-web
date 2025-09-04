<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ProductUserFitting;
use App\Models\ProductFitting;

class ProductFittingAvailableRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $fitting = ProductFitting::where('id', $value)->first();
        
        return $fitting && $fitting->quota > ProductUserFitting::select('id')->where('fitting_id', $value)->count('id');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'fitting tidak tersedia.';
    }
}
