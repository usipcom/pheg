<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

/**
 * A class that handles the detection and conversion of certain resource formats / content types into other formats.
 * The current formats are supported: XML, JSON, Array, Object, Serialized
 *
 * @version	2.0.0
 * @package	mjohnson.utility
 */

use SimpleXMLElement;
use stdClass;

final class TypeConverter {

    /**
     * Disregard XML attributes and only return the value.
     */
    const XML_NONE = 0;

    /**
     * Merge attributes and the value into a single dimension; the values key will be "value".
     */
    const XML_MERGE = 1;

    /**
     * Group the attributes into a key "attributes" and the value into a key of "value".
     */
    const XML_GROUP = 2;

    /**
     * Attributes will only be returned.
     */
    const XML_OVERWRITE = 3;

    public function __construct()
    {

    }

    public static function invoke(): self
    {
        return new self();
    }

    /**
     * Returns a string for the detected type.
     *
     * @access public
     * @param mixed $data
     * @return string
     * @static
     */
    public function is($data) {

        if ($this->isArray($data)) {
            return 'array';

        } elseif ($this->isObject($data)) {
            return 'object';

        } elseif ($this->isJson($data)) {
            return 'json';

        } elseif ($this->isSerialized($data)) {
            return 'serialized';

        } elseif ($this->isXml($data)) {
            return 'xml';
        }

        return 'other';
    }

    /**
     * Check to see if data passed is an array.
     *
     * @access public
     * @param mixed $data
     * @return boolean
     * @static
     */
    public function isArray($data) {
        return is_array($data);
    }

    /**
     * Check to see if data passed is a JSON object.
     *
     * @access public
     * @param mixed $data
     * @return boolean
     * @static
     */
    public function isJson($data) {
        return (@json_decode($data) !== null);
    }

    /**
     * Check to see if data passed is an object.
     *
     * @access public
     * @param mixed $data
     * @return boolean
     * @static
     */
    public function isObject($data) {
        return is_object($data);
    }

    /**
     * Check to see if data passed has been serialized.
     *
     * @access public
     * @param mixed $data
     * @return boolean
     * @static
     */
    public function isSerialized($data) {
        $ser = @unserialize($data);

        return ($ser !== false) ? $ser : false;
    }

    /**
     * Check to see if data passed is an XML document.
     *
     * @access public
     * @param mixed $data
     * @return boolean
     * @static
     */
    public function isXml($data) {
        $xml = @simplexml_load_string($data);

        return ($xml instanceof SimpleXMLElement) ? $xml : false;
    }

    /**
     * Convert any type of data to array
     * @param $data
     * @param false $associative
     * @return array
     */
    public function fromAnyToArray($data, $associative = false){
        $data = $this->toArray(json_decode(json_encode($data, JSON_FORCE_OBJECT), $associative));
        return is_array($data) ? $data : [];
    }

    /**
     * Convert any type of data to object
     * @param $data
     * @param false $associative
     * @return object
     */
    public function fromAnyToObject($data, $associative = false){
        $data = $this->toObject(json_decode(json_encode($data, JSON_FORCE_OBJECT), $associative));
        return is_object($data) ? $data : (object) [];

    }

    /**
     * Transforms a resource into an array.
     *
     * @access public
     * @param mixed $resource
     * @return array
     * @static
     */
    public function toArray($resource) {
        if ($this->isArray($resource)) {
            return $resource;
        } elseif ($this->isObject($resource)) {
            return $this->buildArray($resource);

        } elseif ($this->isJson($resource)) {
            return json_decode($resource, true);

        } elseif ($ser = $this->isSerialized($resource)) {
            return $this->toArray($ser);

        } elseif ($xml = $this->isXml($resource)) {
            return $this->xmlToArray($xml);
        }

        return $resource;
    }

    /**
     * Transforms a resource into a JSON object.
     *
     * @access public
     * @param mixed $resource
     * @return string (json)
     * @static
     */
    public function toJson($resource) {
        if ($this->isJson($resource)) {
            return $resource;
        }

        if ($xml = $this->isXml($resource)) {
            $resource = $this->xmlToArray($xml);

        } elseif ($ser = $this->isSerialized($resource)) {
            $resource = $ser;
        }

        return json_encode($resource);
    }

    /**
     * Transforms a resource into an object.
     *
     * @access public
     * @param mixed $resource
     * @return object
     * @static
     */
    public function toObject($resource) {

        return match ($resource) {
            $this->isObject($resource)     => $resource,
            $this->isArray($resource)      => $this->buildObject($resource),
            $this->isJson($resource)       => json_decode($resource),
            $this->isSerialized($resource) => unserialize($resource),
            $this->isXml($resource)        => $this->toObject(unserialize($resource)),
            default => 'unknown status code',
        };


        if ($this->isObject($resource)) {
            return $resource;

        } elseif ($this->isArray($resource)) {
            return ;

        } elseif ($this->isJson($resource)) {
            return json_decode($resource);

        } elseif ($ser = $this->isSerialized($resource)) {
            return $this->toObject($ser);

        } elseif ($xml = $this->isXml($resource)) {
            return $xml;
        }

        return $resource;
    }

