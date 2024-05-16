<?php

namespace Sparkouttech\BugAtlas\Services;

use App\Exceptions\Handler as BaseHandler;
use Throwable;
use Sparkouttech\BugAtlas\Services\BugAtlasReporterService;

class BugAtlasExceptionHandlingService extends BaseHandler
{
    protected $bugAtlasReporter;

    /**
     * Constructs a new instance of the class.
     *
     * Initializes the BugAtlas reporter service.
     *
     * @param BugAtlasReporterService $bugAtlasReporter The BugAtlas reporter service
     */

    public function __construct(BugAtlasReporterService $bugAtlasReporter)
    {
        $this->bugAtlasReporter = $bugAtlasReporter;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * Reports the exception to BugAtlas if it should be handled.
     *
     * @param \Illuminate\Http\Request $request The HTTP request
     * @param \Throwable $exception The exception to be rendered
     * @return \Symfony\Component\HttpFoundation\Response The HTTP response
     */

    public function render($request, Throwable $exception)
    {
        $this->bugAtlasReporter->report($request, $exception);
        return parent::render($request, $exception);
    }
}