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

    public function __construct($array){

        if(isset($array['userId']))
            $this->userId = $array['userId'];
        else if(auth()->check())
            $this->userId = auth()->user()->id;
        else
            $this->userId = 0;

        $this->subject = $array['subject'];

        if(isset($array['referenceId']))
            $this->referenceId = $array['referenceId'];

        if(isset($array['referenceTable']))
            $this->referenceTable = $array['referenceTable'];

        if(isset($array['text']))
            $this->text = $array['text'];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
                                             
