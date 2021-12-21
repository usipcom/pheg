<?php

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

trait WithGeneralValidatorsTrait
{

    public function minLength($value = null, $minimum = 0): bool
    {
        if($this->respect()->stringType()->length($minimum, null)->validate($value)){
            return true;
        }
        return false;
    }

    public function maxLength($value = null, $maximum = 5): bool
    {
        if($this->respect()->stringType()->length(null, $maximum)->validate($value)){
            return true;
        }
        return false;
    }

    public function exactLength($value = null, $compareTo = 0): bool
    {
        if($this->respect()->equals($compareTo)->validate($value)){
            return true;
        }
        return false;
    }

    public function greaterThan($value = null, $min = 0, $inclusive = true): bool
    {
        if (true === $inclusive){
            if($this->respect()->intVal()->max($min, true)->validate($value)){
                return true;
            }
        }elseif($this->respect()->intVal()->max($min)->validate($value)){
            return true;
        }
        return false;
    }

    public function lessThan($value = null, $max = 0, $inclusive = true): bool
    {
        if (true === $inclusive){
            if($this->respect()->intVal()->min($max)->validate($value)){
                return true;
            }
        }elseif($this->respect()->intVal()->min($max)->validate($value)){
            return true;
        }

        return false;
    }

    public function alpha($value = null): bool
    {
        if($this->respect()->alpha()->validate($value)){
            return true;
        }
        return false;
    }

    public function alphanumeric($value = null): bool
    {
        if($this->respect()->alnum()->validate($value)){
            return true;
        }
        return false;
    }

    public function startsWith($value = null, $match = null): bool
    {
        if($this->respect()->startsWith($value)->validate($match)){
            return true;
        }
        return false;
    }

    public function endsWith($value = null, $match = null): bool
    {
        if($this->respect()->endsWith($value)->validate($match)){
            return true;
        }
        return false;
    }

    public function contains($value = null, $match = null): bool
    {
        if($this->respect()->contains($value)->validate($match)){
            return true;
        }
        return false;
    }

    public function regex($value = null, $regex = null): bool
    {
        if($this->respect()->regex($regex)->validate($value)){
            return true;
        }
        return false;
    }


    public function isGreaterThan($value, $length = 5): bool
    {
        if($this->respect()->stringType()->length(null, $length)->validate($value)){
            return true;
        }
        return false;
    }

    public function isLessThan($value, $length = 5): bool
    {
        if($this->respect()->stringType()->length($length, null)->validate($value)){
            return true;
        }
        return false;
    }

    public function isIdentical($val1, $val2): bool
    {
        if($this->respect()->equals($val1)->validate($val2)){
            return true;
        }
        return false;
    }

    public function isInRange($value, $minimum, $maximum): bool
    {
        if($this->respect()->stringType()->length($minimum, $maximum)->validate($value)){
            return true;
        }
        return false;
    }


    public function isInteger($value): bool
    {
        if($this->respect()->intVal()->validate($value)){
            return true;
        }
        return false;
    }

    public function isNumeric($value): bool
    {
        if($this->respect()->numeric()->validate($value)){
            return true;
        }
        return false;
    }

    public function isBool($value): bool
    {
        return $this->isBoolean($value);
    }

    public function isBoolean($value): bool
    {
        if($this->respect()->boolVal()->validate($value)){
            return true;
        }elseif($this->respect()->boolType()->validate($value)){
            return true;
        }
        return false;
    }

    public function isTrue($value): bool
    {
        if($this->respect()->trueVal()->validate($value) == true){
            return true;
        }
        return false;
    }

    public function isFloat($value): bool
    {
        if (is_float($value)){
            return true;
        }
        return false;
    }

    public function isFalse($value): bool
    {
        if($this->respect()->trueVal()->validate($value) == false){
            return true;
        }
        return false;
    }

    public function isString($value): bool
    {

        // ensure it's of a string type value and !empty
        if( $this->respect()->StringType()->validate($value) && !(empty($value) && strlen($value) == 0  || is_null($value))){
            return true;
        }
        return false;
    }

    public function isEmpty($value): bool
    {

        // if is an array or an object
        if ($this->isArrayOrObject($value)){
            if ($this->isUsableArrayObject($value)){
                return true;
            }
        }
        elseif ( empty($value) && strlen($value) == 0 ){
            return true;
        }
        return false;
    }

    /**
     * Compare string to null designation
     *
     * @param  string $str
     * @return bool
     */
    public function isNull(string $str): bool
    {
        if (!isset($str) || trim($str) === '' || ($str === '<null>') || $str === 'null') {
            return true;
        }
        return  false;
    }


    /**
     * Determine whether a variable has a non-empty value.
     *
     * Alternative to {@see empty()} that accepts non-empty values:
     * - _0_ (0 as an integer)
     * - _0.0_ (0 as a float)
     * - _"0"_ (0 as a string)
     *
     * @param  mixed $number The value to be checked.
     * @return boolean Returns true if var exists and has a non-empty value. Otherwise returns true.
     */
    public function isBlank(mixed $number): bool
    {
        return empty($number) && !is_numeric($number);
    }

}