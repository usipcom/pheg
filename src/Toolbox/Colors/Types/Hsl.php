<?php

namespace Simtabi\Pheg\Toolbox\Colors\Types;

use Simtabi\Pheg\Core\Exceptions\PhegException;
use Simtabi\Pheg\Toolbox\Colors\Traits\WithHslTrait;

class Hsl extends BaseFactory
{
    use WithHslTrait;

    /**
     * @param string $color
     *
     * @return array
     */
    protected function initialize($color)
    {
        return [$this->hue, $this->saturation, $this->lightness] = explode(',', $color);
    }

    /**
     * @return array
     */
    public function values()
    {
        return $this->getValues();
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
        return $this->toHex()->toHexa();
    }

    /**
     * @return Hsl
     */
    public function toHsl()
    {
        return $this;
    }

    /**
     * @throws PhegException
     * @return Hsla
     */
    public function toHsla()
    {
        return new Hsla(implode(',', array_merge($this->values(), [1.0])));
    }

    /**
     * Source: https://en.wikipedia.org/wiki/HSL_and_HSV#Interconversion
     *
     * @throws PhegException
     * @return Hsv
     */
    public function toHsv()
    {
        [$h, $s, $l] = $this->valuesInUnitInterval();
        $v = $s * min($l, 1 - $l) + $l;
        $s = $v ? 2 * (1 - $l / $v) : 0;
        $code = implode(',', [round($h * 360), round($s * 100), round($v * 100)]);
        return new Hsv($code);
    }

    /**
     * @throws PhegException
     * @return Rgb
     */
    public function toRgb()
    {
        return $this->convertToRgb();
    }

    /**
     * @throws PhegException
     * @return Rgba
     */
    public function toRgba()
    {
        return $this->toRgb()->toRgba();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'hsl(' . implode(',', $this->values()) . ')';
    }
}
