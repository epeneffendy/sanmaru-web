<?php

namespace App\Services;

use App\Lib\DbTrx;
use App\Models\User;
use App\Models\Vendor;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VendorService
{
    public function update($id, $params)
    {
        DbTrx::useTrx(
            function () use ($params, $id) {
                $vendor = Vendor::where('id', $id)->firstOrFail();
                $vendor->update($params);
                $user = $vendor->user;
                $user->update($this->userParams($params));
            }
        );
    }

    public function updateByEmail($params)
    {
        DbTrx::useTrx(
            function () use ($params) {
                $user = User::where('email', $params['email'])->firstOrFail();
                $user->update($this->userParams(($params)));
                $vendor = $user->vendor;
                $vendor->update($params);
            }
        );
    }

    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor['email'] = User::where(['id' => $vendor->user_id])->limit(1)->pluck('email')->first();
        return $vendor;
    }

    private function userParams($params)
    {
        return array(
            'mobile_phone' => $params['mobile_phone'],
            'email' => $params['email']
        );
    }
}
