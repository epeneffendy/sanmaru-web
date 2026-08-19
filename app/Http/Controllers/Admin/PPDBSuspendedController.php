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
        // Query data directly from ppdb_users where payment_expired_at is not null
        $suspendeds = DB::table('ppdb_users as d')
            ->select(
                'd.id',
                'd.register_number',
                'd.name as student_name',
                'd.payment_expired_at',
                'd.payment_expired_at as expired_at',
                'd.school_year',
                'e.name as unit_name',
                'f.name as period_name',
                DB::raw("'' as type")
            )
            ->leftJoin('units as e', 'd.unit_id', '=', 'e.id')
            ->leftJoin('periods as f', 'd.periode', '=', 'f.id')
            ->whereNotNull('d.payment_expired_at');

        // Apply search filter for student name or register number based on scope
        $search = $request->input('name') ?? $request->input('search');
        $scope = $request->input('scope', 0);

        if (!empty($search)) {
            if ($scope == '1') {
                $suspendeds->where('d.register_number', 'like', '%' . $search . '%');
            } elseif ($scope == '2') {
                $suspendeds->where('d.name', 'like', '%' . $search . '%');
            } else {
                $suspendeds->where(function ($q) use ($search) {
                    $q->where('d.name', 'like', '%' . $search . '%')
                      ->orWhere('d.register_number', 'like', '%' . $search . '%');
                });
            }
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
                'scope' => $scope,
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
            ->where('ppdb_user_id', $id)
            ->groupBy('ppdb_user_id', 'dispensation_type');

        $va = DB::table('ppdb_users as d')
            ->select(
                'd.id as ppdb_user_id',
                'a.id',
                'c.id as dispensation_detail_id',
                'b.id as payment_dispensation_id',
                'a.virtual_account_number',
                'a.type',
                'a.total_payment',
                'a.status',
                'a.expired_at',
                'd.payment_expired_at',
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
            ->leftJoin('units as e', 'd.unit_id', '=', 'e.id')
            ->leftJoin('periods as f', 'd.periode', '=', 'f.id')
            ->leftJoin('payment_virtual_accounts as a', 'a.ppdb_user_id', '=', 'd.id')
            ->leftJoinSub($bMax, 'b_max', function($join) {
                $join->on('d.id', '=', 'b_max.ppdb_user_id');
            })
            ->leftJoin('payment_dispensations as b', 'b.id', '=', 'b_max.max_id')
            ->leftJoin('payment_dispensation_details as c', function($join) {
                $join->on('c.payment_dispensation_id', '=', 'b.id')
                     ->where('c.installment_number', '=', 0);
            })
            ->where('d.id', $id)
            ->first();

        if (!$va) {
            return response()->json(['status' => false, 'message' => 'Data penangguhan tidak ditemukan.'], 404);
        }

        if (empty($va->expired_at) && !empty($va->payment_expired_at)) {
            $va->expired_at = $va->payment_expired_at;
        }
        
        $dispensationDetails = [];
        if (!empty($va->payment_dispensation_id)) {
            $dispensationDetails = DB::table('payment_dispensation_details as c')
                ->where('c.payment_dispensation_id', $va->payment_dispensation_id)
                ->select('c.installment_number', 'c.nominal', 'c.amount_paid', 'c.status', 'c.date', 'c.virtual_account')
                ->orderBy('c.installment_number', 'asc')
                ->get();
        }

        // Calculate late duration
        $lateText = '-';
        $expiredVal = !empty($va->payment_expired_at) ? $va->payment_expired_at : $va->expired_at;
        if (!empty($expiredVal)) {
            $expiredDate = \Carbon\Carbon::parse($expiredVal);
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
            'id' => 'required|integer|exists:ppdb_users,id',
            'action' => 'required|in:tolerance,re_register,closed,blocked',
        ]);

        $id = $request->input('id');
        $action = $request->input('action');

        $ppdb_user = \App\Models\PPDBUser::find($id);
        if (!$ppdb_user) {
            return response()->json(['status' => false, 'message' => 'Data pendaftar tidak ditemukan.'], 404);
        }

        $va_payment = PaymentVirtualAccounts::where('ppdb_user_id', $id)->orderBy('id', 'desc')->first();
        $va_type = $va_payment ? $va_payment->type : null;

        $dispensation = null;
        if ($va_type) {
            $dispensation = PaymentDispensations::where([
                'dispensation_type' => $va_type,
                'ppdb_user_id' => $id
            ])->orderBy('id', 'desc')->first();
        } else {
            $dispensation = PaymentDispensations::where('ppdb_user_id', $id)->orderBy('id', 'desc')->first();
        }

        if ($dispensation) {
            // Khusus evaluasi 'tolerance' untuk uang kegiatan (activity), status dispensasi tidak diubah
            $isActivityTolerance = ($action === 'tolerance' && $va_type === PaymentDispensations::DISPENSATION_TYPE_ACTIVITY);
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
            $ppdb_user->payment_tolerance_expired_at = now()->addDays(7);
            $ppdb_user->payment_expired_at = null;
            $ppdb_user->save();
        }

        if ($va_payment) {
            $va_payment->status = $status;
            $va_payment->updated_at = now();
            $va_payment->save();
        }

        // Fetch student & user details to send notification email
        $studentData = DB::table('ppdb_users as d')
            ->select(
                'd.id',
                'a.virtual_account_number',
                'd.name as student_name',
                'd.register_number',
                'd.payment_tolerance_expired_at',
                'u.email as user_email',
                'e.name as unit_name',
                'f.name as period_name'
            )
            ->leftJoin('users as u', 'u.id', '=', 'd.user_id')
            ->leftJoin('units as e', 'd.unit_id', '=', 'e.id')
            ->leftJoin('periods as f', 'd.periode', '=', 'f.id')
            ->leftJoin('payment_virtual_accounts as a', 'a.ppdb_user_id', '=', 'd.id')
            ->where('d.id', $id)
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
}
