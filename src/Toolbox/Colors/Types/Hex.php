<?php

namespace Simtabi\Pheg\Toolbox\Colors\Types;

use Simtabi\Pheg\Core\Exceptions\PhegException;
use Simtabi\Pheg\Toolbox\Colors\Helpers\DefinedColor;
use Simtabi\Pheg\Toolbox\Colors\Traits\WithRgbTrait;

class Hex extends BaseFactory
{
    use WithRgbTrait;

    /**
     * @param string $code
     *
     * @return string|bool
     */
    protected function validate($code)
    {
        $color = str_replace('#', '', DefinedColor::find($code));
        if (strlen($color) === 3) {
            $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
        }
        return preg_match('/^[a-f0-9]{6}$/i', $color) ? $color : false;
    }

    /**
     * @param string $color
     *
     * @return array
     */
    protected function initialize($color)
    {
        return [$this->red, $this->green, $this->blue] = str_split($color, 2);
    }

    /**
     * @return Hex
     */
    public function toHex()
    {
        return $this;
    }

    /**
     * @return Hexa
     */
    public function toHexa()
    {
        return new Hexa((string)$this . 'FF');
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
        return $this->toHsl()->toHsla();
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
        $rgb = implode(',', array_map('hexdec', $this->values()));
        return new Rgb($rgb);
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
        return '#' . implode('', $this->values());
    }
}
