<?php

namespace App\Events;

use App\Models\Tender;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TenderImported
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tenders;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $tenders)
    {
        $this->tenders = $tenders;
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
