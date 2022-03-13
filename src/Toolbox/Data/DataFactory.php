<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Data;

use Simtabi\Pheg\Toolbox\Data\Types\Factory;
use Simtabi\Pheg\Toolbox\Data\Types\Ini;
use Simtabi\Pheg\Toolbox\Data\Types\JSON;
use Simtabi\Pheg\Toolbox\Data\Types\PhpArray;
use Simtabi\Pheg\Toolbox\Data\Types\Yml;


final class DataFactory
{

    public function __construct() {}

    /**
     * @param mixed $data
     * @return Json
     */
    public function json($data = null): JSON
    {
        if ($data instanceof JSON) {
            return $data;
        }

        if (is_string($data)) {
            $result = JSON::invoke($data);
        } else {
            $result = JSON::invoke((array)$data);
        }

        return $result;
    }

    /**
     * @param mixed $data
     * @return Factory
     */
    public function data($data = null): Factory
    {
        if ($data instanceof Factory) {
            return $data;
        }

        if (is_string($data)) {
            $result = Factory::invoke($data);
        } else {
            $result = Factory::invoke((array)$data);
        }

        return $result;
    }

    /**
     * @param mixed $data
     * @return PhpArray
     */
    public function phpArray($data = null): PhpArray
    {
        if ($data instanceof PhpArray) {
            return $data;
        }

        if (is_string($data)) {
            $result = PhpArray::invoke($data);
        } else {
            $result = PhpArray::invoke((array)$data);
        }

        return $result;
    }

    /**
     * @param mixed $data
     * @return Ini
     */
    public function ini($data = null): Ini
    {
        if ($data instanceof Ini) {
            return $data;
        }

        if (is_string($data)) {
            $result = Ini::invoke($data);
        } else {
            $result = Ini::invoke((array)$data);
        }

        return $result;
    }

    /**
     * @param mixed $data
     * @return Yml
     */
    public function yml($data = null): Yml
    {
        if ($data instanceof Yml) {
            return $data;
        }

        if (is_string($data)) {
            $result = Yml::invoke($data);
        } else {
            $result = Yml::invoke((array)$data);
        }

        return $result;
    }
}