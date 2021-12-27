<?php

namespace Simtabi\Pheg\Toolbox\Colors\Types;

use Simtabi\Pheg\Core\Exceptions\PhegException;
use Simtabi\Pheg\Toolbox\Colors\Helpers\DefinedColor;
use Simtabi\Pheg\Toolbox\Colors\Traits\WithAlphaTrait;
use Simtabi\Pheg\Toolbox\Colors\Traits\WithRgbTrait;

class Hexa extends BaseFactory
{
    use WithAlphaTrait;
    use WithRgbTrait;

    /**
     * @param string $code
     *
     * @return string|bool
     */
    protected function validate($code)
    {
        $color = str_replace('#', '', DefinedColor::find($code));
        return preg_match('/^[a-f0-9]{6}([a-f0-9]{2})?$/i', $color) ? $color : false;
    }

    /**
     * @param string $color
     *
     * @return array
     */
    protected function initialize($color)
    {
        [$this->red, $this->green, $this->blue, $this->alpha] = array_merge(str_split($color, 2), ['ff']);
        $this->alpha = $this->alphaHexToFloat($this->alpha ?? 'ff');
        return $this->values();
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
     * @return Hex
     * @throws PhegException
     */
    public function toHex()
    {
        return new Hex(implode([$this->red(), $this->green(), $this->blue()]));
    }

    /**
     * @return Hexa
     */
    public function toHexa()
    {
        return $this;
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
     * @return Hsla
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
     * @throws PhegException
     * @return Rgb
     */
    public function toRgb()
    {
        $rgb = implode(',', array_map('hexdec', [$this->red(), $this->green(), $this->blue()]));
        return new Rgb($rgb);
    }

    /**
     * @throws PhegException
     * @return Rgba
     */
    public function toRgba()
    {
        return $this->toRgb()->toRgba()->alpha($this->alpha());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        [$r, $g, $b, $a] = $this->values();
        return '#' . implode('', [$r, $g, $b, $this->alphaFloatToHex($a)]);
    }
}
