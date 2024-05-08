<?php

namespace Sparkouttech\BugAtlas\Traits;

use Illuminate\Support\Facades\Http;

trait ApiBugAtlas
{


    private $baseURL = 'https://api.bugatlas.com/v1';

    /**
     * Sends a POST request to the API endpoint with provided payload,headers. 
     * Returns the API response.
     * @param string $endPoint API endpoint.
     * @param array $payload payload to send with the request.
     * @return \Illuminate\Http\Client\Response 
     */
    public function processApiResponse($endPoint, $payload)
    {
        return Http::withHeaders($this->getApiHeaders())->post($this->baseURL . $endPoint, $payload);
    }


    /**
     * Retrieves headers for BugAtlas API requests.
     *
     * Fetches API and secret keys from config,
     * @return array API request headers.
     */
    private function getApiHeaders()
    {
        return [
            'api_key' => config('bugatlas.api_key'),
            'secret_key' => config('bugatlas.secret_key'),
            'Content-Type' => 'application/json'
        ];
    }
}

