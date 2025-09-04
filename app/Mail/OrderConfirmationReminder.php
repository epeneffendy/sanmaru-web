<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ProductOrder;
use App\Models\PPDBUser;
use App\Models\Unit;

class OrderConfirmationReminder extends Mailable
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
    public function __construct(ProductOrder $order, array $user=null, string $header="")
    {
        if ($order->user->isPPDB()) {
            $this->unit = $order->user->ppdb->unit;
        }
        if ($order->user->isStudent()) {
            $this->unit = $order->user->student->class ? $order->user->student->class->unit : null;
        }
        $this->user = $order->user;
        $this->order = $order;
        $this->header = $header;
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
                    ->subject("[SANMARU PPDB] Reminder Pembelian Seragam")
                    ->markdown('emails.order-confirmation');
    }
}
