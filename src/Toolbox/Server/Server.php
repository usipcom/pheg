<?php

namespace Simtabi\Pheg\Toolbox\Server;

class Server
{
    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function intel(): Intel
    {
        return Intel::invoke();
    }

    public function ip(): IP
    {
        return IP::invoke();
    }

    public function network(): Network
    {
        return Network::invoke();
    }

    public function sslToolkit(array $url = [], string $dateFormat = 'U', string $formatString = 'Y-m-d\TH:i:s\Z', ?string $timeZone = null, float $timeOut = 30): SSLToolkit
    {
        return SSLToolkit::invoke($url, $dateFormat, $formatString, $timeZone, $timeOut);
    }

}