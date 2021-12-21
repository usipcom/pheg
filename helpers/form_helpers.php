<?php

use Simtabi\Pheg\Pheg;

if (!function_exists('create_selectable_array')) {
    function create_selectable_array($array, $name_column = 'name', $withNull = false, $select_data_text = 'Select something', $empty_data_text = 'Select something')
    {
        if (! $array) {
            return ['' =>  $empty_data_text];
        }

        $data = $array->mapWithKeys(function ($value) use ($name_column) {
            return [$value->id => $value->{$name_column}];
        });

        if ($withNull) {
            return ['' => (!empty($select_data_text) ? $select_data_text : '--')] + $data->toArray();
        }

        return $data;
    }
}