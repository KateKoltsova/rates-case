<?php

namespace App\Jobs;

use App\Models\RateSubscriber;
use App\Notifications\CurrencyRateUpdatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriberNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private RateSubscriber $rateSubscriber;
    private float $usdRate;

    /**
     * Create a new job instance.
     */
    public function __construct(RateSubscriber $rateSubscriber, float $usdRate)
    {
        $this->rateSubscriber = $rateSubscriber;
        $this->usdRate = $usdRate;
        $this->onQueue('rate_subscribers_notification');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->rateSubscriber->notify(new CurrencyRateUpdatedNotification($this->usdRate));
    }
}
