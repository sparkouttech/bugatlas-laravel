<?php

namespace Sparkouttech\BugAtlas;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Sparkouttech\BugAtlas\Traits\ApiBugAtlas;

class BugAtlasServiceProvider extends ServiceProvider
{
    use ApiBugAtlas;

    /**
     * Bootstrap services.
     * Checks the keys(API key, secret key, or tag) are configured or not. 
     * If not function returns without further processing.
     * Otherwise, it proceeds with preparing log details based on the provided request and sends the log details.
     * @param  \Illuminate\Http\Request  $request
     * @return bool | void
     */
    public function boot(Request $request)
    {
        if (empty(config('bugatlas.api_key')) || empty(config('bugatlas.secret_key')) || empty(config('bugatlas.tag'))) {
            return;
        }
        $logDetails = $this->prepareLogDetails($request);
        $this->sendLogToApi($logDetails);
    }

    /**
     * Prepares log details for HTTP request.
     *
     * Extracts relevant details from request and response,
     * collects headers, fetches server data, formats into array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array 
     */
    private function prepareLogDetails(Request $request)
    {

        $headersObject = collect($request->header())->map(function ($value, $key) {
            return $value[0];
        });
        $response = Http::get(config("app.url"));

        return [
            "protocol" => $request->server("SERVER_PROTOCOL"),
            "request_url" => $request->fullUrl(),
            "time" => (new DateTime())->format("F jS Y, h:i:s"),
            "host_name" => gethostname(),
            "method" => $request->method(),
            "path" => $request->path(),
            "status_code" => $response->status(),
            "status_text" => $response->getReasonPhrase(),
            "ip_address" => $request->ip(),
            "memory_usage" => round(memory_get_usage(true) / (1024 * 1024), 2) . " MB",
            "user_agent" => $request->header("user-agent"),
            "headers" => $headersObject,
        ];
    }

    /**
     * Sends log details to API for storage.
     *
     * Prepares payload with log details and metadata,
     * sends it to specified endpoint for storage.
     *
     * @param array $logDetails
     * @return void
     */
    private function sendLogToApi($logDetails)
    {
        $body = [
            "request_user_agent" => $logDetails["user_agent"],
            "request_host" => $logDetails["headers"]->get('host'),
            "request_url" => $logDetails["request_url"],
            "request_method" => $logDetails["method"],
            "status_code" => $logDetails["status_code"],
            "status_message" => $logDetails["status_text"],
            "requested_at" => $logDetails["time"],
            "request_ip" => $logDetails["ip_address"],
            "response_message" => "Project created successfully",
            "protocol" => $logDetails["protocol"],
            "payload" => "Payload",
            "tag" => config('bugatlas.tag'),
            "meta" => [
                "host_name" => gethostname(),
                "path" => $logDetails["path"],
                "memory_usage" => $logDetails["memory_usage"],
                "headers" => $logDetails["headers"]->toArray(),
            ],
        ];
        $this->processApiResponse("/api/logs", $body);
    }
}
