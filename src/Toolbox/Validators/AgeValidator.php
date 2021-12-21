<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class AgeValidator
{

    use WithRespectValidatorsTrait;

    public function isLegalAge($value, $limit = 18): bool
    {
        if($this->respect()->age($limit)->validate($value)){
            return true;
        }
        return false;
    }

}