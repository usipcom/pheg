<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class StringValidator
{

    use WithRespectValidatorsTrait;

    /***
     * Function is_title
     *
     * http://stackoverflow.com/questions/3623719/php-regex-to-check-a-english-name
     * http://stackoverflow.com/questions/1261338/php-regex-for-human-names
     * http://stackoverflow.com/questions/275160/regex-for-names
     * http://www.regextester.com/
     * http://www.regexr.com/
     *
     * @param $value
     * @param int $length
     * @return bool
     *
     */
    public function isTitle($value, $length = 80): bool
    {

        // for later reference
        # $illegal = "#$%^&*()+=-[]';,./{}|:<>?~";
        $allowed = '~\-!@#$%\^&\*\(\)';

        $value = trim($value);
        if(Respect::stringType()->validate($value)){
            return (!preg_match("/^[\w\s$allowed]{1,$length}$/", trim($value))) ? false : true;
        }
        return false;
    }

    public function hasNoRepeatingChars($value, $minimumCount = 3): bool
    {
        if (!preg_match('/([a-z]{'.$minimumCount.',}|[0-9]{'.$minimumCount.',})/i', $value)) {
            return true;
        }
        return false;
    }


    /**
     * Checks if the $haystack starts with the text in the $needle.
     *
     * @param string $haystack
     * @param string $needle
     * @param bool   $caseSensitive
     * @return bool
     */
    public function isStart(string $haystack, string $needle, bool $caseSensitive = false): bool
    {
        if ($caseSensitive) {
            return $needle === '' || $this->pos($haystack, $needle) === 0;
        }

        return $needle === '' || $this->iPos($haystack, $needle) === 0;
    }

    /**
     * Checks if the $haystack ends with the text in the $needle. Case sensitive.
     *
     * @param string $haystack
     * @param string $needle
     * @param bool   $caseSensitive
     * @return bool
     */
    public function isEnd(string $haystack, string $needle, bool $caseSensitive = false): bool
    {
        if ($caseSensitive) {
            return $needle === '' || $this->sub($haystack, -$this->len($needle)) === $needle;
        }

        $haystack = $this->low($haystack);
        $needle = $this->low($needle);

        return $needle === '' || $this->sub($haystack, -$this->len($needle)) === $needle;
    }

    public function isSlug($value): bool
    {
        if($this->respect()->slug()->validate($value)){
            return true;
        }
        return false;
    }

}
