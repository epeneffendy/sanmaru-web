<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\PaymentDispensationDetails;
use Illuminate\Support\Facades\Mail;
use App\Mail\DevelopmentFeeInstallmentReminderMail;

class DevelopmentFeeInstallmentReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:installment-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminder to parents 7 days before development fee installment due date';

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
        $targetDate = Carbon::now()->addDays(7)->toDateString();

        $this->info("Checking for installment plan_date: {$targetDate}");

        // Get all details where plan date is 7 days from now, it's not fully paid, and type is development
        $details = PaymentDispensationDetails::whereNotNull('plan_date')
            ->whereDate('plan_date', $targetDate)
            ->where('status', '!=', 'paid')
            ->whereHas('dispensation', function ($query) {
                $query->where('dispensation_type', 'development');
            })
            ->with(['dispensation.ppdb.user'])
            ->get();

        $count = 0;

        foreach ($details as $detail) {
            $dispensation = $detail->dispensation;
            if (!$dispensation) {
                continue;
            }

            $ppdbUser = $dispensation->ppdb;
            if (!$ppdbUser) {
                continue;
            }

            $user = $ppdbUser->user;
            if ($user && $user->email) {
                Mail::to($user->email)->send(new DevelopmentFeeInstallmentReminderMail($detail, $ppdbUser));
                $this->info("Email sent to: {$user->email} for student: {$ppdbUser->name}");
                $count++;
            }
        }

        $this->info("Completed. Sent {$count} reminder emails.");
    }
}
