<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentVirtualAccounts;
use App\Models\PPDBUser;

class ExpirePaymentVirtualAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:expire-virtual-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire payment virtual accounts that have passed their expired_at date and auto-block activity DP';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = now();

        // Step 1: Expire unpaid VAs yang melewati expired_at
        $expiredAccounts = PaymentVirtualAccounts::where('status', PaymentVirtualAccounts::STATUS_UNPAID)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', $now)
            ->update([
                'status' => PaymentVirtualAccounts::STATUS_EXPIRED
            ]);

        // Step 2: Auto-block VA DP activity yang sudah expired (belum dibayar)
        $activityDpToBlock = PaymentVirtualAccounts::where('status', PaymentVirtualAccounts::STATUS_UNPAID)
            ->where('type', 'activity')
            ->whereHas('dispensationDetail', function ($q) {
                $q->where('installment_number', 0)
                  ->where('status', 'unpaid');
            })
            ->get();

        $blockedCount = 0;
        foreach ($activityDpToBlock as $va) {
            $va->status = PaymentVirtualAccounts::STATUS_EXPIRED;
            $va->save();
            $blockedCount++;

            $this->sendBlockedNotification($va);
        }

        // Step 3: Find users whose tolerance has expired
        $expiredTolerances = PPDBUser::whereNotNull('payment_tolerance_expired_at')
            ->where('payment_tolerance_expired_at', '<', $now)
            ->get();

        $expiredToleranceCount = 0;
        foreach ($expiredTolerances as $user) {
            foreach (['development', 'activity'] as $type) {
                // Check if they have an active VA for this type. If their LATEST VA is still 'closed',
                // it means they didn't create a new payment plan within the 7 days tolerance.
                $latestVA = PaymentVirtualAccounts::where('ppdb_user_id', $user->id)
                    ->where('type', $type)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($latestVA && $latestVA->status === PaymentVirtualAccounts::STATUS_CLOSED) {
                    $latestVA->status = PaymentVirtualAccounts::STATUS_EXPIRED;
                    $latestVA->save();
                    $expiredToleranceCount++;
                }
            }
            // Reset the tolerance flag so they don't get processed again
            $user->payment_tolerance_expired_at = null;
            $user->save();
        }

        $this->info("Expired {$expiredAccounts} unpaid virtual accounts.");
        $this->info("Blocked {$blockedCount} activity DP virtual accounts.");
        $this->info("Expired {$expiredToleranceCount} virtual accounts due to tolerance time limit.");
    }

    /**
     * Kirim email notifikasi saat VA activity DP di-block otomatis.
     *
     * @param PaymentVirtualAccounts $va
     * @return void
     */
    private function sendBlockedNotification(PaymentVirtualAccounts $va): void
    {
        try {
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
                ->where('a.id', $va->id)
                ->first();

            if ($studentData && !empty($studentData->user_email)) {
                $mailable = new \App\Mail\PPDBSuspendedEvaluationMail($studentData, 'blocked');
                (new \App\Services\EmailService())->sendMail($mailable, $studentData->user_email);
                Log::info("Auto-block notification sent to {$studentData->user_email} for VA #{$va->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed sending auto-block notification for VA #{$va->id}: " . $e->getMessage());
        }
    }
}
