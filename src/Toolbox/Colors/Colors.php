<?php

namespace Simtabi\Pheg\Toolbox\Colors;

use Simtabi\Pheg\Toolbox\Colors\Helpers\Factory;
use Simtabi\Pheg\Toolbox\Colors\Types\Hex;
use Simtabi\Pheg\Toolbox\Colors\Types\Hexa;
use Simtabi\Pheg\Toolbox\Colors\Types\Hsl;
use Simtabi\Pheg\Toolbox\Colors\Types\Hsla;
use Simtabi\Pheg\Toolbox\Colors\Types\Hsv;
use Simtabi\Pheg\Toolbox\Colors\Types\Rgb;
use Simtabi\Pheg\Toolbox\Colors\Types\Rgba;
use Simtabi\Pheg\Toolbox\Colors\Helpers\Color;
use Simtabi\Pheg\Toolbox\Colors\Helpers\Interpreter;
use Simtabi\Pheg\Toolbox\Colors\Helpers\Palettes;
use Simtabi\Pheg\Toolbox\Colors\Helpers\Picker;

class Colors
{

    public static function invoke(): self
    {
        return new self();
    }

    public function getInterpreter(): Interpreter
    {
        return Interpreter::invoke();
    }

    public function getPalettes(): Palettes
    {
        return Palettes::invoke();
    }

    public function getPicker(array $palette = []): Picker
    {
        return Picker::invoke($palette);
    }

    public function getColor($intColor = null): Color
    {
        // @todo refactor this, and replace with the below
        return Color::invoke($intColor);
    }



    //


    public function getFactory($color): Factory
    {
        return Factory::init($color);
    }

    public function getHex($color): Hex
    {
        return new Hex($color);
    }

    public function getHexa($color): Hexa
    {
        return new Hexa($color);
    }

    public function getHsl($color): Hsl
    {
        return new Hsl($color);
    }

    public function getHsla($color): Hsla
    {
        return new Hsla($color);
    }

    public function getHsv($color): Hsv
    {
        return new Hsv($color);
    }

    public function getRgb($color): Rgb
    {
        return new Rgb($color);
    }

    public function getRgba($color): Rgba
    {
        return new Rgba($color);
    }

}