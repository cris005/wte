<?php

namespace App\Providers;

use App\Events\ReversalProcessed;
use App\Events\TransactionCreated;
use App\Events\TransactionUpdated;
use App\Events\TransferProcessed;
use App\Events\WalletCreated;
use App\Listeners\SendReversalNotification;
use App\Listeners\SendTransferNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        TransferProcessed::class => [
            SendTransferNotification::class,
        ],
        ReversalProcessed::class => [
            SendReversalNotification::class
        ],
        TransactionCreated::class => [
            // TODO: create listener
        ],
        TransactionUpdated::class => [
            // TODO: create listeners
        ],
        WalletCreated::class => [
            // TODO: create listeners
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
