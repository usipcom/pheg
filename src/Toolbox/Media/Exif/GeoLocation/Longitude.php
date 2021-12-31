<?php
declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Media\Exif\GeoLocation;

class Longitude
{
    use Coordinate;

    /** @var array<string> */
    private const AVAILABLE_REF = ['W', 'E'];

    /** @var int */
    private const BOUNDARY = 180;
}
