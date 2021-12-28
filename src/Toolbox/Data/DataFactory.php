<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Data;

use Simtabi\Pheg\Toolbox\Data\Types\Factory;
use Simtabi\Pheg\Toolbox\Data\Types\Ini;
use Simtabi\Pheg\Toolbox\Data\Types\PhpArray;
use Simtabi\Pheg\Toolbox\Data\Types\Yml;
use Simtabi\Pheg\Toolbox\JSON\JSON;


final class DataFactory
{

    public static function invoke(): self
    {
        return new self();
    }

    /**
     * @param mixed $data
     * @return Json
     */
    public function fromJson($data = null): JSON
    {
        if ($data instanceof JSON) {
            return $data;
        }

        if (is_string($data)) {
            $result = new JSON($data);
        } else {
            $result = new JSON((array)$data);
        }

        return $result;
    }

    /**
     * @param mixed $data
     * @return Factory
     */
    public function fromAnyData($data = null): Factory
    {
        if ($data instanceof Factory) {
            return $data;
        }

        if (is_string($data)) {
            $result = new Factory($data);
        } else {
            $result = new Factory((array)$data);
        }

        return $result;
    }

    /**
     * @param mixed $data
     * @return PhpArray
     */
    public function fromPhpArray($data = null): PhpArray
    {
        if ($data instanceof PhpArray) {
            return $data;
        }

        if (is_string($data)) {
            $result = new PhpArray($data);
        } else {
            $result = new PhpArray((array)$data);
        }

        return $result;
    }

    /**
     * @param mixed $data
     * @return Ini
     */
    public function fromIni($data = null): Ini
    {
        if ($data instanceof Ini) {
            return $data;
        }

        if (is_string($data)) {
            $result = new Ini($data);
        } else {
            $result = new Ini((array)$data);
        }

        return $result;
    }

    /**
     * @param mixed $data
     * @return Yml
     */
    public function fromYml($data = null): Yml
    {
        if ($data instanceof Yml) {
            return $data;
        }

        if (is_string($data)) {
            $result = new Yml($data);
        } else {
            $result = new Yml((array)$data);
        }

        return $result;
    }
}