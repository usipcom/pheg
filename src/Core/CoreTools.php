<?php

namespace Simtabi\Pheg\Core;

use Respect\Validation\Validator as Respect;

class CoreTools
{

    /**
     * Default region for telephone utilities
     */
    public const DEFAULT_REGION = 'KE';

    public const PHEG_DIR_PATH = __DIR__.'/../../';

    /**
     * @var string
     */
    protected static $defaultRegion = 'KE';

    public static function getRootPath(int $levels = 2){
        return dirname( __DIR__ , $levels);
    }

    public static function _e($val){
        return $val;
    }
}
