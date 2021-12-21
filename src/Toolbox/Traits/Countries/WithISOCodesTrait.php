<?php

namespace Simtabi\Pheg\Toolbox\Traits\CountryData;

use Sokil\IsoCodes\IsoCodesFactory;
use Sokil\IsoCodes\TranslationDriver\DummyDriver;

trait WithISOCodesTrait
{

    public function getIsoCodes(){
        $isoCodes = new IsoCodesFactory(
            null,
            new DummyDriver()
        );

        return $isoCodes;
    }

}