<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\http\Request;
use App\Models\AgeLimit;
use App\Models\Unit;

class AgeLimitRule implements Rule
{
    private $message = 'Mohon maaf usia Anak Anda masih dibawah batas usia yang ditetapkan.';
    private $unit_id;
    private $request;

    public function __construct($unit_id, Request $request)
    {
        $this->unit_id = $unit_id;
        $this->request = $request;
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
        $forced = null;
        $forced_date = null;
        $max_forced_date = null;
        $unit = Unit::ageLimitApplied()->where('id', $this->unit_id)->first();

        /*
        if ($unit->name === 'KB-SIDOARJO') {
            $forced = 'Batas Umur KB B';
        }
        */
        if (Str::contains($unit->name, 'SD')) {
            $forced_date = Carbon::parse("2022-07-01");
            $max_forced_date = Carbon::parse("2022-07-31");
        }

        $start_date = null;
        $end_date = null;

        if ($unit && $period = $unit->ongoingPeriods->first()) {
            $start_date = $period ? $period->start_date : null;
            $end_date = $period ? $period->end_date : null;

            if ($forced_date && $max_forced_date) {
                $start_date = $forced_date;
                $end_date = $max_forced_date;
            }
        }

        if ($start_date && $end_date && $unit && $ageLimit = AgeLimit::activeUsed(request()->class_option, $forced, $unit->level_of_education)->first()) {
            /*
            $forced_date = Carbon::parse('2021-07-01');
            $max_forced_date = Carbon::parse('2021-07-01')->addMonth()->endOfMonth(); // 2021-08-31
            if ($start_date->greaterThanOrEqualTo($forced_date) && $end_date->lessThanOrEqualTo($max_forced_date)) {
                $min = Carbon::parse('2021-07-01')->subMonths($ageLimit->months - 1)->startOfMonth();
                $max = Carbon::parse('2021-07-01')->subMonths($ageLimit->months - 2)->endOfMonth();
                $dob = Carbon::parse($value);

                if ($dob->greaterThanOrEqualTo($min) && $dob->lessThanOrequalTo($max)) { //pengecualian syarat untuk umur kurang 2 bulan dari yg ditentukan
                    $this->message = 'Usia anak Anda dibawah batas usia namun masih dapat mendaftar dengan melampirkan bukti potensi kecerdasan dan/atau bakat istimewa dan kesiapan psikis dari psikolog profesional. Informasi selengkapnya silakan menghubungi admin kami di nomor: '. \App\Helpers\Helper::phoneWithLeadingZero($unit->phone);
                    return false;
                } elseif ($dob->greaterThan($min)) { //jika umur lebih dari atau sama dengan batas dari Master Age Limit
                    return false;
                }
            }
            */
            $dob = Carbon::parse($value);
            $min_age_months = $dob->diffInMonths($start_date);
            $max_age_months = $dob->diffInMonths($end_date);
            $range_limit_months = range($ageLimit->months, $ageLimit->max_months);

            if (! in_array($min_age_months, $range_limit_months) && ! in_array($max_age_months, $range_limit_months)) {
                if ($min_age_months > $ageLimit->max_months) {
                    $this->message = 'Mohon maaf usia Anak Anda melebihi batas usia yang ditetapkan.';
                }
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
