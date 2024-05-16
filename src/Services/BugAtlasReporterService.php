<?php

namespace Sparkouttech\BugAtlas\Services;

use Throwable;
use Illuminate\Http\Request;
use Sparkouttech\BugAtlas\Traits\ApiBugAtlas;

class BugAtlasReporterService
{
    use ApiBugAtlas;

    /**
     * Reports exception to BugAtlas.
     *
     * Sends request and exception details to BugAtlas API.
     *
     * @param mixed $request The HTTP request
     * @param \Throwable $exception The exception to report
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
