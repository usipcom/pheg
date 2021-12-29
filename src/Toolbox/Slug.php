<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use Cocur\Slugify\Slugify;
use Stringy\Stringy as S;

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

    /**
     * Converts all accent characters to ASCII characters.
     * If there are no accent characters, then the string given is just returned.
     *
     * @param string $string Text that might have accent characters
     * @return string Filtered  string with replaced "nice" characters
     */
    public function removeAccents(string $string): string
    {
        return (S::create($string))->toTransliterate();
    }
}
