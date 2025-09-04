<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ProductOrder;
use App\Models\User;

class OrderPickupNotification extends Mailable
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
            $this->unit = $order->user->student->class->unit ?? null;
        }
        $this->order = $order;
        $this->user = $order->user;
        $this->header = 'PEMBERITAHUAN PENGAMBILAN SERAGAM';
        $this->mail_cc = ['ypb.sby.osu.ppdb@gmail.com', 'seragam@sanmarosu-jatim.sch.id'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'productOrder' => $this->order,
        ];
        if ($this->user->type == User::STUDENT) {
            $data['user'] = $this->user->student;
            $pdf = \PDF::loadView('student-dashboard.shop.pdf', $data);
        } else {
            $pdf = \PDF::loadView('ppdb-online.embed.pdf', $data);
        }
        return $this->from(config('mail.from.address'), $this->unit->name ?? null)
                    ->subject("[SANMARU PPDB] Pemberitahuan Pengambilan Seragam")
                    ->markdown('emails.order-pickup-notification')
                    ->attachData($pdf->output(), 'detail-transaksi-' . $this->order->invoice_no . '.pdf');
    }
}
