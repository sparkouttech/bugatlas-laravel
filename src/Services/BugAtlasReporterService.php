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
    * @param \Illuminate\Http\Request $request
    * @param \Throwable $exception
    * @return void
    */

    public function report($request, Throwable $exception): void
    {
        // Construct the payload to be sent to BugAtlas
        $body = [
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
        // Send the payload to the BugAtlas API
        $this->processApiResponse("/api/errors", $body);
    }
}
