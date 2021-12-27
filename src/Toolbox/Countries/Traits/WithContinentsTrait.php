<?php

namespace Simtabi\Pheg\Toolbox\Countries\Traits;

use DateTimeZone;

trait WithContinentsTrait
{

    public function getAllContinents(){
        return $this->getData('continents');
    }

}