<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\ProductOrder;
use App\Models\VoucherUsage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireProductOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:expire-product-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product order status to cancel if it\'s payment not confirmed within three days since order was made';

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
        $status_new_order = ProductOrder::STATUS_NEW_ORDER;
        $status_cancel = ProductOrder::STATUS_CANCEL;

        $max_expired_days = ProductOrder::MAX_EXPIRED_DAYS;

        $last_date = Carbon::now()->subDays($max_expired_days)->toDateTimeString();

        $orders = ProductOrder::where('status', $status_new_order)
                        ->where('created_at', '<=', $last_date)
                        ->whereNull('payment_image')
                        ->orderBy('created_at', 'asc')
                        ->get();

        foreach ($orders as $order) {
            $order->status = $status_cancel;
            $order->save();
            if ($order->voucher !== NULL) {
                VoucherUsage::where('product_order_id', $order->id)
                ->where('voucher_id', json_decode($order->voucher, TRUE)['id'])
                ->delete();
            }
            $this->info("order ".$order->invoice_no." is canceled.");
        }
    }
}

