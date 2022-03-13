<?php

namespace Simtabi\Pheg\Toolbox\Server;

use Simtabi\Pheg\Toolbox\Server\Entities\Intel;
use Simtabi\Pheg\Toolbox\Server\Entities\IP;
use Simtabi\Pheg\Toolbox\Server\Entities\Network;
use Simtabi\Pheg\Toolbox\Server\Entities\SSLToolkit;

class Server
{
    public function __construct() {}

    public function intel(): Intel
    {
        return new Intel();
    }

    public function ip(): IP
    {
        return new IP;
    }

    public function network(): Network
    {
        return new Network;
    }

    public function sslToolkit(array $url = [], string $dateFormat = 'U', string $formatString = 'Y-m-d\TH:i:s\Z', ?string $timeZone = null, float $timeOut = 30): SSLToolkit
    {
        return new SSLToolkit($url, $dateFormat, $formatString, $timeZone, $timeOut);
    }

}