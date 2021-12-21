<?php

namespace Simtabi\Pheg\Core\Support\Traits;

trait FormHelpersTrait
{

    public function getFormReady_Timezones($default = null)
    {
        if (!empty($default)) {
            return $this->pheg->getTimezones()['flat'][$default] ?? null;
        }else{
            return $this->pheg->getTimezones()['formed'];
        }
    }

    public function getFormReady_DatetimeFormats($default = null, $type = 'long')
    {
        $data = $this->pheg->getFromArray('datetime.'. trim($type), $this->getDatetimeFormats($default));
        $out  = [];
        if (!empty($data)) {
            foreach ($data as $k => $datum){
                $out[$k] = $datum['human'];
            }
        }
        return $out;
    }

    public function getFormReady_DateFormats($default = null, $type = 'short')
    {
        $data = $this->pheg->getFromArray('date.'. trim($type), $this->getDatetimeFormats($default));
        $out  = [];
        if (!empty($data)) {
            foreach ($data as $k => $datum){
                $out[$k] = $datum['human'];
            }
        }
        return $out;
    }

    public function getFormReady_TimeFormats($default = null, $type = 'short')
    {
        $data = $this->pheg->getFromArray('time.'. trim($type), $this->getDatetimeFormats($default));
        dd($data);
        $out  = [];
        if (!empty($data)) {
            foreach ($data as $k => $datum){
                $out[$k] = $datum['human'];
            }
        }
        return $out;
    }

    public function getFormReady_JsFormats($default = null, $type = 'date')
    {
        $data = $this->pheg->getFromArray('js.'. trim($type), $this->getDatetimeFormats($default));
        $out  = [];
        if (!empty($data)) {
            foreach ($data as $k => $datum){
                $out[$k] = $datum['human'];
            }
        }
        return $out;
    }


}
