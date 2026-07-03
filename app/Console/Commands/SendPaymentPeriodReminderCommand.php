<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FinancePeriode;
use App\Models\Student;
use App\Mail\PaymentPeriodReminderMail;
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
        $targetDate = Carbon::now()->addDays(3)->toDateString();
        
        // Find periods starting on target date specifically for 'activity' (Uang Kegiatan)
        $periods = FinancePeriode::where('type', 'activity')->whereDate('start_date', $targetDate)->get();

        if ($periods->isEmpty()) {
            $this->info("No payment periods starting on {$targetDate}.");
            return;
        }

        foreach ($periods as $periode) {
            $this->info("Processing period ID: {$periode->id} (Starts: {$periode->start_date})");
            
            // Get all PPDB students with emails and matching unit
            \App\Models\PPDBUser::where('unit_id', $periode->unit_id)
                ->where('status', \App\Models\PPDBUser::STATUS_SUBMITTED)
                ->with('user')
                ->chunk(100, function($students) use ($periode) {
                    foreach ($students as $student) {
                        $email = optional($student->user)->email;
                        if (!$email) continue;

                        try {
                            Mail::to($email)->queue(new PaymentPeriodReminderMail($student, $periode));
                        } catch (\Exception $e) {
                            Log::error("Failed to send payment period reminder to {$email}: " . $e->getMessage());
                        }
                    }
                });
        }
        
        $this->info('Payment period reminders have been processed successfully.');
    }
}
