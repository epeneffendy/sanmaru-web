<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Unit;
use App\Services\PPDBMonitoringService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use App\Models\PaymentVirtualAccounts;
use App\Models\PaymentDispensations;

class PPDBSuspendedController extends Controller
{

    private $page = [
        "parent" => "ppdb",
        "child" => "ppdb-suspended",
    ];

    public function index(Request $request, PPDBMonitoringService $PPDBMonitoringService)
    {
        // Construct the base query to select suspended virtual accounts with student details
        $suspendeds = DB::table('payment_virtual_accounts as a')
            ->select('a.id','d.name as student_name', 'a.expired_at', 'a.type', 'a.virtual_account_number','d.school_year', 'e.name as unit_name' , 'f.name as period_name')
            ->join('payment_dispensations as b', 'a.ppdb_user_id', '=', 'b.ppdb_user_id')
            ->join('payment_dispensation_details as c', 'b.id', '=', 'c.payment_dispensation_id')
            ->join('ppdb_users as d', 'd.id', '=', 'a.ppdb_user_id')
            ->join('units as e', 'd.unit_id', '=', 'e.id')
            ->join('periods as f', 'd.periode', '=', 'f.id')
            ->where('a.status', 'expired')
            ->where('c.installment_number', 0);

        // Apply search filter for student name (d.name)
        $search = $request->input('name') ?? $request->input('search');
        if (!empty($search)) {
            $suspendeds->where('d.name', 'like', '%' . $search . '%');
        }

        // Apply unit filter
        if ($request->filled('unit') && $request->input('unit') != '0') {
            $suspendeds->where('d.unit_id', $request->input('unit'));
        }

        // Apply period filter
        if ($request->filled('period') && $request->input('period') != '0') {
            $suspendeds->where('d.periode', $request->input('period'));
        }

        // Apply school year filter
        if ($request->filled('year') && $request->input('year') != '0' && $request->input('year') != 'all') {
            $suspendeds->where('d.school_year', $request->input('year'));
        }

        // Group by the requested columns to ensure unique entries for each virtual account
        $suspendeds->groupBy('a.virtual_account_number', 'd.name', 'a.type', 'a.expired_at', 'd.school_year', 'e.name', 'f.name');

        // Order the results by student name for better readability
        $suspendeds->orderBy('d.name', 'asc');

        // Paginate the results and append query parameters
        $per_page = 15;
        $paginator = $suspendeds->paginate($per_page);
        $paginator->appends($request->query());

        $data = [
            'nav' => $this->page,
            'data' => $paginator,
            'units' => Unit::byUserRole()->get(),
            'periods' => Period::byUserRole()->get(),
            'params' => [
                'name' => $search,
                'unit' => $request->input('unit'),
                'period' => $request->input('period'),
                'year' => $request->input('year', 0),
            ]
        ];

        return view('administrator/ppdb-suspended/index', $data);
    }

    public function detail($id)
    {
        $bMax = DB::table('payment_dispensations')
            ->select('ppdb_user_id', 'dispensation_type', DB::raw('MAX(id) as max_id'))
            ->groupBy('ppdb_user_id', 'dispensation_type');

        $va = DB::table('payment_virtual_accounts as a')
            ->select(
                'a.id',
                'c.id as dispensation_detail_id',
                'b.id as payment_dispensation_id',
                'a.virtual_account_number',
                'a.type',
                'a.total_payment',
                'a.status',
                'a.expired_at',
                'a.created_at as va_created_at',
                'd.name as student_name',
                'd.school_year',
                'd.register_number',
                'd.payment_tolerance_expired_at',
                'e.name as unit_name',
                'f.name as period_name',
                'b.dispensation_mode',
                'b.total_final_fee',
                'b.actual_cost'
            )
            ->join('ppdb_users as d', 'd.id', '=', 'a.ppdb_user_id')
            ->leftJoin('units as e', 'd.unit_id', '=', 'e.id')
            ->leftJoin('periods as f', 'd.periode', '=', 'f.id')
            ->leftJoinSub($bMax, 'b_max', function($join) {
                $join->on('a.ppdb_user_id', '=', 'b_max.ppdb_user_id')
                     ->on('a.type', '=', 'b_max.dispensation_type');
            })
            ->leftJoin('payment_dispensations as b', 'b.id', '=', 'b_max.max_id')
            ->leftJoin('payment_dispensation_details as c', function($join) {
                $join->on('c.payment_dispensation_id', '=', 'b.id')
                     ->where('c.installment_number', '=', 0);
            })
            ->where('a.id', $id)
            ->first();
        if (!$va) {
            return response()->json(['status' => false, 'message' => 'Data penangguhan tidak ditemukan.'], 404);
        }
        
        $dispensationDetails = DB::table('payment_dispensation_details as c')
            ->where('c.payment_dispensation_id', $va->payment_dispensation_id)
            ->select('c.installment_number', 'c.nominal', 'c.amount_paid', 'c.status', 'c.date', 'c.virtual_account')
            ->orderBy('c.installment_number', 'asc')
            ->get();

        // Calculate late duration
        $lateText = '-';
        if (!empty($va->expired_at)) {
            $expiredDate = \Carbon\Carbon::parse($va->expired_at);
            $now = \Carbon\Carbon::now();
            if ($now->greaterThan($expiredDate)) {
                $diff = $expiredDate->diff($now);
                $months = $diff->m + ($diff->y * 12);
                $days = $diff->d;
                $parts = [];
                if ($months > 0) $parts[] = $months . ' bulan';
                if ($days > 0) $parts[] = $days . ' hari';
                $lateText = empty($parts) ? '0 hari' : implode(' ', $parts);
            } else {
                $lateText = 'Belum Jatuh Tempo';
            }
        }

        return response()->json([
            'status' => true,
            'data' => $va,
            'dispensation_details' => $dispensationDetails,
            'late_text' => $lateText
        ]);
    }

