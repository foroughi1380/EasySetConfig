<?php

namespace Gopex\EasySetConfig\facade;

use Gopex\EasySetConfig\utils\IESAccess;
use Illuminate\Support\Facades\Facade;

/**
 *
 * @mixin IESAccess
 */
class ESConfig extends Facade
{

    protected static function getFacadeAccessor()
    {
        return IESAccess::class;
    }
}
