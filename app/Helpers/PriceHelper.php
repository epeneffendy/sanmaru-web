<?php
namespace App\Helpers;

use App\Models\PPDBUser;
use App\Models\Finance;
use App\Models\GeneralSettings;
use App\Models\Student;
use App\Models\Period;
use App\Models\User;
use App\Models\Unit;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class PriceHelper
{
    public static function getNameFinance($model, string $type = '')
    {
        $data = self::$type($model, 0, 0, 1);
        if ($data) {
            return @$data['name'];
        }

        return null;
    }

    public static function getDescriptionFinance($model, string $type = '')
    {
        $data = self::$type($model, 0, 0, 1);
        if ($data) {
            return @$data['description'];
        }

        return null;
    }

    public static function getDevelopmentStartDateFinance($model)
    {
        $data = self::development($model, 0, 0, 1);
        if ($data) {
            return @$data['start_date'];
        }

        return null;
    }

    public static function getDevelopmentDiscountStatus(PPDBUser $ppdb)
    {
        $discountStatus = true;
        $data = self::development($ppdb, 0,0,1);
        if ($data && isset($data['user_ids']) && $data['user_ids'] && count($data['user_ids'])) {
            if (in_array($ppdb->user_id, $data['user_ids'])) {
                $discountStatus = false;
            }
            if (isset($data['is_discount']) && $data['is_discount']) {
                $discountStatus = true;
            }
        }
        return $discountStatus;
    }

    public static function checkDevelopmentLunasDiscount(PPDBUser $ppdb)
    {
        $hasDiscount = false;
        $hasVoucher = false;
        $discountPercentage = 0;

        $financeData = self::development($ppdb, false, null, true);

        if ($financeData) {
            $hasDiscount = isset($financeData['is_discount']) ? (bool) $financeData['is_discount'] : false;
            $hasVoucher = isset($financeData['is_voucher']) ? (bool) $financeData['is_voucher'] : false;
        }

        if ($hasDiscount) {
            $setting = \App\Models\GeneralSettings::where('slug', 'development-fee-discount')->first();
            if ($setting) {
                $discountPercentage = (float) $setting->value;
            }
        }

        return [
            'is_eligible_discount' => $hasDiscount,
            'discount_percentage' => $discountPercentage,
            'is_eligible_free_voucher' => $hasVoucher
        ];
    }

    public static function getFreeVouchersOlahRagaProductStatus(PPDBUser $ppdb, $developmentFeeOption=null)
    {
        if ($developmentFeeOption == null) {
            $developmentFeeOption = $ppdb->development_fee_option;
        }

        if ($developmentFeeOption === 'lunas') {
            $lunasBenefit = self::checkDevelopmentLunasDiscount($ppdb);
            return $lunasBenefit['is_eligible_free_voucher'] ?? false;
        }

        return false;
    }

    public static function registration($model = null, $withFormat = false, $year = null, $getModel = false)
    {
        return self::collect($model, 'registrasi', 200000, $withFormat, $year, $getModel);
    }

    public static function development($model, $withFormat = false, $year = null, $getModel = false)
    {
        return self::collect($model, 'development', 0, $withFormat, $year, $getModel);
    }

    public static function uniform($model, $withFormat = false, $year = null, $getModel = false)
    {
        return self::collect($model, 'uniform', 1000000, $withFormat, $year, $getModel);
    }

    public static function tuition($model, $withFormat = false, $year = null, $getModel = false)
    {
        return self::collect($model, 'tuition', 100000, $withFormat, $year, $getModel);
    }

    public static function activity($model, $withFormat = false, $year = null, $getModel = false)
    {
        return self::collect($model, 'activity', 100000, $withFormat, $year, $getModel);
    }

    public static function others($model, $withFormat = false, $year = null, $getModel = false)
    {
        return self::collect($model, 'others', 100000, $withFormat, $year, $getModel);
    }

    private static function collect($model, $pattern, $default, $withFormat = false, $year = null, $returnModel = false)
    {
        $finances = Finance::with(['financeUser'])->select(['id','nominal_default', 'name', 'description', 'code', 'start_date', 'is_insider', 'is_voucher', 'is_discount','periode_start','periode_end'])
        ->get()->makeHidden(['id','financeUser'])->keyBy('code')->toArray();

        $keys = collect();
        $year = $year?: date('Y');
        $additional = 0;
        $unit = null;
        $user = null;
        $period = null;
        $price = $default;

        if ($model instanceof PPDBUser) {
            $unit = $model->unit;
            $user = $model->user_id;
            $period = $model->period;
            $year = $model->school_year;
            // if ($pattern === 'registrasi') {
            //     $additional = $model->randomGenerateNumber;
            // }
        }

        if ($model instanceof Student) {
            $unit = @$model->class->unit;
            $user = $model->user_id;
            $year = $model->school_year;
        }

        if ($model instanceof Period) {
            $unit = $model->unit;
            $period = $model;
            $year = $model->school_year;
        }

        if ($model instanceof Unit) {
            $unit = $model;
        }

        //initialize key
        $pattern = 'type_'. $pattern;
        if ($unit) $unit = 'unit_'. @$unit->id;
        // if ($user) $user = 'user_'. @$user;
        if ($year) $year = 'year_'. @$year;
        if ($period) $period = 'period_'. @$period->id;

        if ($period) {
            if ($unit) {
                $keys->push($pattern .'.'. $unit .'.'. $year .'.'. $period);
                $keys->push($pattern .'.'. $unit .'.'. $period);
            }

            $keys->push($pattern .'.'. $year .'.'. $period);
            $keys->push($pattern .'.'. $period);
        }

        if ($unit) {
            $keys->push($pattern .'.'. $unit .'.'. $year);
            $keys->push($pattern .'.'. $unit);
        }

        $keys->push($pattern.'.'.$year);
        $keys->push($pattern);
        $foundModel = false;
        if (count($finances)) {
            foreach ($keys as $key) {
                if ($user && $exists = Arr::first($finances, function ($finance, $index) use ($user, $key) {
                    return Str::contains($index, $key . '.users_') && in_array($user, $finance['user_ids']);
                })) {
                    $price = $exists['nominal_default'];
                    $foundModel = $exists;
                    break;
                } else {
                    if (isset($finances[$key])) {
                        $price = $finances[$key]['nominal_default'];
                        $foundModel = $finances[$key];
                        break;
                    }
                }
            }
        }

        if ($returnModel) {
            return $foundModel;
        }

        $price = $price + $additional;
        if ($withFormat) {
            return self::rupiah($price);
        } else {
            return $price;
        }
    }

    public static function rupiah($nominal, $rupiah=true)
    {
        $retval = number_format(intval($nominal), 0, ',', '.');
        if ($rupiah) {
            $retval = 'Rp '. $retval;
        }
        return $retval;
    }

    public static function virtualAccountNumber($model, $isSeragamPayment = false, $paymentOption = null)
    {
        $unit = null;
        $registrationNumber = null;
        $unitCode = null;
        $typePayment = null;
        $kodeBiller = null;

        if ($model instanceof PPDBUser) {
            $unit = $model->unit;
            $registrationNumber = $model->register_number;
            $typePayment = '07'; //ppdb
        }

        if (! $unit || ! $registrationNumber) {
            return null;
        }

        $unitCode = sprintf("%02d", $unit->id);
        $paymentInfo = self::paymentInfo($unit, $paymentOption);
        $kodeBiller = $paymentInfo['kode_biller'];

        if ($isSeragamPayment) {
            $kodeBiller = $paymentInfo['kode_biller_seragam'];
            $typePayment = '08';
            if ($model instanceof Student) {
                $typePayment = '04';
            }

        }

        if (! $typePayment || ! $kodeBiller) {
            return null;
        }

        return "{$kodeBiller}{$unitCode}{$typePayment}{$registrationNumber}";
        // return 3909004072104006;
    }

    public static function paymentInfo(Unit $unit = null, $paymentOption=null)
    {
        if (!$unit) {
            return null;
        }

        $paymentOption = $paymentOption ? ucwords(strtolower(trim($paymentOption))) : $unit->payment_option;

        switch ($paymentOption) {
            case 'Mandiri':
                return [
                    'bank' => 'Mandiri',
                    'kode_bank' => '008',
                    'kode_biller' => '89227',
                    'kode_biller_seragam' => '89227'
                ];
            case 'Bca':
                return [
                    'bank' => 'BCA',
                    'kode_bank' => '014',
                    'kode_biller' => '13977',
                    'kode_biller_seragam' => '13977'
                ];
            default:
                return [
                    'bank' => 'CIMB Niaga',
                    'kode_bank' => '022',
                    'kode_biller' => '39090',
                    'kode_biller_seragam' => '31390'
                ];
        }
    }

    public static function developmentStudent($model, $withFormat = false, $year = null, $getModel = false)
    {
        return self::collectDevelopment($model, 'development', 15000000, $withFormat, $year, $getModel);
    }

    private static function collectDevelopment($model, $pattern, $default, $withFormat = false, $year = null, $returnModel = false)
    {
        $finances = Finance::with(['financeUser'])->select(['id','nominal_default', 'name', 'description', 'code', 'start_date', 'is_insider'])
            ->get()->makeHidden(['id','financeUser'])->keyBy('code')->toArray();

        $keys = collect();
        $year = $year?: date('Y');
        $additional = 0;
        $unit = null;
        $user = null;
        $period = null;
        $price = $default;

        if ($model instanceof PPDBUser) {
            $unit = $model->unit;
            $user = $model->user_id;
            $period = $model->period;
            $year = $model->school_year;
            // if ($pattern === 'registrasi') {
            //     $additional = $model->randomGenerateNumber;
            // }
        }

        if ($model instanceof Student) {
            $unit = @$model->class->unit;
            $user = $model->user_id;
            $year = $model->school_year;
        }

        if ($model instanceof Period) {
            $unit = $model->unit;
            $period = $model;
            $year = $model->school_year;
        }

        if ($model instanceof Unit) {
            $unit = $model;
        }

        //initialize key
        $pattern = 'type_'. $pattern;
        if ($unit) $unit = 'unit_'. @$unit->id;
        // if ($user) $user = 'user_'. @$user;
        if ($year) $year = 'year_'. @$year;
        if ($period) $period = 'period_'. @$period->id;

        if ($period) {
            if ($unit) {
                $keys->push($pattern .'.'. $unit .'.'. $year .'.'. $period);
                $keys->push($pattern .'.'. $unit .'.'. $period);
            }

            $keys->push($pattern .'.'. $year .'.'. $period);
            $keys->push($pattern .'.'. $period);
        }

        if ($unit) {
            $keys->push($pattern .'.'. $unit .'.'. $year);
            $keys->push($pattern .'.'. $unit);
        }

        $keys->push($pattern.'.'.$year);
        $keys->push($pattern);

        $foundModel = false;

        if (count($finances)) {

            foreach ($keys as $key) {

                if ($user && $exists = Arr::first($finances, function ($finance, $index) use ($user, $key) {
                        return Str::contains($index, $key . '.users_') && in_array($user, $finance['user_ids']);
                    })) {
                    $max_date = 0;
                    $foundModel = $exists;
                    break;
                } else {
                    if (isset($finances[$key])) {

                        $max_date = $finances[$key]['start_date'];
                        $foundModel = $finances[$key];
                        break;
                    }
                }
            }
        }

        if ($returnModel) {
            return $foundModel;
        }

        return $max_date;
    }

    public static function getPeriodPayment($model, string $type = '')
    {
        $diskonStatus = '';
        $data = self::$type($model, 0, 0, 1);
        if ($data) {
            if (isset($data['periode_start']) && $data['periode_start']) {
                $diskonStatus = 'Wajib dibayarkan mulai tanggal '. Carbon::parse($data['periode_start'])->format('d-m-Y');
            }

            if (isset($data['periode_end']) && $data['periode_end']) {
                $diskonStatus .= ' sampai tanggal '. Carbon::parse($data['periode_end'])->format('d-m-Y');
            }
        }

        return $diskonStatus;
    }

    public static function getPeriodFinance($model, string $type = ''){
        $data = self::$type($model, 0, 0, 1);
        if (isset($data['periode_end']) && $data['periode_end']) {
            return Carbon::parse($data['periode_end'])->endOfDay();
        }
        return null;
    }

    public static function getDatePeriodePayment($model, string $type = '')
    {
        $date_start = $date_end = null;
        $data = self::$type($model, 0, 0, 1);
        if ($data) {
            $date_start = $data['periode_start'];
            $date_end = $data['periode_end'];
        }
        return ['start' => $date_start, 'end' => $date_end];
    }

}
