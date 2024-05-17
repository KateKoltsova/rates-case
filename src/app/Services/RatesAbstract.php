<?php

namespace App\Services;

use App\Events\CurrencyRateUpdatedEvent;
use App\Models\CurrencyRate;
use Exception;
use Illuminate\Support\Facades\Http;

abstract class RatesAbstract
{
    protected $response = [];
    protected $url;

    public function __construct()
    {
        $i = 0;
        do {
            $response = Http::get($this->url);

            if ($response->successful()) {
                $this->response = $response->json();
                $i = 3;
            } else {
                $i++;
                sleep(60);
            }

        } while ($i < 3);

        if (empty($this->response)) {
            throw new Exception('Error request to PrivatBank API', $this->response->status());
        }
    }

    public function getRates()
    {
        $usdRate = $this->getUsdToUah();

        return $usdRate;
    }

    abstract protected function getUsdToUah();

    public function saveUsdRate($usdRate)
    {
        $rate = CurrencyRate::create([
            'currency' => 'USD',
            'rate_to_uah' => $usdRate,
        ]);

        if ($rate) {
            event(new CurrencyRateUpdatedEvent($usdRate));
        }
    }

    public function getLastRate()
    {
        $lastUsdRate = CurrencyRate::latest('created_at')->first();

        return $lastUsdRate['rate_to_uah'];
    }

}
