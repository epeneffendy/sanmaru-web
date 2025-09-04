<?php

namespace App\Services;
use Propaganistas\LaravelPhone\PhoneNumber;

class PhoneNormalizerService
{
    public function normalize($phone_number)
    {
        $phone_number = preg_replace("/^62/", "0", $phone_number);
        $phone_number = PhoneNumber::make($phone_number, 'ID')->formatForMobileDialingInCountry('US');
        return preg_replace("/[^0-9]/", "", $phone_number);
    }
}
