<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\HTML;

final class Form
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function data2selectbox(array|object $data, $placeholderText = "Select something", $nothingToSelectText = "Nothing to Select"): array
    {

        if (is_object($data)) {
            $data = pheg()->transfigure()->toArray($data);
        }

        if (is_array($data) && (count($data) >= 1)){
            $default[0] = $placeholderText;
        }else{
            $default[0] = $nothingToSelectText;
        }

        return array_merge($default , $data);
    }

}