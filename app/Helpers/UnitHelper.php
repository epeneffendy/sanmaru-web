<?php 

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\Unit;

class UnitHelper 
{
    public static function webUnitSlug(Unit $unit)
    {
        $level      = self::level($unit->name);
        $city_alias = self::cityAlias($unit->city);

        if (in_array($level, ['kb', 'tk'])) {
            $level = 'kbtk';
        }

        return Str::slug("{$level} {$city_alias}");
    }

    public static function level(string $name)
    {
        return Str::before(strtolower(trim($name)), '-');
    }

    public static function cityAlias(string $city)
    {
        $city = strtolower(trim($city));
        $aliases = [
            'surabaya' => 'sby',
            'sidoarjo' => 'sda',
            'mojokerto' => 'pacet'
        ];

        return $aliases[$city] ?? $city;
    }
}