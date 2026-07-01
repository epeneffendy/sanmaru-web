<?php

namespace App\Services;

use App\Models\FinancePeriode;
use App\Lib\DbTrx;

class FinancePeriodeService {

    public function get()
    {
        $data = FinancePeriode::get();
        return $data;
    }

   

    public function create($params)
    {
        return FinancePeriode::create($params);
    }

    public function update($id, $params)
    {
        DbTrx::useTrx(
            function () use ($params, $id) {
                $periode = FinancePeriode::where('id', $id)->firstOrFail();
                $periode->update($params);
            }
        );
    }

}
