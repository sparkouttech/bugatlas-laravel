<?php

namespace Sparkouttech\BugAtlas;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sparkouttech\BugAtlas\Skeleton\SkeletonClass
 */
class BugAtlasFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'bug-atlas';
    }
}
