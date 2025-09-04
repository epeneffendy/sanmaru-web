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

class DevelopmentStatementConfirmed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ppdb;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PPDBUser $ppdb)
    {
        $this->ppdb = $ppdb;
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
