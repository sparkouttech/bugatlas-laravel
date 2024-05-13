<?php

namespace Sparkouttech\BugAtlas\Services;

use App\Exceptions\Handler as BaseHandler;
use Throwable;
use Sparkouttech\BugAtlas\Services\BugAtlasReporterService;

class BugAtlasExceptionHandlingService extends BaseHandler
{
    protected $bugAtlasReporter;

    /**
    * Create a new BugAtlasExceptionHandlingService instance.
    *
    * @param mixed $app
    * @param \Sparkouttech\BugAtlas\Services\BugAtlasReporterService $bugAtlasReporter
    * @return void
    */

    public function __construct($app, BugAtlasReporterService $bugAtlasReporter)
    {
        // Call the parent constructor
        parent::__construct($app);

        // Inject the BugAtlasReporterService instance
        $this->bugAtlasReporter = $bugAtlasReporter;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function render($request, Throwable $exception)
    {
        // Check if the exception should be handled by BugAtlas
        if ($this->shouldHandleException($exception)) {
            // Report the exception to BugAtlas for monitoring and debugging purposes
            $this->bugAtlasReporter->report($request, $exception);
        }
        return parent::render($request, $exception);
    }

    /**
    * Determine if the exception should be handled by BugAtlas.
    *
    * @param \Throwable $exception The exception to be evaluated
    * @return bool Returns true if the exception should be handled by BugAtlas, otherwise false
    */

    protected function shouldHandleException(Throwable $exception): bool
    {
        // handle exceptions by BugAtlas
        return true;
    }
}