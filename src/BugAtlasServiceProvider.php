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
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/bugatlas.php', 'bugatlas');
    }

    /**
     * Bootstrap services.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function boot(Request $request)
    {
        $this->publishes([
            __DIR__ . '/config/bugatlas.php' => config_path('bugatlas.php'),
        ], 'config');

        $logDetails = $this->prepareLogDetails($request);
        $this->sendLogToApi($logDetails);
    }

    /**
     * Prepare log details for a given HTTP request.
     *
     * This function extracts relevant details from the request and response
     * to prepare a log entry. It collects headers, fetches additional data
     * from the server, and formats them into an array.
     *
     * @param Request $request The HTTP request object containing request details.
     * @return array An array containing various details for logging.
     */
    private function prepareLogDetails(Request $request)
    {

        $headersObject = collect($request->header())->map(function ($value, $key) {
            return $value[0];
        });

        $response = Http::get(config("app.url"));

        // Log the details
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
     * Send log details to the API for storage.
     *
     * This function prepares a structured payload containing various log details
     * and sends it to the specified API endpoint for storage. It constructs the
     * payload based on the provided log details and additional metadata.
     *
     * @param array $logDetails An array containing various log details to be sent to the API.
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
