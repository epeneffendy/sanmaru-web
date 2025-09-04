<?php

namespace App\Lib;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DbTrx
{
    public static function useTrx($func, $exceptionFunc = null){
        try{
            DB::beginTransaction();
            $func();
            DB::commit();
        } catch(Exception $e)
        {
            DB::rollBack();
            Log::error($e->getMessage());
            if ($exceptionFunc !== null) $exceptionFunc();
        }
    }
}
