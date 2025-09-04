<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\OrderConfirmationReminder;
use App\Models\PPDBUser;
use App\Models\ProductOrder;
use App\Services\EmailService;
use Carbon\Carbon;

class SendOrderConfirmationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:send-order-confirmation-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send ppdb user an order confirmation reminder email every day in three days after an order was made';

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
        $max_expired_days = ProductOrder::MAX_EXPIRED_DAYS;
        $last_date = Carbon::today()->toDateTimeString();
        $now_date = Carbon::today()->addHours(24);
        $now_date = Carbon::parse($now_date)->format('Y-m-d H:i:s');

        $orders = ProductOrder::where('status', ProductOrder::STATUS_NEW_ORDER)
            ->whereBetween('created_at', [$last_date, $now_date])
            ->whereNull('payment_image')
            ->orderBy('created_at', 'asc')
            ->with('user')->get();

        $emailService = new EmailService();
        foreach ($orders as $order) {
            $template = (new OrderConfirmationReminder($order, null, "REMINDER PEMBAYARAN SERAGAM"));
            $emailService->sendMail($template, $order->user->email);
            $this->info("order confirmation mail no " . $order->invoice_no . " is being processed.");
        }
    }
}
