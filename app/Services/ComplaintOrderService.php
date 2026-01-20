<?php

namespace App\Services;

use App\Models\ComplaintOrders;
use App\Models\ComplaintPeriode;
use Carbon\Carbon;

class ComplaintOrderService
{
    public function get()
    {
        $data = ComplaintOrders::orderBy('id','desc')->get();
        return $data;
    }

    public function getById($id)
    {
        $data = ComplaintOrders::whereId($id)->first();
        return $data;
    }

    public function changeStatus($id, $status, $desc = null)
    {
        $success = false;
        $message = 'Prosess Gagal';
        $data = ComplaintOrders::whereId($id)->first();

        if (isset($data)) {
            if($status == 'rejected'){
                $data->reason = $desc['reason'];
            }
            if($status == 'pickup'){
                $data->date_pickup = Carbon::parse($desc['date'])->format('Y-m-d');
                $data->location_pickup = $desc['location'];
            }
            $data->status = $status;
            if ($data->save()) {
                $success = true;
                $message = 'Prosess Berhasil';
            }
        }
        return [
            'success' => $success,
            'message' => $message
        ];
    }

    public function settingPeriod($payload)
    {

        $ppdb = ComplaintPeriode::where('type', 'ppdb')->update([
            'date_start' => $payload['date_start_ppdb'],
            'date_end' => $payload['date_end_ppdb'],
            'status' => $payload['status_ppdb'],
        ]);

        $siswa = ComplaintPeriode::where('type', 'siswa')->update([
            'date_start' => $payload['date_start_siswa'],
            'date_end' => $payload['date_end_siswa'],
            'status' => $payload['status_siswa'],
        ]);

    }
}
