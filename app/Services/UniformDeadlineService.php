<?php

namespace App\Services;

use App\Models\UniformDeadline;
use App\Lib\DbTrx;

class UniformDeadlineService
{

    public function get()
    {
        $deadlines = UniformDeadline::get();
        return $deadlines;
    }

    public function findById($id){
        $deadlines = UniformDeadline::where('id',$id)->first();
        return $deadlines;
    }

    public function validateDeadline($param)
    {
        $deadline = UniformDeadline::where([
            'unit_id' => $param['unit_id'],
            'school_year' => $param['school_year'],
            'status' => 1
        ])->first();

        $status = true;
        if ($deadline) {
            $status = false;
        }
        return $status;
    }

    public function create($params)
    {
        return UniformDeadline::create($params);
    }

    public function update($id, $params)
    {
        DbTrx::useTrx(
            function () use ($params, $id) {
                $deadline = UniformDeadline::where('id', $id)->firstOrFail();
                $deadline->update($params);
            }
        );
    }
}
