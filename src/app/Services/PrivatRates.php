<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class PrivatRates extends RatesAbstract
{
    protected $url = 'https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=5';

    protected function getUsdToUah()
    {
        foreach ($this->response as $currency) {
            if ($currency['ccy'] === 'USD' && $currency['base_ccy'] === 'UAH') {
                return (float)$currency['buy'];
            }
        }

        throw new Exception('Currency rate not found', 404);
    }
}
