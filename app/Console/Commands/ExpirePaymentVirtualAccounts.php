<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Expire payment virtual accounts that have passed their expired_at date';

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

        $expiredAccounts = \App\Models\PaymentVirtualAccounts::where('status', \App\Models\PaymentVirtualAccounts::STATUS_UNPAID)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', $now)
            ->update([
                'status' => \App\Models\PaymentVirtualAccounts::STATUS_EXPIRED
            ]);

        $this->info("Expired {$expiredAccounts} virtual accounts.");
    }
}
