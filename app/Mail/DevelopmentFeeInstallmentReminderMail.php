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
     * Get the readable label for the dispensation type.
     *
     * @return string
     */
    protected function getDispensationTypeLabel()
    {
        $type = optional($this->detail->dispensation)->dispensation_type;
        if ($type === 'development') {
            return 'Uang Pengembangan';
        } elseif ($type === 'activity') {
            return 'Uang Kegiatan';
        }
        return 'Uang ' . ucwords(str_replace('_', ' ', $type));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $typeLabel = $this->getDispensationTypeLabel();

        return $this->from(config('mail.from.address'), optional($this->unit_name))
                    ->subject("[SANMARU PPDB] Pengingat Jatuh Tempo Cicilan {$typeLabel} - " . $this->ppdbUser->name)
                    ->markdown('emails.development_fee_installment_reminder')
                    ->with(['typeLabel' => $typeLabel]);
    }
}