    /**
     * Transforms a resource into a serialized form.
     *
     * @access public
     * @param mixed $resource
     * @return string
     * @static
     */
    public function toSerialize($resource) {
        if (!$this->isArray($resource)) {
            $resource = $this->toArray($resource);
        }

        return serialize($resource);
    }

    /**
     * Transforms a resource into an XML document.
     *
     * @access public
     * @param mixed $resource
     * @param string $root
     * @return string (xml)
     * @static
     */
    public function toXml($resource, $root = 'root') {
        if ($this->isXml($resource)) {
            return $resource;
        }

        $array = $this->toArray($resource);

        if (!empty($array)) {
            $xml = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?><'. $root .'></'. $root .'>');
            $response = $this->buildXml($xml, $array);

            return $response->asXML();
        }

        return $resource;
    }

    /**
     * Turn an object into an array. Alternative to array_map magic.
     *
     * @access public
     * @param object $object
     * @return array
     */
    public function buildArray($object) {
        $array = array();

        foreach ($object as $key => $value) {
            if (is_object($value)) {
                $array[$key] = $this->buildArray($value);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Turn an array into an object. Alternative to array_map magic.
     *
     * @access public
     * @param array $array
     * @return object
     */
    public function buildObject($array) {
        $obj = new stdClass();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $obj->{$key} = $this->buildObject($value);
            } else {
                $obj->{$key} = $value;
            }
        }

        return $obj;
    }

    /**
     * Turn an array into an XML document. Alternative to array_map magic.
     *
     * @access public
     * @param object $xml
     * @param array $array
     * @return object
     */
    public function buildXml(&$xml, $array) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                // XML_NONE
                if (!is_array($value)) {
                    $xml->addChild($key, $value);
                    continue;
                }

                // Multiple nodes of the same name
                if (isset($value[0])) {
                    foreach ($value as $kValue) {
                        if (is_array($kValue)) {
                            $this->buildXml($xml, array($key => $kValue));
                        } else {
                            $xml->addChild($key, $kValue);
                        }
                    }

                    // XML_GROUP
                } elseif (isset($value['attributes'])) {
                    if (is_array($value['value'])) {
                        $node = $xml->addChild($key);
                        $this->buildXml($node, $value['value']);
                    } else {
                        $node = $xml->addChild($key, $value['value']);
                    }

                    if (!empty($value['attributes'])) {
                        foreach ($value['attributes'] as $aKey => $aValue) {
                            $node->addAttribute($aKey, $aValue);
                        }
                    }

                    // XML_MERGE
                } elseif (isset($value['value'])) {
                    $node = $xml->addChild($key, $value['value']);
                    unset($value['value']);

                    if (!empty($value)) {
                        foreach ($value as $aKey => $aValue) {
                            if (is_array($aValue)) {
                                $this->buildXml($node, array($aKey => $aValue));
                            } else {
                                $node->addAttribute($aKey, $aValue);
                            }
                        }
                    }

                    // XML_OVERWRITE
                } else {
                    $node = $xml->addChild($key);

                    if (!empty($value)) {
                        foreach ($value as $aKey => $aValue) {
                            if (is_array($aValue)) {
                                $this->buildXml($node, array($aKey => $aValue));
                            } else {
                                $node->addChild($aKey, $aValue);
                            }
                        }
                    }
                }
            }
        }

        return $xml;
    }

    /**
     * Convert a SimpleXML object into an array.
     *
     * @access public
     * @param object $xml
     * @param int $format
     * @return array
     */
    public function xmlToArray($xml, $format = self::XML_GROUP) {
        if (is_string($xml)) {
            $xml = @simplexml_load_string($xml);
        }

        if (count($xml->children()) <= 0) {
            return (string)$xml;
        }

        $array = array();

        foreach ($xml->children() as $element => $node) {
            $data = array();

            if (!isset($array[$element])) {
                $array[$element] = "";
            }

            if (!$node->attributes() || $format === self::XML_NONE) {
                $data = $this->xmlToArray($node, $format);

            } else {
                switch ($format) {
                    case self::XML_GROUP:
                        $data = array(
                            'attributes' => array(),
                            'value' => (string)$node
                        );

                        if (count($node->children()) > 0) {
                            $data['value'] = $this->xmlToArray($node, $format);
                        }

                        foreach ($node->attributes() as $attr => $value) {
                            $data['attributes'][$attr] = (string)$value;
                        }
                        break;

                    case self::XML_MERGE:
                    case self::XML_OVERWRITE:
                        if ($format === self::XML_MERGE) {
                            if (count($node->children()) > 0) {
                                $data = $data + $this->xmlToArray($node, $format);
                            } else {
                                $data['value'] = (string)$node;
                            }
                        }

                        foreach ($node->attributes() as $attr => $value) {
                            $data[$attr] = (string)$value;
                        }
                        break;
                }
            }

            if (count($xml->{$element}) > 1) {
                $array[$element][] = $data;
            } else {
                $array[$element] = $data;
            }
        }

        return $array;
    }

    /**
     * Encode a resource object for UTF-8.
     *
     * @access public
     * @param mixed $data
     * @return array|string
     * @static
     */
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

    /**
     * Decode a resource object for UTF-8.
     *
     * @access public
     * @param mixed $data
     * @return array|string
     * @static
     */
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

}