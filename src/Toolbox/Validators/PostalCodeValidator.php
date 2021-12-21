<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class PostalCodeValidator
{

    use WithRespectValidatorsTrait;

    public function isPostalCode($value, $locale = 'US'): bool
    {
        if($this->respect()->numeric()->postalCode($locale)->validate($value)){
            return true;
        }
        return false;
    }

}