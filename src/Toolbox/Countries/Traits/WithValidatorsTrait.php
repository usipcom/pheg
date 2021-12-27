<?php


namespace Simtabi\Pheg\Toolbox\Countries\Traits;


trait WithValidatorsTrait
{

    public function isValidIso2CountryCode(){
        $data = $this->getCountryIso2ToIso3();
        $code = $this->countryCode;
        return !empty($code) && isset($data[$code]) === true;
    }

    public function isValidIso3CountryCode(){
        $data = $this->getCountryIso3ToIso2();
        $code = $this->countryCode;
        return !empty($code) && isset($data[$code]) === true;
    }

    public function isValidCountryCode(){
        if ($this->isValidIso3CountryCode() || $this->isValidIso2CountryCode()) {
            return true;
        }
        return false;
    }

}