<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $unit_name;
    public $notification;
    public $header;
    public $mail_cc;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $notification)
    {
        $this->user = $user;
        $this->notification = $notification;
        $this->unit_name = $user->unit_name;
        $this->header = $this->notification->data['title'];
        $this->mail_cc = ['ypb.sby.osu.ppdb@gmail.com'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), optional($this->unit_name))
                    ->subject("[SANMARU PPDB] " . $this->notification->data['title'])
                    ->markdown('emails.notification-email');
    }
}
