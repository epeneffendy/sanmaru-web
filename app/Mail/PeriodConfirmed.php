<?php

namespace App\Mail;

use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\PPDBUser;

class PeriodConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $unit;
    public $ppdb;
    public $header;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PPDBUser $ppdb)
    {
        $this->ppdb = $ppdb;
        $this->user = $this->ppdb->user;
        $this->unit = $this->ppdb->unit;
        $this->header = 'Verifikasi Seleksi Administrasi';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), $this->unit->name ?? 'Kampus Santa Maria')
                    ->subject("[SANMARU SPMB] Konfirmasi Seleksi Administrasi")
                    ->markdown('emails.period-confirmed');
    }

}
