<?php

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

use Respect\Validation\Validator as Respect;

trait WithRespectValidatorsTrait
{

    public function respect(): Respect
    {
        return new Respect();
    }

}