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
        // Target date is 3 days from now
        $targetDate = Carbon::now()->addDays(3)->toDateString();

        $units = Unit::get();

        if ($units->isEmpty()) {
            $this->info("No units found.");
            return;
        }

        $sentCount = 0;

        foreach ($units as $unit) {
            $this->info("Processing Unit ID: {$unit->id} ({$unit->name})");

            // Get all PPDB students with emails and matching unit
            PPDBUser::where('unit_id', $unit->id)
                ->where('status', PPDBUser::STATUS_SUBMITTED)
                ->where('period_verified', PPDBUser::PERIOD_VERIFIED)
                ->with('user')
                ->chunk(100, function ($students) use ($targetDate, &$sentCount) {
                    foreach ($students as $student) {
                        $periodePayment = PriceHelper::getDatePeriodePayment($student, 'activity');

                        $startDate = !empty($periodePayment['start'])
                            ? Carbon::parse($periodePayment['start'])->toDateString()
                            : null;

                        // Only send reminder if the payment period start date matches targetDate (3 days before opening)
                        if (!$startDate || $startDate !== $targetDate) {
                            continue;
                        }

                        $email = optional($student->user)->email;
                        if (!$email) {
                            continue;
                        }

                        try {
                            Mail::to($email)->queue(new PaymentPeriodReminderMail($student, $periodePayment));
                            $sentCount++;
                            $this->info("Queued reminder email for student ID {$student->id} ({$email}) for period starting on {$startDate}.");
                        } catch (\Exception $e) {
                            Log::error("Failed to send payment period reminder to {$email}: " . $e->getMessage());
                        }
                    }
                });
        }

        $this->info("Payment period reminders processing complete. Total emails queued: {$sentCount}.");
    }
}
