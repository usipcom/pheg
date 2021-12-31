<?php
declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Media\Exif\CameraData;

use Simtabi\Pheg\Toolbox\Media\Exif\ExifRational;

class Aperture
{
    use ExifRational;

    public function __toString(): string
    {
        return 'f/' . (string)$this->floatValue;
    }
}
