<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class MultipleDateFormatRule implements Rule
{
    private $formats;

    public function __construct($format)
    {
        $this->formats = explode(',', $format);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            foreach ($this->formats as $format) {
                $date = Carbon::instance(Date::excelToDateTimeObject($value))->format($format);
            }
            return true;
        } catch (\ErrorException $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'data :attribute tidak sesuai format ['. implode(', ', $this->formats) .']';
    }
}
