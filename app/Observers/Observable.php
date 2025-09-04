<?php

namespace App\Observers;

trait Observable
{
    public static function bootObservable()
    {
        if (get_called_class()::$observer !== null) {
            static::observe(get_called_class()::$observer);
        }
    }
}
