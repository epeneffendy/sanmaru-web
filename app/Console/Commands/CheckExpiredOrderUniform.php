<?php

namespace App\Console\Commands;

use App\Mail\OrderConfirmationReminder;
use App\Models\ProductOrder;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredOrderUniform extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:check-expired-order-uniform';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check expired order uniform every day in three days after an order was made';

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
        $currentDateTime = Carbon::now();

        $orders = ProductOrder::where([
            'status' => 'new_order',
        ])->get();

        foreach ($orders as $order) {
            if($currentDateTime > $order->expired_at){
                $order->status = 'cancel';
                $order->payment_cancel_reason = 'Expired';
                $order->payment_cancel_date = $currentDateTime;
                $order->save();
                $this->info("cancel order confirmation  " . $order->invoice_no . " is being processed.");
            }
        }
    }
}
