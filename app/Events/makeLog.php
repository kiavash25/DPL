<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class makeLog
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $userId;
    public $subject;
    public $referenceId;
    public $referenceTable;
    public $text;

    public function __construct($array)
    {
        $this->userId = $array['userId'];
        $this->subject = $array['subject'];
        if(isset($array['referenceId']))
            $this->referenceId = $array['referenceId'];
        if(isset($array['referenceTable']))
            $this->referenceTable = $array['referenceTable'];
        if(isset($array['text']))
            $this->text = $array['text'];
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
