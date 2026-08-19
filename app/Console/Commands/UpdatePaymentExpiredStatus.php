<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentBills;
use App\Models\PPDBUser;
use App\Helpers\PriceHelper;
use Illuminate\Support\Facades\Log;

class UpdatePaymentExpiredStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ppdb:update-payment-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payment_expired_at in ppdb_users for students with unpaid development or activity bills that exceeded period_end';

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
        $userToUpdateIds = [];

        // Ambil tagihan 'development' dan 'activity' dengan status 'unpaid'
        $unpaidBills = StudentBills::whereIn('type', [
                StudentBills::BILL_TYPE_DEVELOPMENT,
                StudentBills::BILL_TYPE_ACTIVITY
            ])
            ->where('payment_method', StudentBills::PAYMENT_METHOD_UNPAID)
            ->whereNotNull('ppdb_user_id')
            ->with('ppdb')
            ->get();

        foreach ($unpaidBills as $bill) {
            $ppdb = $bill->ppdb;
            if (!$ppdb) {
                continue;
            }

            // Dapatkan periode_end untuk tipe tagihan ('activity' / 'development')
            $periodEnd = PriceHelper::getPeriodFinance($ppdb, $bill->type);

            // Jika periode_end ditentukan, periksa apakah waktu sekarang sudah melebihi periode_end tersebut
            if ($periodEnd !== null) {
                if ($now->greaterThan($periodEnd)) {
                    $userToUpdateIds[] = $ppdb->id;
                }
            } else {
                // Jika tidak ada periode_end spesifik, tetap masukkan jika unpaid
                $userToUpdateIds[] = $ppdb->id;
            }
        }

        $userToUpdateIds = array_values(array_unique($userToUpdateIds));

        if (empty($userToUpdateIds)) {
            $this->info("Tidak ada siswa dengan tagihan development/activity unpaid yang melebihi periode_end.");
            return;
        }

        // Update kolom payment_expired_at di tabel ppdb_users
        $updatedCount = PPDBUser::whereIn('id', $userToUpdateIds)
            ->update([
                'payment_expired_at' => $now
            ]);

        $message = "[Cronjob] Berhasil memperbarui payment_expired_at untuk {$updatedCount} siswa ppdb_users pada {$now}.";
        $this->info($message);
        Log::info($message);
    }
}
