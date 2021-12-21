<?php

namespace Simtabi\Pheg\Toolbox\Color\Types;

use Simtabi\Pheg\Toolbox\Color\BaseColor;
use Simtabi\Pheg\Core\Exceptions\PhegException;
use Simtabi\Pheg\Toolbox\Color\Helpers\DefinedColor;
use Simtabi\Pheg\Toolbox\Traits\Color\WithAlphaTrait;
use Simtabi\Pheg\Toolbox\Traits\Color\WithRgbTrait;

class Rgba extends BaseColor
{
    use WithAlphaTrait;
    use WithRgbTrait;

    /**
     * @var Rgb
     */
    protected $background;

    /**
     * @param string $code
     *
     * @return bool|mixed|string
     */
    protected function validate($code)
    {
        $color = str_replace(['rgba', '(', ')', ' '], '', DefinedColor::find($code, 1));
        if (substr_count($color, ',') === 2) {
            $color = "{$color},1.0";
        }
        $color = $this->fixPrecision($color);
        if (preg_match($this->validationRules(), $color, $matches)) {
            if ($matches[1] > 255 || $matches[2] > 255 || $matches[3] > 255 || $matches[4] > 1) {
                return false;
            }
            return $color;
        }
        return false;
    }

    /**
     * @param string $color
     *
     * @return void
     * @throws PhegException
     */
    protected function initialize($color)
    {
        $colors = explode(',', $color);
        [$this->red, $this->green, $this->blue] = array_map('intval', $colors);
        $this->alpha = (double) $colors[3];
        $this->background = $this->defaultBackground();
    }

    /**
     * @return array
     */
    public function values()
    {
        return [
            $this->red(),
            $this->green(),
            $this->blue(),
            $this->alpha()
        ];
    }

    /**
     * @return Rgb
     *@throws PhegException
     */
    public function toRgb()
    {
        [$red, $green, $blue] = array_map(function ($attribute) {
            $value = (1 - $this->alpha()) * $this->background->{$attribute}() + $this->alpha() * $this->{$attribute}();
            return floor($value);
        }, ['red', 'green', 'blue']);
        return new Rgb(implode(',', [$red, $green, $blue]));
    }

    /**
     * @return Rgba
     */
    public function toRgba()
    {
        return $this;
    }

    /**
     * @throws PhegException
     * @return Hex
     */
    public function toHex()
    {
        return $this->toRgb()->toHex();
    }

    /**
     * @throws PhegException
     * @return Hexa
     */
    public function toHexa()
    {
        return $this->toRgb()->toHex()->toHexa()->alpha($this->alpha());
    }

    /**
     * @throws PhegException
     * @return Hsl
     */
    public function toHsl()
    {
        return $this->toRgb()->toHsl();
    }

    /**
     * @throws PhegException
     * @return Hsla|float
     */
    public function toHsla()
    {
        return $this->toHsl()->toHsla()->alpha($this->alpha());
    }

    /**
     * @throws PhegException
     * @return Hsv
     */
    public function toHsv()
    {
        return $this->toRgb()->toHsv();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'rgba(' . implode(',', $this->values()) . ')';
    }

    /**
     * @param Rgb $rgb
     *
     * @return $this
     */
    public function background(Rgb $rgb)
    {
        $this->background = $rgb;
        return $this;
    }

    /**
     * @return Rgb
     * @throws PhegException
     */
    protected function defaultBackground()
    {
        return new Rgb('255,255,255');
    }
}
