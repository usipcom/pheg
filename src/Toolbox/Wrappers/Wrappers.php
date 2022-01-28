<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Wrappers;

use Simtabi\Pheg\Toolbox\Wrappers\Guzzle\GuzzleApiCallBuilder;

class Wrappers
{
    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public static function guzzle(string $url, string $uri, string $method = GuzzleApiCallBuilder::HTTP_POST): GuzzleApiCallBuilder
    {
        return GuzzleApiCallBuilder::invoke($url, $uri, $method);
    }
}