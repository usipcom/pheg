<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Transfigures;

/**
 * A class that handles the detection and conversion of certain resource formats / content types into other formats.
 * The current formats are supported: XML, JSON, Array, Object, Serialized
 *
 * @version	2.0.0
 * @package	mjohnson.utility
 */

use DOMDocument;
use SimpleXMLElement;
use Simtabi\Enekia\Validators;
use Simtabi\Pheg\Core\Exceptions\PhegException;
use Simtabi\Pheg\Toolbox\Serialize;
use stdClass;

final class TypeConverter {

    private Validators $validators;
    private Serialize  $serialize;
    private Xml2Array  $xml2Array;

    public function __construct()
    {
        $this->validators = Validators::invoke();
        $this->serialize  = Serialize::invoke();
        $this->xml2Array  = Xml2Array::invoke();
    }

    public static function invoke(): self
    {
        return new self();
    }

    public function isArray($data) {
        return is_array($data);
    }

    public function isJson($data) {
        return (@json_decode($data) !== null);
    }

    public function isObject($data) {
        return is_object($data);
    }

    public function isSerialized($resource)
    {
        return $this->serialize->is($resource);
    }

    public function isXml($data) {
        return $this->validators->xml()->isValid($data, null, true);
        // return (@simplexml_load_string($data) instanceof SimpleXMLElement);
    }

    public function json2Array($resource)
    {
        return json_decode($resource, true);
    }

    public function array2Json($resource)
    {
        return json_encode($resource);
    }

    public function json2Object($resource)
    {
        return json_decode($resource, true);
    }

    public function serialize($resource)
    {
        return $this->serialize->serialize($resource);
    }

    public function unserialze($resource)
    {
        return $this->serialize->unserialize($resource);
    }

    public function arrayObject2Xml(array|object $resource, array $config = ArrayToXmlConfig::DEFAULTS)
    {
        return ArrayToXml::invoke($config)->buildXml($resource);
    }

    public function xmlToArray(mixed $resource, bool $fromDOM = false, array $config = XmlToArrayConfig::DEFAULTS)
    {
        return Xml2Array::invoke()->convert($resource, $fromDOM, $config);
    }

    public function array2Object($array) {
        $obj = new stdClass();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $obj->{$key} = $this->array2Object($value);
            } else {
                $obj->{$key} = $value;
            }
        }

        return $obj;
    }

    public function object2Array($object) {
        $array = [];

        foreach ($object as $key => $value) {
            if (is_object($value)) {
                $array[$key] = $this->object2Array($value);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    public function utf8Encode($data) {
        if (is_string($data)) {
            return utf8_encode($data);

        } elseif (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[utf8_encode($key)] = $this->utf8Encode($value);
            }

        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->{$key} = $this->utf8Encode($value);
            }
        }

        return $data;
    }

    public function utf8Decode($data) {
        if (is_string($data)) {
            return utf8_decode($data);

        } elseif (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[utf8_decode($key)] = $this->utf8Decode($value);
            }

        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->{$key} = $this->utf8Decode($value);
            }
        }

        return $data;
    }


    private function throwError()
    {
        throw new PhegException('Unknown data type');
    }

    public function getDataType($resource) {
        return match ($resource) {
            $this->isArray($resource)      => 'array',
            $this->isObject($resource)     => 'object',
            $this->isJson($resource)       => 'json',
            $this->isSerialized($resource) => 'serialized',
            $this->isXml($resource)        => 'xml',
            default                        => $this->throwError(),
        };
    }

    public function toArray($resource): mixed
    {
        return match ($resource) {
            $this->isArray($resource)      => $resource,
            $this->isObject($resource)     => $this->object2Array($resource),
            $this->isJson($resource)       => $this->json2Array($resource),
            $this->isSerialized($resource) => $this->json2Array($resource),
            $this->isXml($resource)        => $this->xmlToArray($resource),
            default                        => $this->throwError(),
        };
    }

    public function toJson($resource)
    {
        return match ($resource) {
            $this->isJson($resource)       => $resource,
            $this->isArray($resource)      => $this->array2Json($resource),
            $this->isObject($resource)     => $this->object2Array($resource),
            $this->isSerialized($resource) => $this->json2Array($resource),
            $this->isXml($resource)        => $this->xmlToArray($resource),
            default                        => $this->throwError(),
        };
    }

    public function toObject($resource)
    {
        return match ($resource) {
            $this->isObject($resource)     => $resource,
            $this->isArray($resource)      => $this->array2Object($resource),
            $this->isJson($resource)       => $this->json2Object($resource),
            $this->isSerialized($resource) => $this->array2Object($this->serialize($resource)),
            $this->isXml($resource)        => $this->xmlToArray($resource),
            default                        => $this->throwError(),
        };
    }
}