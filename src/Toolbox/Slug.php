<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use Cocur\Slugify\Slugify;

/**
 * Class Slug
 *
 * @package Simtabi\Pheg\Toolbox
 */
final class Slug
{

    private function __construct() {}

    public static function invoke(string $string, $sep = '_', array $args = []): self
    {
        return (new Slugify($args))->slugify($string, $sep);
    }

}
