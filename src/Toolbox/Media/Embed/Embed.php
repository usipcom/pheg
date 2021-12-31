<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Media\Embed;

class Embed
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

}