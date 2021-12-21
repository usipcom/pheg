<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class CountriesValidator
{

    use WithRespectValidatorsTrait;

    public function isLanguage($value): bool
    {
        if($this->respect()->languageCode()->validate($value)){
            return true;
        }elseif($this->respect()->languageCode('alpha-3')->validate($value)){
            return true;
        }
        return false;
    }

    public function isCountry($value): bool
    {
        // if we can validate it and Respect can't either
        $code  = Countries::getCountryName($value);
        $respect = $this->respect()->countryCode()->validate($value);
        if((false === $code) && (false === $respect)){
            return false;
        }
        return true;
    }

    public function isCurrency($value): bool
    {

        // if we can validate it in both alpha2 & alpha3 and Respect can't either
        if((!Countries::getCountryCurrencyCodeByCode($value)) && (!$this->respect()->currencyCode()->validate($value))){
            return false;
        }
        return true;
    }

}