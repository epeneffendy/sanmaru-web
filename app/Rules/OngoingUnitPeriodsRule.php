<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Unit;

class OngoingUnitPeriodsRule implements Rule
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
        return Unit::where('id', $value)
            ->has('ongoingPeriods')
            ->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Unit tidak tersedia / period unit belum dibuka.';
    }
}
