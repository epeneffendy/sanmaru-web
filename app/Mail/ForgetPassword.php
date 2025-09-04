<?php

namespace App\Mail;

use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\User;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("[SANMARU PPDB] Permintaan reset password")->markdown('emails.forget-password');
    }
}
