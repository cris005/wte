<?php

namespace App\Listeners;

use App\Events\ReversalProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendReversalNotification implements ShouldQueue
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
     * @param ReversalProcessed $event
     * @return void
     */
    public function handle(ReversalProcessed $event): void
    {
        //
    }
}
