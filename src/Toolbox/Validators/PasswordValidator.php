<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

use ZxcvbnPhp\Zxcvbn;

class PasswordValidator
{

    use WithRespectValidatorsTrait;

    /****
     * Check for complex password using regular expressions
     *
     * Checks if provided password meets the following conditions:
     * - of at least a minimum length of 3
     * - containing at least one lowercase letter
     * - and at least one uppercase letter
     * - and at least one number
     * - and at least a special character (non-word characters)
     * http://www.catswhocode.com/blog/15-php-regular-expressions-for-web-developers
     * http://shabeebk.com/blog/regular-expression-for-at-least-one-number-capital-letter-and-a-special-character/
     * http://stackoverflow.com/questions/5142103/regex-for-password-strength
     * http://runnable.com/UmrnTejI6Q4_AAIM/how-to-validate-complex-passwords-using-regular-expressions-for-php-and-pcre
     *
     * @param $value
     * @param int $minLength
     * @param int $maxLength
     * @return bool|mixed
     *
     */

    public function isUsablePassword($value, $minLength = 6, $maxLength = 150): bool
    {
        if (strlen($value) < $minLength) {
            return false;
        }
        elseif (strlen($value) > $maxLength) {
            return false;
        }
        elseif (!preg_match('@[A-Z]@', $value) || !preg_match('@[a-z]@', $value) || !preg_match('@[0-9]@', $value)) {
            return false;
        }
        elseif (!preg_match_all("$\S*(?=\S{'.$minLength.',})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$", $value)){
            return false;
        }
        return true;
    }

    public function isValidPasswordScore($value, $minimumScore = 3): bool
    {

        // check password score
        $init  = new Zxcvbn();
        $score = $init->passwordStrength($value)['score'];
        if( $score < $minimumScore ) {
            return false;
        }

        if(preg_match('#^v?(\d{1,3}+(?:\.(?:\d{1,3})){0,2})(-(?:pre|beta|b|RC|alpha|a|pl|p)(?:\.?(?:\d+))?)?$#i', $value))
        {
            return true;
        }
        return false;
    }

}
