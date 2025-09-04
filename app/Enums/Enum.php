<?php

namespace App\Enums;

use ReflectionClass;

abstract class Enum
{
    public static function getValues()
    {
        $reflectionClass = new ReflectionClass(static::class);
        return $reflectionClass->getConstants();
    }

}
