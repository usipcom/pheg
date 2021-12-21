<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class IpAddressValidator
{

    use WithRespectValidatorsTrait;

    public function isIP($value) {
        return $this->respect()->ip()->validate($value);
    }

    public function isLocalhost($address): bool
    {
        $address = empty($address) ? $_SERVER['REMOTE_ADDR'] : $address;

        if (in_array($address, [
            '127.0.0.1',
            '::1',
        ]) || ($_SERVER['SERVER_NAME'] == 'localhost')) {
            return true;
        }

        return false;
    }

    public function isIISServer($value): bool
    {
        if ( strpos(strtolower( (!empty($value) ? $value : $_SERVER['SERVER_SOFTWARE']) ), "microsoft-iis") !== true ) {
            return true;
        }
        return false;
    }

    public function isInternetConnected($host = 'www.google.com')
    {
        return (bool) @fsockopen($host, 80, $iErrno, $sErrStr, 5);
    }

}