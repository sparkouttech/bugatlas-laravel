<?php

namespace Sparkouttech\BugAtlas\Traits;

use Illuminate\Support\Facades\Http;
trait ApiBugAtlas
{

    // Base URL for BugAtlas API
    private $baseURL = 'https://api.bugatlas.com/v1';

    /**
     * Process API response by making a POST request to BugAtlas API.
     *
     * @param string $endPoint The API endpoint to send the request to
     * @param array $body The body of the request
     * @return \Illuminate\Http\Client\Response The response from the API
     */
    public function processApiResponse($endPoint, $body)
    {
        // Make a POST request to BugAtlas API with the provided endpoint and request body
         return Http::withHeaders($this->getApiHeaders())->post($this->baseURL . $endPoint, $body);
    }


    /**
     * Retrieve API headers including API key and secret key.
     *
     * @return array The headers array containing API key, secret key, and content type
     */
    private function getApiHeaders()
    {
        // Return an array containing API key, secret key, and content type for BugAtlas API requests
        return [
            'api_key' => config('bugatlas.api_key'),
            'secret_key' => config('bugatlas.secret_key'),
            'Content-Type' => 'application/json'
        ];
    }
}