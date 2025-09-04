<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\PPDB\DevelopmentStatementConfirmed' => [
            'App\Listeners\PPDB\SyncDevelopmentFinance',
        ],
        'App\Events\PPDB\FinanceActivityUpdated' => [
            'App\Listeners\PPDB\SyncFinanceActivity',
        ],
        'App\Events\PPDB\FinanceFormPaymentImported' => [
            'App\Listeners\PPDB\SyncFinanceForm',
        ],
        'App\Events\PPDB\FinanceUniformPaymentImported' => [
            'App\Listeners\PPDB\SyncFinanceUniform',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
