<?php

use Cocur\Slugify\Slugify;
use Cocur\Slugify\RuleProvider\RuleProviderInterface;

if (!function_exists('slugify')) {
    function slugify($string, $options = null, $classOptions = [], RuleProviderInterface $provider = null): ?string
    {
        return (new Slugify($classOptions, $provider))->slugify($string, $options);
    }
}