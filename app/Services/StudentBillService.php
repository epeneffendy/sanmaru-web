<?php
namespace App\Services;

use App\Models\Student;
use App\Models\PPDBUser;
use App\Exceptions\UserException;
use App\Models\StudentBills;
use App\Models\PaymentDispensations;

class StudentBillService
{
    public function getStudentBills($student_id)
    {
        $bills = StudentBills::where('ppdb_user_id', $student_id)->get();
        if (!$bills) {
            throw new UserException("Siswa tidak ditemukan");
        }
        return $bills;
    }

    public function getStudentBillReport($params){
        $ppdbUsers = PPDBUser::orderBy('ppdb_users.created_at', 'ASC');

        if (isset($params['unit']) && $params['unit'] != 'all') {
            $ppdbUsers->where('ppdb_users.unit_id', $params['unit']);
        }

        if (isset($params['period']) && $params['period'] != 'all') {
            $ppdbUsers->where('ppdb_users.periode', $params['period']);
        }

        if (isset($params['year']) && $params['year'] != 'all') {
            $ppdbUsers->where('ppdb_users.school_year', $params['year']);
        }

        $ppdbUsers = $ppdbUsers->get();

        $collections = [];
        
        foreach($ppdbUsers as $ppdbUser){
            $bills = $this->getStudentBills($ppdbUser->id);
            $collections[$ppdbUser->id]['name'] = $ppdbUser->name;
            $collections[$ppdbUser->id]['register_number'] = $ppdbUser->register_number;
            $collections[$ppdbUser->id]['unit'] = $ppdbUser->unit->name;
            $collections[$ppdbUser->id]['billing'] = ($bills->count() > 0) ? true : false;
            if($bills->count() > 0){
                
                foreach($bills as $bill){
                    $is_dispensation = false;        
                    if($bill->type == StudentBills::BILL_TYPE_DEVELOPMENT){
                        $dispensations = PaymentDispensations::where('ppdb_user_id', $ppdbUser->id)
                            ->where('status', PaymentDispensations::STATUS_ACTIVE)
                            ->where('dispensation_mode','<>', PaymentDispensations::MODE_REAL_PAYMENT)
                            ->where('dispensation_type', PaymentDispensations::DISPENSATION_TYPE_DEVELOPMENT)
                            ->orderBy('id', 'desc')->first();
                        
                            if($dispensations){
                                $is_dispensation = true;
                            }
                    }
                    $collections[$ppdbUser->id]['bills'][$bill->finance_id]['type'] = $bill->type;
                    $collections[$ppdbUser->id]['bills'][$bill->finance_id]['desc'] = $bill->finance->name;
                    $collections[$ppdbUser->id]['bills'][$bill->finance_id]['is_dispensation'] = $is_dispensation;
                    $collections[$ppdbUser->id]['bills'][$bill->finance_id]['total_final_fee'] = ($is_dispensation) ? $dispensations->total_final_fee : 0;
                    $collections[$ppdbUser->id]['bills'][$bill->finance_id]['amount'] = $bill->amount;
                    $collections[$ppdbUser->id]['bills'][$bill->finance_id]['payment_term'] = $this->getPaymentTerm($bill->payment_term);
                    $collections[$ppdbUser->id]['bills'][$bill->finance_id]['payment_method'] = $this->getPaymentMethod($bill->payment_method);   
                }
            }
        }
        
        return $collections;
    }

    public function getPaymentMethod($status) {
        switch ($status) {
            case StudentBills::PAYMENT_METHOD_UNPAID:
                return 'Belum Bayar';
            case StudentBills::PAYMENT_METHOD_PAID:
                return 'Sudah Bayar';
            case StudentBills::PAYMENT_METHOD_PARTIAL:
                return 'Pembayaran Sebagian';
            case StudentBills::PAYMENT_METHOD_CANCELED:
                return 'Pembayaran Dibatalkan';
            default:
                return '';
        }
    }

    public function getPaymentTerm($status) {
        switch ($status) {
            case StudentBills::PAYMENT_TERM_FULL:
                return 'Lunas';
            case StudentBills::PAYMENT_TERM_INSTALLMENT:
                return 'Cicilan';
            default:
                return '';
        }
    }
}
            