<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\HTML;

final class Form
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function data2selectbox(array|object $data, ?string $placeholderText = "Select something", string $nothingToSelectText = "Nothing to Select"): array
    {
        if (is_object($data))
        {
            $data = pheg()->transfigure()->toArray($data);
        }

        if (empty($data)){
            return ['' => $nothingToSelectText];
        }

        return ['' => $placeholderText] + $data;

    }

}