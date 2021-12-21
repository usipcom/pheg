<?php

namespace Simtabi\Pheg\Toolbox\Color;

use Simtabi\Pheg\Toolbox\Color\Types\Factory;
use Simtabi\Pheg\Toolbox\Color\Types\Hex;
use Simtabi\Pheg\Toolbox\Color\Types\Hexa;
use Simtabi\Pheg\Toolbox\Color\Types\Hsl;
use Simtabi\Pheg\Toolbox\Color\Types\Hsla;
use Simtabi\Pheg\Toolbox\Color\Types\Hsv;
use Simtabi\Pheg\Toolbox\Color\Types\Rgb;
use Simtabi\Pheg\Toolbox\Color\Types\Rgba;

class ClassLoader
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