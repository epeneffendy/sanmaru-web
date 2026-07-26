<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PaymentDispensationDetails;
use App\Models\PPDBUser;

class OverdueBillNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $detail;
    public $ppdbUser;
    public $unit_name;
    public $typeLabel;

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
        $this->typeLabel = $this->getDispensationTypeLabel();
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
        return $this->from(config('mail.from.address'), $this->unit_name)
                    ->subject("[SANMARU PPDB] Pemberitahuan Tunggakan {$this->typeLabel} - " . $this->ppdbUser->name)
                    ->markdown('emails.overdue_bill_notification')
                    ->with([
                        'ppdb' => $this->ppdbUser,
                        'unit' => $this->ppdbUser->unit,
                    ]);
    }
}
