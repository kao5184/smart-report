<?php

namespace App\Providers;
use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\Event;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
    ];

    public function boot()
    {
        parent::boot();
        Event::listen(StatementPrepared::class, function ($event) {
            $event->statement->setFetchMode(\PDO::FETCH_ASSOC);
        });
    }
}
