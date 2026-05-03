<?php

namespace App\Services;

use App\Models\Period;
use App\Models\PPDBUser;
use App\Models\PPDBUserStage;
use App\Models\Stage;
use App\Models\Voucher;
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

        //cari siswa yang sudah menyelesaikan administrasi
        $passedUserIds = $this->studentPassed($period);

        foreach ($stages as $index => $stage) {

            //check stage
            $stageUsed = PPDBUserStage::where('stage_id', $stage->id)->count();

            $ppdbUsers = PPDBUser::where('unit_id', $period->unit_id)
                ->where('periode', $period->id)
                ->whereIn('ppdb_users.id', $passedUserIds)
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

    public function stagesAdministrasi($period, $isList, $flag)
    {
        $ppdbUser = PPDBUser::where('periode', $period->id)->get();

        $confirm = $not_confirm = $not_specified = 0;
        $collection = [];

        if ($isList) {
            foreach ($ppdbUser as $user) {
                if (($user->isDataCompleteWhitoutBca) && ($user->isParentsComplete)) {
                     $status_confirm = '<span class="badge-modern badge-soft-info" title="Periode"> Dokumen Lengkap</span>';
                } else {
                    $status_confirm = '<span class="badge-modern badge-soft-warning" title="Periode"> Dokumen Belum Lengkap </span>';
                }

                $stage_status = '';
                if (!empty($user->stages_status)) {
                    if ($user->stages_status == 'not_passed') {
                        $stage_status = '<label class="label label-danger">Tidak Lolos</label>';
                    } elseif ($user->stages_status == 'pending') {
                        $stage_status = '<label class="label label-danger">Proses Pending</label>';
                    }
                }

                $voucher = '';
                if ($user->development_fee_option == 'lunas') {
                    $checkVoucher = $this->checkVocuher($user);
                    //cari voucher
                    $check = Voucher::where('code', $checkVoucher)
                        ->whereJsonContains('user_id', $user->user_id)// Mengecek apakah 15706 ada dalam array user_id
                        ->where('active', 1)
                        ->exists();
                    if ($check) {
                        $voucher = '<span class="badge-modern badge-soft-success">Free Voucher: ' .$checkVoucher. '</span>';
                    }
                }

                if ($flag == 'development-statement') {
                    
                    if (!empty($user->IsStatementLetterUploaded)) {
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
                            'status_stage' => $stage_status,
                            'voucher' => $voucher
                        ];
                    }
                } elseif ($flag == 'last-stage') {
                    if ($user->isOrderConfirmed) {
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
                            'status_stage' => $stage_status,
                            'voucher' => $voucher,
                        ];
                    }
                } elseif ($flag == 'setting-class') {
                    if ($user->status == PPDBUser::STATUS_ACCEPTED && ($user->user->type == 'ppdb' || $user->user->type == 'siswa')) {
                        $father_name = $mother_name = $father_nik = $mother_nik = '';
                        foreach ($user->parents as $parent) {
                            if ($parent->type == 'father') {
                                $father_name = $parent->name;
                            } else {
                                $mother_name = $parent->name;
                            }
                        }

                        $nisn = $class = '';
                        if (!empty($user->student)) {
                            $nisn = $user->student->nis;
                            $class = $user->student->class->name;
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
                            'status_stage' => $stage_status,
                            'voucher' => $voucher,
                            'nik_siswa' => $user->nik_siswa,
                            'nik_ortu' => $user->nik_ortu,
                            'address' => $user->address,
                            'region' => $user->region,
                            'city' => $user->city,
                            'father_name' => $father_name,
                            'mother_name' => $mother_name,
                            'nisn' => $nisn,
                            'class' => $class
                        ];
                    }
                } else {

                    $checkStage = Stage::join('ppdb_user_stages as pus', 'pus.stage_id', '=', 'stages.id')
                        ->where('stages.periode', $user->periode)
                        ->where('stages.is_opening_development_feature', true)
                        ->where('pus.ppdb_user_id', $user->id)
                        ->select('stages.*', 'pus.*')
                        ->first();

                    $is_stage_development = false;
                    $stage_id = 0;
                    if (isset($checkStage)) {
                        if (empty($user->IsStatementLetterUploaded)){
                            if ($checkStage->passed == 1) {
                                $is_stage_development = true;
                                $stage_id = $checkStage->stage_id;
                            }
                        }
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
                        'status_stage' => $stage_status,
                        'voucher' => $voucher,
                        'IsStageDevelopment' => $is_stage_development,
                        'stage_id'=>$stage_id,
                        'status_student'=>$user->user->type,
                        'nis'=> isset($user->user->student)? $user->user->student->nis : '-',
                        'class_name'=> isset($user->user->student->class) ? $user->user->student->class->name : '-',
                    ];


                }
            }
        } else {
            if ($flag == 'administration') {
                foreach ($ppdbUser as $user) {
                    if (($user->isDataCompleteWhitoutBca) && ($user->isParentsComplete)) {
                        $confirm++;
                    } else {
                        $not_confirm++;
                    }
                    $collection['confirm'] = $confirm;
                    $collection['not_confirm'] = $not_confirm;
                }
            } else {
                foreach ($ppdbUser as $user) {

                    if ($user->status == PPDBUser::STATUS_ACCEPTED) {
                        $confirm++;
                    } elseif ($user->status == PPDBUser::STATUS_NOT_SELECTED) {
                        $not_confirm++;
                    } elseif ($user->status == PPDBUser::STATUS_SUBMITTED) {
                        $not_specified++;
                    }
                    $collection['confirm'] = $confirm;
                    $collection['not_confirm'] = $not_confirm;
                    $collection['not_specified'] = $not_specified;
                }


            }

        }

        return $collection;
    }

    public function studentPassed($period)
    {
        $ppdbUser = PPDBUser::where('periode', $period->id)->get();
        $arr = [];
        foreach ($ppdbUser as $user) {
            if ($user->isDataCompleteWhitoutBca) {
                $arr[] = $user->id;
            }
        }

        return $arr;
    }

    function checkVocuher($user)
    {
        $code = "";
        $unit = 0;
        $periode = 0;
        if ($user->unit_id) {
            $unit = (int)$user->unit_id;
        }
        if ($user->register_number) {
            $periode = (int)substr($user->register_number, 0, 2);
        }

        $code = $code . sprintf("%02d%02d%02d", $unit, $periode, ($periode + 1));
        if ($user->gender && $user->gender === 'female') {
            $code = $code . 'PI';
        } else {
            $code = $code . 'PA';
        }

        return $code;
    }
}
