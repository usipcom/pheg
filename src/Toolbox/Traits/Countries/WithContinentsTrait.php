<?php

namespace Simtabi\Pheg\Toolbox\Traits\CountryData;

use DateTimeZone;

trait WithContinentsTrait
{

    public function getAllContinents(){
        return $this->getData('continents');
    }

}