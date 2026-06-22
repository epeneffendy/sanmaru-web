<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\Stage;
use App\Models\PPDBUser;
use App\Models\PPDBUserStage;
use App\Services\PPDBUserService;
use App\Services\PaymentDispensationsService;
use App\Models\PaymentDispensations;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function welcome()
    {
        $data = array(
            'nav' => ['parent' => 'home', 'child'=>'Home'],
        );

        return view('administrator.student-welcome', $data);
    }

    public function financeBills(Request $request, PPDBUserService $ppdbUserService, PaymentDispensationsService $paymentDispensationsService)
    {
        $user = $request->session()->get('user');
        $ppdbUser = PPDBUser::where('id', $user['ppdb']['id'])->first();

        $is_show = false;
        // if($ppdbUser->development_fee_option == PPDBUser::DEVELOPMENT_FEE_ANGSURAN){
        //     $is_show = true;
        // }

        $bills = $ppdbUserService->getBills($user['ppdb']['id']);

        $dispensation = $paymentDispensationsService->getAllBilling($user['ppdb']['id']);

        $stages = Stage::where('periode', $user['ppdb']['periode'])->get();
        $arr_stage = [];
        foreach($stages as $ind => $stage){
            $feature = 'no_feature';
            if($stage->is_opening_shop_feature){
                $feature = 'shop';
            }
            if($stage->is_opening_development_feature){
                $feature = 'development';
            }

            $arr_stage[$ind][$stage->id] = $feature;
        }
        $cekStage = PPDBUserStage::where('ppdb_user_id', $user['ppdb']['id'])->orderBy('id', 'desc')->first();
        if($cekStage && $cekStage->passed == 1){
            $currentIndex = -1;
            foreach ($arr_stage as $ind => $stageArray) {
                if (isset($stageArray[$cekStage->stage_id])) {
                    $currentIndex = $ind;
                    break;
                }
            }

            // Cek apabila tahapan berikutnya ada, dan valuenya adalah 'development'
            if ($currentIndex !== -1 && isset($arr_stage[$currentIndex + 1])) {
                $nextStage = $arr_stage[$currentIndex + 1];
                $nextFeature = reset($nextStage); // Mengambil value array pertama

                if ($nextFeature === 'development') {
                    $is_show = true;
                }
            }
        }

        if($ppdbUser->student->status == Student::STATUS_ACTIVE){
            $is_show = true;
        }

        $is_dispensation = false;
        $arr_dispensation = [];
        if($dispensation){
            foreach($dispensation as $ind => $d){
                if($d->dispensation_mode != PaymentDispensations::MODE_REAL_PAYMENT){
                    $arr_dispensation[$d->dispensation_type]['type'] = $d->dispensation_type;
                    $arr_dispensation[$d->dispensation_type]['total_final_fee'] = $d->total_final_fee;
                    $arr_dispensation[$d->dispensation_type]['is_dispensation'] = true;
                }
            }
        }

        $data = array(
            'bills' => $bills['bills'],
            'bill_amount' => $bills['bill_amount'],
            'ppdb'=>$user['ppdb'],
            'is_dispensation'=>$is_dispensation,
            'dispensation'=>$dispensation,
            'arr_dispensation'=>$arr_dispensation,
            'is_show' => $is_show,
            'ppdbUser'=> $ppdbUser,
            'nav' => ['parent' => 'data', 'child' => 'Data Siswa']
        );

        return view('student-dashboard.finance.form-finance-bills', $data);
    }

    public function registrationPaymentReceipt($id){
        $ppdb = PPDBUser::where('id', $id)->first();

        $data = array(
            'data' => $ppdb,
            'nav' => ['parent' => 'data', 'child' => 'Data Siswa']
        );

        return view('student-dashboard.finance.partial._registration_receipt', $data);
    }

    public function getDevelopmentStatementLetterFile()
    {
        $user = request()->session()->get('user');
        $ppdbUser = PPDBUser::where('user_id', $user['id'])->firstOrFail();
        $filename = $ppdbUser->getDevelopmentStatementUrl();

        $type = (strpos($filename, '.jpg') !== false) ? "image/jpeg" : ((strpos($filename, '.jpeg') !== false) ? "image/jpeg" : ((strpos($filename, '.pdf') !== false) ? 'application/pdf' : "image/png"));

        return response($ppdbUser->getDevelopmentStatementFile())->withHeaders([
            'Content-Type' => $type,
        ]);
    }

}
