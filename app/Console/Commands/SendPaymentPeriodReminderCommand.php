<?php

namespace App\Console\Commands;

use App\Helpers\PriceHelper;
use Illuminate\Console\Command;
use App\Models\FinancePeriode;
use App\Models\Student;
use App\Mail\PaymentPeriodReminderMail;
use App\Models\PPDBUser;
use App\Models\Unit;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendPaymentPeriodReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:payment-period-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder email to students 3 days before payment period opens.';

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
        // Target date is exactly 3 days from now
        $targetDate = Carbon::now()->addDays(1)->toDateString();
        
        $units = Unit::get();

        if ($units->isEmpty()) {
            $this->info("No payment unit starting on {$targetDate}.");
            return;
        }

        foreach ($units as $unit) {
            $this->info("Processing Unit ID: {$unit->id} ({$unit->name})");
            
            // Get all PPDB students with emails and matching unit
            \App\Models\PPDBUser::where('unit_id', $unit->id)
                ->where('status', \App\Models\PPDBUser::STATUS_SUBMITTED)
                ->where('period_verified', \App\Models\PPDBUser::PERIOD_VERIFIED)
                ->with('user')
                ->chunk(100, function($students) use ($unit) {
                    foreach ($students as $student) {
                        $ppdb = PPDBUser::find($student->id);
                        $periodePayment = PriceHelper::getDatePeriodePayment($ppdb, 'activity');

                        $email = optional($student->user)->email;
                        if (!$email) continue;

                        try {
                            Mail::to($email)->queue(new PaymentPeriodReminderMail($student, $periodePayment));
                        } catch (\Exception $e) {
                            Log::error("Failed to send payment period reminder to {$email}: " . $e->getMessage());
                        }
                    }
                });
        }
        
        $this->info('Payment period reminders have been processed successfully.');
    }
}
