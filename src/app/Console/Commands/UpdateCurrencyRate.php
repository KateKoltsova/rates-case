<?php

namespace App\Console\Commands;

use App\Events\CurrencyRateUpdatedEvent;
use App\Models\CurrencyRate;
use App\Services\RatesAbstract;
use Exception;
use Illuminate\Console\Command;

class UpdateCurrencyRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency rate in database';

    public function __construct(private RatesAbstract $ratesService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $usdRate = $this->ratesService->getRates();
            $createdRate = $this->ratesService->saveUsdRate($usdRate);
            if ($createdRate) {
                event(new CurrencyRateUpdatedEvent($usdRate));
            }

            $this->info('Currency rate updated successfully');
        } catch (Exception $e) {

            $lastRate = $this->ratesService->getLastRate();
            if ($lastRate) {
                event(new CurrencyRateUpdatedEvent($lastRate));
            }

            $this->error('Failed to update currency rate: ' . $e->getMessage());
        }
    }
}