    public function evaluate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:payment_virtual_accounts,id',
            'action' => 'required|in:tolerance,re_register,closed,blocked',
        ]);

        $id = $request->input('id');
        $action = $request->input('action');

        $va_payment = PaymentVirtualAccounts::where('id', $id)->first();
        if (!$va_payment) {
            return response()->json(['status' => false, 'message' => 'Data penangguhan tidak ditemukan.'], 404);
        }

        $dispensation = PaymentDispensations::where([
            'dispensation_type' => $va_payment->type,
            'ppdb_user_id' => $va_payment->ppdb_user_id
        ])->orderBy('id', 'desc')->first();

        if ($dispensation) {
            // Khusus evaluasi 'tolerance' untuk uang kegiatan (activity), status dispensasi tidak diubah
            $isActivityTolerance = ($action === 'tolerance' && $va_payment->type === PaymentDispensations::DISPENSATION_TYPE_ACTIVITY);
            if (!$isActivityTolerance) {
                $dispensation->status = PaymentDispensations::STATUS_CANCELLED;
                $dispensation->save();
            } else {
                // Khusus toleransi uang kegiatan:
                // Set plan_date = null untuk detail yang statusnya 'unpaid', KECUALI cicilan ke-1 (installment_number = 1)
                \App\Models\PaymentDispensationDetails::where('payment_dispensation_id', $dispensation->id)
                    ->where('status', \App\Models\PaymentDispensationDetails::STATUS_UNPAID)
                    ->where('installment_number', '>', 1)
                    ->update(['plan_date' => null]);
            }
        }

        $status = ($action === 'tolerance' || $action === 'closed') ? 'closed' : 'blocked';

        if ($action === 'tolerance') {
            $ppdb_user = \App\Models\PPDBUser::find($va_payment->ppdb_user_id);
            if ($ppdb_user) {
                $ppdb_user->payment_tolerance_expired_at = now()->addDays(7);
                $ppdb_user->save();
            }
        }

        $updated = DB::table('payment_virtual_accounts')
            ->where('id', $id)
            ->update([
                'status' => $status,
                'updated_at' => now(),
            ]);

        if ($updated) {
            // Fetch student & user details to send notification email
            $studentData = DB::table('payment_virtual_accounts as a')
                ->select(
                    'a.id',
                    'a.virtual_account_number',
                    'd.name as student_name',
                    'd.register_number',
                    'd.payment_tolerance_expired_at',
                    'u.email as user_email',
                    'e.name as unit_name',
                    'f.name as period_name'
                )
                ->join('ppdb_users as d', 'd.id', '=', 'a.ppdb_user_id')
                ->leftJoin('users as u', 'u.id', '=', 'd.user_id')
                ->leftJoin('units as e', 'd.unit_id', '=', 'e.id')
                ->leftJoin('periods as f', 'd.periode', '=', 'f.id')
                ->where('a.id', $id)
                ->first();

            if ($studentData && !empty($studentData->user_email)) {
                try {
                    $mailable = new \App\Mail\PPDBSuspendedEvaluationMail($studentData, $status);
                    (new \App\Services\EmailService())->sendMail($mailable, $studentData->user_email);
                } catch (\Exception $e) {
                    Log::error('Failed sending PPDB suspended evaluation email: ' . $e->getMessage());
                }
            }

            $message = ($status === 'closed')
                ? 'Status berhasil diubah menjadi Closed (Toleransi Pembayaran) & email pemberitahuan telah dikirim.'
                : 'Status berhasil diubah menjadi Blocked (Pendaftaran Ulang) & email pemberitahuan telah dikirim.';

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Gagal memperbarui status.'
        ], 400);
    }
}
