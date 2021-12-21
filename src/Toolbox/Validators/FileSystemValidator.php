<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class FileSystemValidator
{

    use WithRespectValidatorsTrait;

    /**
     * Checks if image has JPEG/JPG format
     *
     * @param string|null $format
     * @return bool
     */
    public function isJpeg(?string $format = null): bool
    {
        if (!$format) {
            return false;
        }

        $format = strtolower($format);
        return 'image/jpg' === $format || 'jpg' === $format || 'image/jpeg' === $format || 'jpeg' === $format;
    }

    /**
     * Checks if image has GIF format
     *
     * @param string|null $format
     * @return bool
     */
    public function isGif(?string $format = null): bool
    {
        if (!$format) {
            return false;
        }

        $format = strtolower($format);
        return 'image/gif' === $format || 'gif' === $format;
    }

    /**
     * Checks if image has PNG format
     *
     * @param string|null $format
     * @return bool
     */
    public function isPng(?string $format = null): bool
    {
        if (!$format) {
            return false;
        }

        $format = strtolower($format);
        return 'image/png' === $format || 'png' === $format;
    }

    /**
     * Checks if image has WEBP format
     *
     * @param string|null $format
     * @return bool
     */
    public function isWebp(?string $format = null): bool
    {
        if (!$format) {
            return false;
        }

        $format = strtolower($format);
        return 'image/webp' === $format || 'webp' === $format;
    }

    /**
     * Check is format supported by lib
     *
     * @param string $format
     * @return bool
     */
    public function isSupportedFormat(string $format): bool
    {
        if ($format) {
            return $this->isJpeg($format) || $this->isPng($format) || $this->isGif($format) || $this->isWebp($format);
        }

        return false;
    }

    public function isEmptyDir($value): bool
    {
        if (!is_readable($value))
            return null;
        $handle = opendir($value);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                return false;
            }
        }
        return true;
    }

}