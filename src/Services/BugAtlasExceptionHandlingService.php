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
            // Report the exception to BugAtlas
            $this->bugAtlasReporter->report($request, $exception);
        }

        // Let the parent handler render the exception
        return parent::render($request, $exception);
    }

    /**
    * Determine if the exception should be handled by BugAtlas.
    *
    * @param \Throwable $exception
    * @return bool
    */

    protected function shouldHandleException(Throwable $exception): bool
    {
        // Get the class name of the exception
        $exceptionName = get_class($exception);

        // Check if the exception should be handled based on its class name
        switch($exceptionName){
            // List of exceptions to be handled by BugAtlas
            case "SyntaxError":
            case "TypeError":
            case "FileNotFoundError":
            case "AttributeError":
            case "ZeroDivisionError":
            case "ValidationException":
            case "ModelNotFoundException":
            case "MethodNotAllowedHttpException":
            case "NotFoundHttpException":
            case "QueryException":
            case "AuthorizationException":
            case "AuthenticationException":
            case "FileNotFoundException":
            case "HttpException":
            case "TokenMismatchException":
            case "ServiceNotFoundException":
            case "ThrottleRequestsException":
            case "BadMethodCallException":
            case "PDOException":
            case "ConnectException":
            case "PermissionDeniedException":
            case "ReflectionException":
            case "BindingResolutionException": 
            case "InvalidArgumentException":
            case "OutOfBoundsException":
            case "ConnectionException":
            case "ErrorException":        
            case "SuspiciousOperationException":
            case "FatalThrowableError":
            case "RuntimeException":

            // Return true if the exception should be handled
            return true;
            break;
            
            // Default case: return false if the exception should not be handled
            default:
            return false;
        }
    }
}