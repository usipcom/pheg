<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use gugglegum\MemorySize\Exception;
use gugglegum\MemorySize\Formatter;
use gugglegum\MemorySize\Parser;

final class Humanize
{

    private static $fileSizeFormatter;
    private static $fileSizeParser;
    private static $errors = [];

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function parse($bytes){
        try{
            $sp = new Parser();
            return $sp->parse($bytes);
        }catch (Exception $exception){
            self::setErrors([$exception->getMessage()]);
        }
        return false;
    }

    public function formatSize($number){
        try{
            $sf = new Formatter();
            return $sf->format($number);
        }catch (Exception $exception){
            self::setErrors([$exception->getMessage()]);
        }
        return false;
    }

    public function formatBytes(int $bytes, $precision = 2): string
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return number_format($bytes, $precision, ',', '.') . ' ' . $units[$pow];
    }

    public function toBytes($size)
    {

        $units = [
            'K' => 1024,
            'M' => 1024 * 1024,
            'G' => 1024 * 1024 * 1024,
            'T' => 1024 * 1024 * 1024 * 1024,
            'P' => 1024 * 1024 * 1024 * 1024 * 1024,
        ];

        $bytes = (float) $size;

        if (preg_match('~([KMGTP])$~si', rtrim($size, 'B'), $matches) && !empty($units[strtoupper($matches[1])])) {
            $bytes *= $units[strtoupper($matches[1])];
        }

        return (int) round($bytes, 2);
    }

    public function toSize($bytes, $format = false) {
        $sizes = [
            'b'  => 'Bit',
            'B'  => 'Bytes',
            'KB' => 'Kilobytes',
            'MB' => 'Megabytes',
            'GB' => 'Gigabytes',
            'TB' => 'Terabytes',
            'PB' => 'Petytes',
            'EB' => 'Exabytes',
            'ZB' => 'Zettabytes',
            'YB' => 'Yottabytes'
        ];

        ## - set and check if file doesn't exceed the maximum Yottabyte file size
        $max_Yt = 1208925801182629174706176;
        $space  = ( $format === true ? ' ' : '' );
        if($bytes <= $max_Yt){
            if ($bytes == 0){
                $formatted = '0'.$space.'Bytes';
            }else{
                $formatted = round($bytes/pow(1024, ($i = floor(log($bytes, 1024)))), 2) .$space. ucfirst(strtolower($sizes[$i]));
            }

            return $formatted;
        }
        return('File exceeds maximum allowed size!');
    }

    public function stringToBytes($string) {

        $string = ucfirst(strtolower($string));
        $sizes = [
            'B'  => 0,
            'KB' => 1,
            'MB' => 2,
            'GB' => 3,
            'TB' => 4,
            'PB' => 5,
            'EB' => 6,
            'ZB' => 7,
            'YB' => 8
        ];

        $string_unit = strtoupper(trim(substr($string, -2)));
        if (intval($string_unit) !== 0) {
            $string_unit = 'B';
        }

        if (!in_array($string_unit, array_keys($sizes))) {
            return false;
        }

        $units = trim(substr($string, 0, strlen($string) - 2));
        if (!intval($units) == $string_unit) {
            return false;
        }

        return $units * pow(1024, $sizes[$string_unit]);
    }

}