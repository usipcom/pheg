<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use InvalidArgumentException;

final class Base64
{

    public static function invoke(): self
    {
        return new self();
    }

    public function jsonEncode($str, $base64 = true){

        if(true === $base64){
            return base64_encode(json_encode($str));
        }
        return json_encode($str);
    }

    public function jsonDecode($str, $base64 = true){

        if(true === $base64){
            return json_decode(base64_decode($str), true);
        }
        return json_encode($str, true);
    }

    public function imageEncode($path){
        if(!empty($path) && (file_exists($path))){
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return false;
    }


    /**
     * @param string $data       The data to encode
     * @param bool   $usePadding If true, the "=" padding at end of the encoded value are kept, else it is removed
     *
     * @return string The data encoded
     */
    public function encode(string $data, bool $usePadding = false): string
    {
        $encoded = strtr(base64_encode($data), '+/', '-_');

        return true === $usePadding ? $encoded : rtrim($encoded, '=');
    }

    /**
     * @param string $data The data to decode
     *
     * @throws InvalidArgumentException
     *
     * @return string The data decoded
     */
    public function decode(string $data): string
    {
        $decoded = base64_decode(strtr($data, '-_', '+/'), true);
        if (false === $decoded) {
            throw new InvalidArgumentException('Invalid data provided');
        }

        return $decoded;
    }
}