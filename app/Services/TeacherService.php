<?php

namespace App\Services;

use App\Models\Teacher;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherService
{
    public function update($id, $params)
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::where('id', $id)->firstOrFail();
            $teacher->update($params);
            $user = $teacher->user;
            $usrParams = Arr::except($params, 'name');
            $user->update($usrParams);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
    }

    public function updateByNik($params)
    {
        $teacher = Teacher::where('nik', $params['nik'])->firstOrFail();
        DB::beginTransaction();
        try {
            $teacher->update($params);
            $user = $teacher->user;
            $usrParams = Arr::except($params, 'name');
            $user->update($usrParams);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
