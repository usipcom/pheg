<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Wrappers;

use Simtabi\Pheg\Toolbox\Wrappers\Guzzle\GuzzleApiCallBuilder;

final class Wrappers
{
    public function __construct() {}

    public static function guzzle(string $url, string $uri, string $method = GuzzleApiCallBuilder::HTTP_POST): GuzzleApiCallBuilder
    {
        return new GuzzleApiCallBuilder($url, $uri, $method);
    }
}