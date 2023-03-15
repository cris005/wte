<?php

namespace App\Listeners;

use App\Events\TransferProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTransferNotification implements ShouldQueue
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
     * @param TransferProcessed $event
     * @return void
     */
    public function handle(TransferProcessed $event): void
    {
        //
    }
}
