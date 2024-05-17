<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('APP_URL') . '/api/',
        ]);
    }

    public function addEmail(Request $request)
    {
        try {
            $data = request()->all();

            foreach ($data as $key => $value) {
                $formData['multipart'][] = [
                    'name' => $key,
                    'contents' => $value
                ];
            }

            $response = $this->client->post('subscribe',
                [
                    'multipart' => $formData['multipart'],
                ]);

            $response = json_decode($response->getBody(), true);
            $message = $response['message'];

            return view('welcome', compact('message'));

        } catch (RequestException $e) {
            $response = json_decode($e->getResponse()->getBody(), true);

            if (isset($response['message'])) {
                $fails['email'][] = $response['errors'];
                return redirect()->route('welcome')->withErrors($fails)->withInput();
            }

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
