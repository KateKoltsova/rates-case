<?php

namespace App\Listeners;

use App\Events\CurrencyRateUpdatedEvent;
use App\Jobs\SubscriberNotifyJob;
use App\Models\CurrencyRate;
use App\Models\RateSubscriber;
use App\Notifications\CurrencyRateUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
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

        RateSubscriber::query()->chunkById(100,
            function (Collection $rateSubscribersBatch) use ($usdRate) {
                $rateSubscribersBatch->map(function (RateSubscriber $rateSubscriber) use ($usdRate) {
                    dispatch(new SubscriberNotifyJob($rateSubscriber, $usdRate));
                });
            });
    }
}
