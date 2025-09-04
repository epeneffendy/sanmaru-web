<?php

namespace App\Mail;

use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\ProductOrder;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $unit;
    public $mail_cc;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProductOrder $order, array $user = null)
    {
        if ($order->user->isPPDB()) {
            $this->unit = $order->user->ppdb->unit;
        }
        if ($order->user->isStudent()) {
            $this->unit = $order->user->student->class ? $order->user->student->class->unit : null;
        }
        
        $this->user = $order->user;
        $this->order = $order;
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
                    ->subject("[SANMARU PPDB] Pemberitahuan Pembelian Seragam")
                    ->markdown('emails.order-confirmation');
    }
}
