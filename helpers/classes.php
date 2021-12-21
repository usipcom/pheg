<?php

use Simtabi\Pheg\Pheg;

if (!function_exists('pheg')) {
    function pheg()
    {
        return Pheg::getInstance();
    }
}