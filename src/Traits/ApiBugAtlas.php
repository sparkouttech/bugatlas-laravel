<?php

namespace Sparkouttech\BugAtlas\Traits;

use Illuminate\Support\Facades\Http;

trait ApiBugAtlas
{


    private $baseURL = 'https://api.bugatlas.com/v1';

    /**
     * Process API response for a given endpoint and payload.
     *
     * This function sends a POST request to the specified API endpoint
     * with the provided payload and retrieves the response. It sets the
     * necessary headers for the API request using the `getApiHeaders()`
     * method and constructs the full URL by appending the endpoint to
     * the base URL. The response from the API is returned.
     *
     * @param string $endPoint The endpoint of the API to send the request to.
     * @param array $payload The payload to send with the request.
     * @return \Illuminate\Http\Client\Response The response from the API.
     */
    public function processApiResponse($endPoint, $payload)
    {
        return Http::withHeaders($this->getApiHeaders())->post($this->baseURL . $endPoint, $payload);
    }


    /**
     * Retrieve headers for API requests.
     *
     * This function retrieves the required headers for making requests
     * to the BugAtlas API. It fetches the API key and secret key from
     * the application configuration and sets the content type to JSON.
     *
     * @return array An array containing the API request headers.
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

