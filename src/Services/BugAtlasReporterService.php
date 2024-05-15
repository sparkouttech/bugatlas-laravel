<?php

namespace Sparkouttech\BugAtlas\Services;

use Throwable;
use Illuminate\Http\Request;
use Sparkouttech\BugAtlas\Traits\ApiBugAtlas;

class BugAtlasReporterService
{
    use ApiBugAtlas;

   /**
     * Report an exception to the BugAtlas service.
     *
     * This method constructs a payload containing information about the request, exception,
     * and other relevant metadata, then sends it to the BugAtlas service via an API call.
     *
     * @param mixed $request The request object contabodyg information about the HTTP request.
     * @param Throwable $exception The exception object representing the error that occurred.
     * @return void
     */

    public function report($request, Throwable $exception): void
    {
        $payload = [
            "request_url" => $request->fullUrl(),
            "request_method" => $request->method(),
            "payload" => json_encode($request->all()),
            "error_type" => get_class($exception),
            "error_message" => $exception->getMessage(),
            "tag" => config('bugatlas.tag'),
            "meta" => [
                "error_line" => $exception->getLine(),
                "stacktrace" => $exception->getTraceAsString(),
            ]
        ];
        $this->processApiResponse("/api/errors", $payload);
    }
}
