<?php
namespace App\Mail;

use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\PPDBUser;
use App\Models\User;

class PaymentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ppdbUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, PPDBUser $ppdbUser = null)
    {
        $this->user = $user;
        $this->ppdbUser = $ppdbUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("[SANMARU PPDB] Pembayaran telah diverifikasi")->markdown('emails.payment-received');
    }
}
