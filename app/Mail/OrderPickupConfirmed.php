<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ProductOrder;
use App\Models\User;

class OrderPickupConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $unit;
    public $header;
    public $mail_cc;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProductOrder $order)
    {
        if ($order->user->isPPDB()) {
            $this->unit = $order->user->ppdb->unit;
        }
        if ($order->user->isStudent()) {
            $this->unit = $order->user->student->class ? $order->user->student->class->unit : null;
        }
        $this->order = $order;
        $this->user = $order->user;
        $this->header = 'BUKTI PENGAMBILAN SERAGAM';
        $this->mail_cc = ['ypb.sby.osu.ppdb@gmail.com'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), $this->unit->name ?? null)
                    ->subject("[SANMARU PPDB] Konfirmasi Pengambilan Seragam")
                    ->markdown('emails.order-pickup-received');
    }
}
