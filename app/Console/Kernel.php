<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ExpireProductOrder::class,
        Commands\SendOrderConfirmationReminder::class,
        Commands\SendPaymentPeriodReminderCommand::class,
        Commands\ExpirePaymentVirtualAccounts::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('tools:expire-product-order')->everyMinute();
        $schedule->command('tools:send-order-confirmation-reminder')->dailyAt('07:00');
        $schedule->command('tools:check-expired-order-uniform')->everyMinute();
        $schedule->command('email:installment-reminder')->dailyAt('01:00')->appendOutputTo(storage_path('logs/cron-installment.log'));
        $schedule->command('email:payment-period-reminder')->dailyAt('02:00')->appendOutputTo(storage_path('logs/cron-payment-period.log'));
        $schedule->command('payment:expire-virtual-accounts')->dailyAt('02:00')->appendOutputTo(storage_path('logs/cron-expire-virtual-accounts.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
