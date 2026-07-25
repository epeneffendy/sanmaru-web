<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PPDBSuspendedEvaluationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $status;
    public $header;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $status)
    {
        $this->data = $data;
        $this->status = $status;
        $this->header = ($status === 'closed')
            ? 'Pemberitahuan Toleransi Pembayaran'
            : 'Pemberitahuan Pendaftaran Ulang';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = ($this->status === 'closed')
            ? "[SANMARU PPDB] Pemberitahuan Toleransi Pembayaran"
            : "[SANMARU PPDB] Pemberitahuan Pendaftaran Ulang";

        return $this->from(config('mail.from.address'), $this->data->unit_name ?? 'Kampus Santa Maria')
                    ->subject($subject)
                    ->markdown('emails.ppdb-suspended-evaluation');
    }
}
