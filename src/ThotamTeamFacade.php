<?php

namespace Thotam\ThotamTeam;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thotam\ThotamTeam\Skeleton\SkeletonClass
 */
class ThotamTeamFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'thotam-team';
    }
}
