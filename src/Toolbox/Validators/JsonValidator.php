<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class JsonValidator
{

    use WithRespectValidatorsTrait;

    public function isJSON($value, $alt = false): bool
    {

        if(!$alt){
            return is_string($value) && is_object(json_decode($value));
        }

        // checks for calculating if the string given to it is JSON.
        // So, it is the most perfect one, but it's slower than the other.
        # Requires PHP 5.4 and above
        return !is_string($value) && is_object(json_decode($value)) && (json_last_error() == JSON_ERROR_NONE);
    }

}