<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PaymentDispensationDetails;
use App\Models\PPDBUser;

class DevelopmentFeeInstallmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $detail;
    public $ppdbUser;
    public $unit_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PaymentDispensationDetails $detail, PPDBUser $ppdbUser)
    {
        $this->detail = $detail;
        $this->ppdbUser = $ppdbUser;
        
        $this->unit_name = $ppdbUser->unit ? $ppdbUser->unit->name : 'Sanmaru';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), optional($this->unit_name))
                    ->subject("[SANMARU PPDB] Pengingat Jatuh Tempo Cicilan Uang Pengembangan - " . $this->ppdbUser->name)
                    ->markdown('emails.development_fee_installment_reminder');
    }
}
