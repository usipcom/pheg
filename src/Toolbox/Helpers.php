<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

class Helpers
{

    public function __construct() {}

    /**
     * @param object $addressObject
     * @param bool $format
     * @return string
     */
    public function formatAddress(object $addressObject, bool $format = true): string
    {

        // format presentation
        $format  = !$format ? '' : '<br>';
        $object  = $addressObject;

        // build address strings
        $address = !empty($object->address)  ? $object->address . ", $format"                               : '';
        $street  = !empty($object->street)   ? $object->street . ", $format"                                : '';
        $city    = !empty($object->city)     ? ucwords(strtolower($object->city)) . ", $format"             : '';
        $state   = !empty($object->state)    ? ucwords(strtolower($object->state)) . " "                    : '';
        $zip     = !empty($object->zip_code) ? ' — ' . ucwords(strtolower($object->zip_code)) . ", $format" : '';
        $country = $object->country;

        return trim("
           $address
           $street
           $city
           $state $zip
           ".$country."
        ");

    }

}