<?php

namespace App\Mail;

use App\Models\PaymentDispensations;
use App\Models\PaymentVirtualAccounts;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\ProductOrder;
use App\Models\User;

class BillPaymentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $unit;
    public $dispensation;
    public $header;
    public $tagihan;
    public $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PaymentDispensations $dispensation, PaymentVirtualAccounts $paymentVirtualAccount)
    {
        $this->dispensation = $dispensation;
        $this->tagihan = $paymentVirtualAccount;
        $this->user = $this->dispensation->ppdb;
        $this->unit = $this->dispensation->ppdb->unit;
        $this->title = $this->dispensationType($this->dispensation->dispensation_type);
        $this->title = $this->dispensationType($this->dispensation->dispensation_type);
        $this->header = 'Bukti Pembayaran '. $this->dispensationType($this->dispensation->dispensation_type);
    }

    public function dispensationType($type){
        switch($type){
            case 'development':
                return 'Uang Pengembangan';
                break;
            case 'activity':
                return 'Uang Kegiatan';
                break;
            default:
                return '';
                break;
        }

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), $this->unit->name ?? 'Kampus Santa Maria')
                    ->subject("[SANMARU SPMB] Konfirmasi pembayaran". $this->header)
                    ->markdown('emails.bill-payment-confirmed');
    }

}
