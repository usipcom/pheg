<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use PHLAK\StrGen\Generator;
use PHLAK\StrGen\CharSet;

final class Password
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function generate($length = 10)
    {
        return (new Generator())->charset([CharSet::MIXED_ALPHA, CharSet::NUMERIC])->length($length)->generate();
    }
}