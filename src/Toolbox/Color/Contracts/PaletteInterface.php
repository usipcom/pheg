<?php

namespace Simtabi\Pheg\Toolbox\Color\Contracts;

interface PaletteInterface
{

    /**
     * @return array
     */
    public static function getColors();

    /**
     * @return void
     */
    public static function setColors(array $colors);
}








