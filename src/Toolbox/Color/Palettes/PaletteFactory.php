<?php

namespace Simtabi\Pheg\Toolbox\Color\Palettes;

use Simtabi\Pheg\Toolbox\Color\Contracts\PaletteInterface;

/**
 * Class Palette
 *   A color list.
 */
class PaletteFactory implements PaletteInterface
{


    public function __construct()
    {

    }

    public static function invoke(): self
    {
        return new self();
    }

    /**
     * Array of colors i.e. ['name' => 'blue'] keyed by HEX color.
     * @var array
     */
    public static $colors = [];

    /**
     * @return array
     */
    public static function getColors(): array
    {
        return static::$colors;
    }

    /**
     * @param array $colors
     */
    public static function setColors(array $colors): void
    {
        static::$colors = $colors;
    }
}
