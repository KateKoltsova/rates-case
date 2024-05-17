<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddSubscriberRequest;
use App\Models\RateSubscriber;
use App\Services\RatesAbstract;
use Exception;

class HomeController extends Controller
{
    public function __construct(
        private RatesAbstract $ratesService
    )
    {
    }

    public function getRate()
    {
        try {
            $usdRate = $this->ratesService->getRates();
            $this->ratesService->saveUsdRate($usdRate);

            return response()->json($usdRate);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function getLastRate()
    {
        try {
            $usdRate = $this->ratesService->getLastRate();

            return response()->json($usdRate);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function subscribeEmail(AddSubscriberRequest $request)
    {
        try {
            $email = $request->get('email');

            RateSubscriber::firstOrCreate(['email' => $email]);

            return response()->json(['message' => 'E-mail successfully added']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
