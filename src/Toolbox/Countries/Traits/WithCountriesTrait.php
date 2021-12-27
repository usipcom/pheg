<?php

namespace Simtabi\Pheg\Toolbox\Countries\Traits;

use DateTimeZone;

trait WithCountriesTrait
{

    private string $countryCode;

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return self
     */
    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = strtoupper(trim($countryCode));
        return $this;
    }

    protected function getCountriesData($request = null){
        $data = $this->getData('countries');
        return isset($data[$request]) && is_array($data) ? $data[$request] : $data;
    }


    public function getAllCountriesWithData(){
        $countries = $this->getCountriesData('countries');
        $iso2data  = $this->getCountriesData('countries2to3');
        $data      = [];
        foreach ($iso2data as $iso2 => $iso3){
            if (isset($countries[$iso2])) {
                $data[$iso2] = array_merge($countries[$iso2], [
                    'iso2' => $iso2,
                    'iso3' => $iso3,
                ]);
            }
        }
        return $data;
    }

    public function getAllCountries(){
        $countries = $this->getAllCountriesWithData();
        $data      = [];
        foreach ($countries as $code => $country){
            $data[strtoupper(strtolower($code))] = ucwords(strtolower($country['name']));
        }
        return $data;
    }

    public function getCountryInfo(){
        $data = $this->getAllCountriesWithData();
        // reverse if we are having a valid iso3 code
        $code = $this->isValidIso3CountryCode() ? $this->getCountryIsoReversed() : $this->countryCode;

        return $data[$code] ?? null;
    }

    public function getCountryName(){
        $data = $this->getCountryInfo();
        return $data['name'] ?? null;
    }

    public function getCountryNativeName(){
        $data = $this->getCountryInfo();
        return $data['native'] ?? null;
    }

    public function getCountryDialingCode(){
        $data = $this->getCountryInfo();
        return $data['phone'] ?? null;
    }

    public function getCountryContinentCode(){
        $data = $this->getCountryInfo();
        return $data['continent'] ?? null;
    }

    public function getCountryContinentName(){
        $countryInfo = $this->getCountryInfo();
        $continents  = $this->getAllContinents();
        $countryCode = $countryInfo['continent'] ?? null;

        return isset($continents[$countryCode]) && !empty($countryCode) ? $continents[$countryCode] : null;
    }

    public function getCountryCapitalCity(){
        $data = $this->getCountryInfo();
        return $data['capital'] ?? null;
    }

    public function getCountryCurrency(){
        $data = $this->getCountryInfo();
        return $data['currency'] ?? null;
    }

    public function getCountryLanguages(){
        $data = $this->getCountryInfo();
        return $data['languages'] ?? null;
    }

    public function getCountryIsoReversed(){
        $code = $this->countryCode;
        $iso2 = $this->getCountryIso3ToIso2();
        $iso3 = $this->getCountryIso2ToIso3();

        if ($this->isValidIso2CountryCode()) {
            foreach ($iso3 as $i => $item){
                if ($i === $code){
                    return $item;
                }
            }
        }elseif ($this->isValidIso3CountryCode()) {
            foreach ($iso2 as $k => $item){
                if ($k === $code){
                    return $item;
                }
            }
        }
        return null;
    }

    public function getCountryIso3ToIso2(){
        return $this->getCountriesData('countries3to2');
    }

    public function getCountryIso2ToIso3(){
        return $this->getCountriesData('countries2to3');
    }

    /**
     * Get the timezones.
     *
     * @return array|null
     */
    public function getTimezones()
    {
        $code = $this->isValidIso3CountryCode() ? $this->getCountryIsoReversed() : $this->countryCode;

        return DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $code);
    }

}