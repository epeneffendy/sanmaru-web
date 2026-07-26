<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentDispensationDetails;
use App\Services\EmailService;
use App\Mail\OverdueBillNotificationMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OverdueBillController extends Controller
{
     private $page = [
        'parent' => 'finance-configuration',
        'child' => 'overdue-bills'
    ];

    public function index(Request $request){
        // Fetch overdue bills
        $overdueBills = PaymentDispensationDetails::with(['dispensation.ppdb', 'dispensation.ppdb.unit'])
            ->whereNotNull('plan_date')
            ->whereDate('plan_date', '<', Carbon::today())
            ->where('status', '!=', PaymentDispensationDetails::STATUS_PAID)
            ->orderBy('plan_date', 'asc')
            ->get();

        return view('administrator.overdue-bills.index', [
            'nav' => $this->page,
            'overdueBills' => $overdueBills
        ]);
    }

    public function broadcast(Request $request)
    {
        $overdueBills = PaymentDispensationDetails::with(['dispensation.ppdb.user', 'dispensation.ppdb.unit'])
            ->whereNotNull('plan_date')
            ->whereDate('plan_date', '<', Carbon::today())
            ->where('status', '!=', PaymentDispensationDetails::STATUS_PAID)
            ->get();

        $count = 0;
        foreach ($overdueBills as $bill) {
            try {
                $ppdbUser = $bill->dispensation->ppdb ?? null;
                $user = $ppdbUser ? $ppdbUser->user : null;

                if ($ppdbUser && $user && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    $template = new OverdueBillNotificationMail($bill, $ppdbUser);
                    (new EmailService())->sendMail($template, $user->email);
                    $count++;
                }
            } catch (\Exception $e) {
                Log::error('Error broadcasting overdue bill email: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('message', "Berhasil menjadwalkan {$count} email tagihan tertunda untuk dikirim.");
    }
}
