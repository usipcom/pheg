<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Transfigures;

/**
 * A class that handles the detection and conversion of certain resource formats / content types into other formats.
 * The current formats are supported: XML, JSON, Array, Object, Serialized
 *
 * @version	2.0.0
 * @package	mjohnson.utility
 */
use Exception;
use DOMDocument;
use stdClass;
use Simtabi\Enekia\Validators;
use Simtabi\Pheg\Toolbox\Serialize;

final class Transfigure {

    private Validators $validators;
    private Serialize  $serialize;
    private Xml2Array  $xml2Array;
    private            $resource;

    private const      UNKNOWN_DATA_TYPE_MSG = 'Unknown data type';
    private const      DATA_IS_EMPTY_MSG     = 'Data to be converted can not be empty';

    private function __construct()
    {
        $this->validators = Validators::invoke();
        $this->serialize  = Serialize::invoke();
        $this->xml2Array  = Xml2Array::invoke();
    }

    public static function invoke(): self
    {
        return new self();
    }

    public function arrayToXml(): ArrayToXml
    {
        return ArrayToXml::invoke();
    }

    public function xml2Array(): Xml2Array
    {
        return Xml2Array::invoke();
    }

    public function xmlResponse(array $array): XmlResponse
    {
        return XmlResponse::invoke($array);
    }

    public function utf8Encode(string|array|object $resource): object|array|string
    {
        if (is_string($resource)) {
            return utf8_encode($resource);
        } elseif (is_array($resource)) {
            foreach ($resource as $key => $value) {
                $resource[utf8_encode($key)] = $this->utf8Encode($value);
            }
        } elseif (is_object($resource)) {
            foreach ($resource as $key => $value) {
                $resource->{$key} = $this->utf8Encode($value);
            }
        }

        return $resource;
    }

    public function utf8Decode(string|array|object $resource): object|array|string
    {
        if (is_string($resource)) {
            return utf8_decode($resource);
        } elseif (is_array($resource)) {
            foreach ($resource as $key => $value) {
                $resource[utf8_decode($key)] = $this->utf8Decode($value);
            }
        } elseif (is_object($resource)) {
            foreach ($resource as $key => $value) {
                $resource->{$key} = $this->utf8Decode($value);
            }
        }

        return $resource;
    }

    public function stringToArray(string $resource): array
    {
        return [$resource];
    }

    public function json2Array(string $resource, $associative = true)
    {
        return json_decode($resource, $associative);
    }

    public function array2Json(array $resource): bool|string
    {
        return json_encode($resource);
    }

    public function string2Json(string $resource): bool|string
    {
        return json_encode([$resource]);
    }

    public function string2Object(string $resource): stdClass
    {
        return $this->array2Object([$resource]);
    }

    public function json2Object(string $resource)
    {
        return json_decode($resource, true);
    }

    public function serialize(mixed $resource)
    {
        return $this->serialize->serialize($resource);
    }

    public function unserialze(string $resource)
    {
        return $this->serialize->unserialize($resource);
    }

    public function arrayObject2Xml(array|object $resource, array $config = ArrayToXmlConfig::DEFAULTS): DOMDocument
    {
        return ArrayToXml::invoke($config)->buildXml($resource);
    }

    public function xmlToArray(mixed $resource, bool $fromDOM = false, array $config = XmlToArrayConfig::DEFAULTS): array|XmlResponse|string
    {
        return Xml2Array::invoke()->convert($resource, $fromDOM, $config);
    }

    public function array2Object(array $resource): stdClass
    {

        $object = new stdClass();

        foreach ($resource as $key => $value) {
            if (is_array($value)) {
                $object->{$key} = $this->array2Object($value);
            } else {
                $object->{$key} = $value;
            }
        }

        return $object;
    }

    public function object2Array(object $resource): array
    {

        $resource = get_object_vars($resource);
        return array_map(array('self', 'object2Array'), $resource);

        $array = [];

        foreach ($resource as $key => $value)
        {
            if (is_object($value)) {
                $array[$key] = $this->object2Array($value);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    private function throwUnknownDataTypeError()
    {
        throw new Exception(self::UNKNOWN_DATA_TYPE_MSG);
    }

    private function throwEmptyDataError()
    {
        throw new Exception(self::DATA_IS_EMPTY_MSG);
    }

    private function validate(): Validators\DataType
    {
        return $this->validators->dataType();
    }

    public function getDataType(): string
    {
        $resource = $this->resource;
        return match ($resource) {
            $this->validate()->isArray($resource)      => 'array',
            $this->validate()->isObject($resource)     => 'object',
            $this->validate()->isJson($resource)       => 'json',
            $this->validate()->isSerialized($resource) => 'serialized',
            $this->validate()->isXml($resource)        => 'xml',
            $this->validate()->isString($resource)     => 'string',
            default                                    => self::UNKNOWN_DATA_TYPE_MSG,
        };
    }

    public function toArray($resource): mixed
    {
        if (empty($resource)) $this->throwEmptyDataError();

        $this->resource = $resource;
        return match ($resource) {
            $this->validate()->isArray($resource)      => $resource,
            $this->validate()->isObject($resource)     => $this->object2Array($resource),
            $this->validate()->isJson($resource)       => $this->json2Array($resource),
            $this->validate()->isSerialized($resource) => $this->json2Array($resource),
            $this->validate()->isXml($resource)        => $this->xmlToArray($resource),
            $this->validate()->isString($resource)     => $this->stringToArray($resource),
            default                                    => [$resource],
        };
    }

    public function toJson($resource)
    {
        if (empty($resource)) $this->throwEmptyDataError();

        $this->resource = $resource;
        return match ($resource) {
            $this->validate()->isJson($resource)       => $resource,
            $this->validate()->isArray($resource)      => $this->array2Json($resource),
            $this->validate()->isObject($resource)     => $this->object2Array($resource),
            $this->validate()->isSerialized($resource) => $this->json2Array($resource),
            $this->validate()->isXml($resource)        => $this->xmlToArray($resource),
            $this->validate()->isString($resource)     => $this->string2Json($resource),
            default                                    => $this->throwUnknownDataTypeError(),
        };
    }

    public function toObject($resource)
    {

        if (empty($resource)) $this->throwEmptyDataError();

        $this->resource = $resource;
        return match ($resource) {
            $this->validate()->isObject($resource)     => $resource,
            $this->validate()->isArray($resource)      => $this->array2Object($resource),
            $this->validate()->isJson($resource)       => $this->json2Object($resource),
            $this->validate()->isSerialized($resource) => $this->array2Object($this->serialize($resource)),
            $this->validate()->isXml($resource)        => $this->xmlToArray($resource),
            $this->validate()->isString($resource)     => $this->string2Object($resource),
            default                                    => $this->throwUnknownDataTypeError(),
        };
    }

}