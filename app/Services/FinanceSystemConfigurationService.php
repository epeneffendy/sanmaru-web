<?php

namespace App\Services;

use App\Models\FinanceSystemConfigurations;
use App\Lib\DbTrx;

class FinanceSystemConfigurationService {

    public function get()
    {
        $data = FinanceSystemConfigurations::get();
        return $data;
    }

    public function findById($id){
        $deadlines = FinanceSystemConfigurations::where('id',$id)->first();
        return $deadlines;
    }

    public function findConfigurationActive(){
        $configuration = FinanceSystemConfigurations::where('effective_date', '<=', now())
                    ->orderBy('id', 'desc')
                    ->first();
        return $configuration;
    }

    public function getDpTenorScheme($configuration = null)
    {
        if ($configuration && !empty($configuration->dp_tenor_scheme)) {
            $scheme = is_array($configuration->dp_tenor_scheme)
                ? $configuration->dp_tenor_scheme
                : json_decode($configuration->dp_tenor_scheme, true);

            if (!empty($scheme) && is_array($scheme)) {
                return $scheme;
            }
        }

        // Default dynamic fallback scheme mapping
        return [
            ['dp' => 30, 'max_tenor' => 2],
            ['dp' => 35, 'max_tenor' => 3],
            ['dp' => 40, 'max_tenor' => 4],
            ['dp' => 50, 'max_tenor' => 5],
            ['dp' => 55, 'max_tenor' => 6],
            ['dp' => 60, 'max_tenor' => 7],
            ['dp' => 65, 'max_tenor' => 8],
            ['dp' => 70, 'max_tenor' => 9],
            ['dp' => 75, 'max_tenor' => 10],
        ];
    }


    public function create($params)
    {
        return FinanceSystemConfigurations::create($params);
    }

    public function update($id, $params)
    {
        DbTrx::useTrx(
            function () use ($params, $id) {
                $deadline = FinanceSystemConfigurations::where('id', $id)->firstOrFail();
                $deadline->update($params);
            }
        );
    }

}
