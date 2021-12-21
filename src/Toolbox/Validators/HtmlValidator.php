<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class HtmlValidator
{

    use WithRespectValidatorsTrait;

    /**
     * Check if a string has some html tags
     * Example: '<p>My string</p>' => true
     * @param string $string
     * @param string|null $exceptions
     * @return bool
     */

    public function hasHtml(string $string, string $exceptions = null): bool
    {
        if($exceptions)
        {
            $regex = "/<(?!(?:".$exceptions.")\b)([\w]+)([^>]*>)?/";
        } else {
            $regex = "/<([\w]+)([^>]*>)?/";
        }

        return preg_match($regex,$string,$m) != 0;
    }

}