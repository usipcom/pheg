<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class UsernameValidator
{

    use WithRespectValidatorsTrait;

    public function isUsername($username, $minLength = 5, $maxLength = 32, $startWithAlphabets = false): bool
    {

        // trim username
        $username  = trim($username);

        $minLength = !self::isInteger($minLength) ? 5 : $minLength;

        // validate username maximum length
        $maxLength = !self::isInteger($maxLength) ? 32 : $maxLength;

        // validate username length
        if(!$this->respect()->stringType()->length($minLength, $maxLength, true)->validate($username)){
            return false;
        }

        // if we are to strictly start with alphabets
        $regex     = $startWithAlphabets ? '[A-Za-z]' : '';
        if(preg_match('/^'.$regex.'[A-Za-z0-9\d_]{5,'.$maxLength.'}$/', $username)){
            return true;
        }
        return false;
    }


}
