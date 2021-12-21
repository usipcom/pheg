<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use JsonException;

final class Json
{

    public static function invoke(): self
    {
        return new self();
    }

    public function prettyPrint($data, $html = false, $raw_array = true, $config = false) {

        if($raw_array){
            $json = json_encode($data, $config);
        }elseif($raw_array == false && (Validator::isJSON($data))){
            $json = $data;
        }else{
            return false;
        }

        $out = ''; $nl = "\n"; $cnt = 0; $tab = 4; $len = strlen($json); $space = ' ';
        if($html) {
            $space = '&nbsp;';
            $nl    = '<br/>';
        }

        $k = strlen($space)?strlen($space):1;
        for ($i=0; $i<=$len; $i++) {
            $char = substr($json, $i, 1);
            if($char == '}' || $char == ']') {
                $cnt --;
                $out .= $nl . str_pad('', ($tab * $cnt * $k), $space);
            } else if($char == '{' || $char == '[') {
                $cnt ++;
            }
            $out .= $char;
            if($char == ',' || $char == '{' || $char == '[') {
                $out .= $nl . str_pad('', ($tab * $cnt * $k), $space);
            }
            if($char == ':') {
                $out .= ' ';
            }
        }

        return $out;
    }

    /**
     * @param array $data
     * @return string
     * @throws JsonException
     */
    function pretifyEncode(string $data)
    {
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function escapeUnicode($str){
        return json_decode((preg_replace('/\\\u([0-9a-z]{4})/', '&#x$1;', TypeConverter::toJson($str))));
    }

    public function toArray(string $jsonString, $parentId = 0)
    {
        $data = json_decode($jsonString, true);
        $out  = [];

        foreach ($data as $order => $datum) {
            $subArray = [];
            if (isset($datum['children'])) {
                $subArray = $this->toArray($datum['children'], $datum['id']);
            }
            $out[] = [
                'parent_id' => $parentId,
                'order'     => $order,
                'id'        => $datum['id'],
            ];
            $out = array_merge($out, $subArray);
        }

        return $out;
    }

}