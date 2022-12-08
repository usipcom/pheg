<?php

namespace Simtabi\Pheg\Toolbox\Server\Entities;

final class Network
{

    public function __construct() {}

    public function isConnected($host = 'www.google.com')
    {
        return (bool) @fsockopen($host, 80, $iErrno, $sErrStr, 5);
    }

}