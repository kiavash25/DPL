<?php

namespace App\Listeners;

use App\Events\makeLog;
use App\models\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  makeLog  $event
     * @return void
     */
    public function handle(makeLog $event)
    {
        $newLog = new Log();
        $newLog->userId = $event->userId;
        $newLog->subject = $event->subject;
        $newLog->referenceId = $event->referenceId;
        $newLog->referenceTable = $event->referenceTable;
        $newLog->text = $event->text;
        $newLog->save();
    }
}
