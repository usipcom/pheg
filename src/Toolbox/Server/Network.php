<?php

namespace Simtabi\Pheg\Toolbox\Server;

class Network
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function isConnected($host = 'www.google.com')
    {
        return (bool) @fsockopen($host, 80, $iErrno, $sErrStr, 5);
    }
}