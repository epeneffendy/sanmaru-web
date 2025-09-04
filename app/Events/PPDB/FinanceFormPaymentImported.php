<?php

namespace App\Events\PPDB;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\PPDBUser;

class FinanceFormPaymentImported
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ppdb;
    public $paymentDate;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PPDBUser $ppdb, $paymentDate)
    {
        $this->ppdb = $ppdb;
        $this->paymentDate = $paymentDate;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
