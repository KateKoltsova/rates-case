<?php

namespace App\Services;

use Exception;

class MonoRates extends RatesAbstract
{
    protected $url = 'https://api.monobank.ua/bank/currency';

    protected function getUsdToUah()
    {
        foreach ($this->response as $currency) {
            if ($currency['currencyCodeA'] == 840 && $currency['currencyCodeB'] == 980) {
                return (float)$currency['rateBuy'];
            }
        }

        throw new Exception('Currency rate not found', 404);
    }
}
