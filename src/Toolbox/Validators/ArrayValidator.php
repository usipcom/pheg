<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

use Simtabi\Pheg\Toolbox\Arr;

class ArrayValidator
{

    use WithRespectValidatorsTrait;

    /**
     * Determines if an array is associative.
     *
     * @param  array $array
     * @return bool
     */
    public function isAssoc(array $array): bool
    {
        if (array_keys( $array ) !== range( 0, count( $array ) - 1 )){
            return true;
        }
        return false;
    }

    public function isArray($value): bool
    {
        if($this->respect()->arrayType()->validate($value)){
            return true;
        }
        return false;
    }

    public function isObject($value): bool
    {
        if($this->respect()->arrayType()->validate($value)){
            return true;
        }
        return false;
    }

    public function isArrayOrObject($value): bool
    {

        if($this->respect()->arrayVal()->validate($value)){
            return true;
        }
        elseif($this->respect()->arrayType()->validate($value)){
            return true;
        }
        return false;
    }

    public function isUsableArrayObject($value, $filter = true): bool
    {
        if (!self::isArrayOrObject($value)){
            return false;
        }

        // remove empty values
        $value = true === $filter ? Arr::invoke()->filter($value) : $value;

        // if array is not empty
        if ($this->respect()->arrayVal()->notEmpty()->validate($value)){
            return true;
        }
        return false;
    }

    public function inArray($value = null, $list = []):bool
    {
        if (in_array($value, $list)){
            return true;
        }
        return false;
    }

    public function isFoundInArray($needle, $haystack): bool
    {
        $found = false;
        foreach ($haystack as $key => $item) {
            if ($needle === $key) {
                $found = true;
                break;
            } elseif (is_array($item)) {
                $found = $this->isFoundInArray($needle, $item);
                if($found) {
                    break;
                }
            }
        }
        return $found;
    }

    public function isInArrayKey($key, array $array): bool
    {
       return array_key_exists((string)$key, $array);
    }

    /**
     * Check is value exists in the array
     *
     * @param mixed $value
     * @param array $array
     * @param bool  $returnKey
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function isInArray($value, array $array, bool $returnKey = false)
    {
        $status = in_array($value, $array, true);

        if ($returnKey) {
            if ($status) {
                return array_search($value, $array, true);
            }

            return null;
        }

        return $status;
    }

    /**
     * @param object $obj
     * @return bool
     */
    public function isEmptyObject(object $obj): bool
    {
        return empty((array) $obj);
    }

}
