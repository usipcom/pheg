<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Media;

use Simtabi\Pheg\Core\Exceptions\CannotReadExifData;
use Simtabi\Pheg\Toolbox\Media\Embed\Embed;
use Simtabi\Pheg\Toolbox\Media\Exif\ExifData;
use Simtabi\Pheg\Toolbox\Media\Exif\ExifReader;
use Simtabi\Pheg\Toolbox\Media\File\File;
use Simtabi\Pheg\Toolbox\Media\Image\ImageHandler;
use Simtabi\Pheg\Toolbox\Media\Image\ImageManipulator;

class Media
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function file(string $path, string $mode): File
    {
        return File::invoke($path, $mode);
    }

    public function imageHandler(): ImageHandler
    {
        return ImageHandler::invoke();
    }

    public function imageManipulator($filename = null, bool $strict = false): ImageManipulator
    {
        return ImageManipulator::invoke($filename, $strict);
    }

    public function exifInfo($imagePath): bool|ExifData
    {
        try {
            return (new ExifReader())->read($imagePath);
        } catch (CannotReadExifData $e) {
            return false;
        }
    }

    public function mediaEmbed(): Embed
    {
        return Embed::invoke();
    }
}