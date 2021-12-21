<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class VersionNumberValidator
{

    use WithRespectValidatorsTrait;

    /**
     * Version control regex validation
     *
     * Will validate "version numbers" using regex and preg_match.
     * Examples (With or without 'v') v1 v1.1 v1.132.1
     * Allows "unlimited" length MAJOR version Followed by (but not required)
     * up to 2 addition "." - MINOR, PATCH Followed by (vut not required) "-" followed
     * by "pre|beta|b|RC|alpha|a|pl|p" followed by -#
     *
     * @param $value
     * @return int
     *
     * @author https://github.com/nicholas-c/version-regex
     * Notes: http://stackoverflow.com/questions/8100717/using-regular-expressions-in-php-to-return-part-of-a-string
     */
    public function isVersionNumber($value): bool
    {
        if(preg_match('#^v?(\d{1,3}+(?:\.(?:\d{1,3})){0,2})(-(?:pre|beta|b|RC|alpha|a|pl|p)(?:\.?(?:\d+))?)?$#i', $value)){
            return true;
        }
        return false;
    }

    public function isVersionReleaseNumber($value): bool
    {

        // 1.23.456 = version 1, release 23, modification 456
        #  1.23     = version 1, release 23, any modification
        #  1.23.*   = version 1, release 23, any modification
        #  1.*      = version 1, any release, any modification
        #  1        = version 1, any release, any modification
        #  *        = any version, any release, any modification

        if(preg_match('/^(\d+\.)?(\d+\.)?(\*|\d+)$/', $value)){
            return true;
        }
        return false;
    }
    
}
