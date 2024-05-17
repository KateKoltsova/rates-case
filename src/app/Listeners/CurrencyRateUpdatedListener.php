<?php

namespace App\Listeners;

use App\Events\CurrencyRateUpdatedEvent;
use App\Models\CurrencyRate;
use App\Models\RateSubscriber;
use App\Notifications\CurrencyRateUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CurrencyRateUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CurrencyRateUpdatedEvent $event): void
    {
        $usdRate = $event->usdRate;

        $subscribers = RateSubscriber::all();

        foreach ($subscribers as $subscriber) {
           $subscriber->notify(new CurrencyRateUpdatedNotification($usdRate));
        }
    }
}
