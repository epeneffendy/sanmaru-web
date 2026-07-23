<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PPDBUser;
use App\Models\FinancePeriode;

class PaymentPeriodReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $periode;
    public $unit_name;
    public $unit;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PPDBUser $student, $periode)
    {
        $this->student = $student;
        $this->periode = $periode;
        $this->unit = $student->unit;
        $this->unit_name = $this->unit ? $this->unit->name : 'Sanmaru';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), 'Sanmaru')
                    ->subject("[SANMARU] Pemberitahuan Periode Pembayaran Uang Kegiatan")
                    ->markdown('emails.payment-period-reminder');
    }
}
