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
