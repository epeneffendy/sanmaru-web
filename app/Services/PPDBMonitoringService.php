<?php

namespace App\Services;

use App\Models\Period;
use App\Models\PPDBUser;
use App\Models\PPDBUserStage;
use App\Models\Stage;
use App\Traits\ImageHandler;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class PPDBMonitoringService
{
    use ImageHandler;

    public function stages($stages, $period)
    {

        $collection = [];
        $is_locked = false;
        $completedStagesCount = 0;
        foreach ($stages as $index => $stage) {

            //check stage
            $stageUsed = PPDBUserStage::where('stage_id', $stage->id)->count();

            $ppdbUsers = PPDBUser::where('unit_id', $period->unit_id)
                ->where('periode', $period->id)
                ->select('ppdb_users.id', 'name', 'register_number', 'unit_id', 'periode', 'ppdb_user_stages.passed', 'ppdb_user_stages.note')
                ->leftJoin('ppdb_user_stages', function ($join) use ($stage) {
                    return $join->on('ppdb_users.id', '=', 'ppdb_user_stages.ppdb_user_id')->where('stage_id', $stage->id);
                })
                ->get();
            $totalSiswa = $ppdbUsers->count();

            $not_confirm = $pending = $passed = $not_passed = $total = 0;
            $current_stage_locked = $is_locked;

            if ($stage->is_opening_shop_feature) {
                $accepted = [];
                $development = Stage::where('unit_id', $period->unit_id)->where('periode', $period->id)->where('active', 1)->where('is_opening_development_feature', 1)->first();
                if ($development) {
                    $accepted = PPDBUserStage::where('stage_id', $development->id)
                        ->where('passed', 1)->pluck('ppdb_user_id')->all();
                }

                $ppdbUsers = $ppdbUsers->filter(function ($ppdbUser) use ($accepted) {
                    return in_array($ppdbUser->id, $accepted);
                })->values();
                foreach ($ppdbUsers as $user) {
                    if (isset($user->passed)) {
                        switch ($user->passed) {
                            case 1:
                                $passed++;
                                break;
                            case 0:
                                $not_passed++;
                                break;
                            case 2:
                                $pending++;
                                break;
                            default:
                                $not_confirm++;
                                break;
                        }
                    } else {
                        $not_confirm++;
                    }

                    if ($totalSiswa != $stageUsed) {
                        $is_locked = true;
                    }

                    $overallProgress = ($totalSiswa > 0) ? ($stageUsed / $totalSiswa) * 100 : 0;

                    $collection[$stage->id]['not_confirm'] = $not_confirm;
                    $collection[$stage->id]['pending'] = $pending;
                    $collection[$stage->id]['passed'] = $passed;
                    $collection[$stage->id]['not_passed'] = $not_passed;
                    $collection[$stage->id]['total'] = count($ppdbUsers);
                    $collection[$stage->id]['is_locked'] = $is_locked;
                    $collection[$stage->id]['overallProgress'] = $overallProgress;
                    $collection[$stage->id]['stageUsed'] = $stageUsed;
                }

            } else {
                foreach ($ppdbUsers as $ind => $user) {
                    if (isset($user->passed)) {
                        switch ($user->passed) {
                            case 1:
                                $passed++;
                                break;
                            case 0:
                                $not_passed++;
                                break;
                            case 2:
                                $pending++;
                                break;
                            default:
                                $not_confirm++;
                                break;
                        }
                    } else {
                        $not_confirm++;
                    }

                    if ($totalSiswa != $stageUsed) {
                        $is_locked = true;
                    }

                    $overallProgress = ($totalSiswa > 0) ? ($stageUsed / $totalSiswa) * 100 : 0;

                    $collection[$stage->id]['not_confirm'] = $not_confirm;
                    $collection[$stage->id]['pending'] = $pending;
                    $collection[$stage->id]['passed'] = $passed;
                    $collection[$stage->id]['not_passed'] = $not_passed;
                    $collection[$stage->id]['total'] = count($ppdbUsers);
                    $collection[$stage->id]['is_locked'] = $is_locked;
                    $collection[$stage->id]['overallProgress'] = $overallProgress;
                    $collection[$stage->id]['stageUsed'] = $stageUsed;


                }
                $not_confirm = $pending = $passed = $not_passed = $total = 0;
            }

        }

        return $collection;

    }

    public function stagesAdministrasi($period, $isList)
    {
        $ppdbUser = PPDBUser::where('periode', $period->id)->get();

        $confirm = $not_confirm = 0;
        $collection = [];

        if ($isList) {
            foreach ($ppdbUser as $user) {
                if ($user->isDataCompleteWhitoutBca) {
                    $status_confirm = '<label class="label label-success">Dokumen Sudah Lengkap</label>';
                } else {
                    $status_confirm = '<label class="label label-warning">Dokumen Belum Lengkap</label>';
                }

                $collection[$user->id] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'status_confirm' => $status_confirm,
                    'email' => $user->email,
                    'register_number' => $user->register_number,
                    'school_year' => $user->school_year,
                    'periode' => $user->periode,
                    'username' => $user->user->username,
                    'email' => $user->user->email,
                    'periode_name' => $user->period->name,
                    'origin_school' => $user->origin_school,
                    'mobile_phone' => $user->user->mobile_phone,
                    'gender' => $user->gender,
                    'unit_id' => $user->unit->id,
                    'unit_name' => $user->unit->name,
                    'isComplite' => $user->isDataCompleteWhitoutBca,
                    'isParent' => $user->isParentsComplete,
                    'IsStatementLetterUploaded' => $user->IsStatementLetterUploaded,
                    'IsStatementLetterConfirmed' => $user->IsStatementLetterConfirmed,
                    'development_fee_option' => $user->development_fee_option,
                    'isOrderConfirmed' => $user->isOrderConfirmed,
                    'isEmailVerified' => $user->isEmailVerified,
                    'payment_date' => $user->payment_date,
                    'total_payment_form' => $user->total_payment_form,
                ];
            }
        } else {
            foreach ($ppdbUser as $user) {
                if ($user->isDataCompleteWhitoutBca) {
                    $confirm++;
                } else {
                    $not_confirm++;
                }
                $collection['confirm'] = $confirm;
                $collection['not_confirm'] = $not_confirm;
            }
        }

        return $collection;
    }
}
