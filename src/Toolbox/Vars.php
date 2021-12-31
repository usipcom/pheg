<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use Exception;
use NumberFormatter;
use Simtabi\Pheg\Toolbox\Transfigures\Transfigure;

final class Vars
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    public function numberFormat($number, $decimals = 0): string
    {
        return number_format($number, $decimals);
    }

    /**
     * Converts a number into a given format.
     *
     * @param int|float|string $number The number.
     * @param int $decPlaces           Number of decimal places.
     * @param string $decSep           The character to separate decimals.
     * @param string $thouSep          The character to separate thousands.
     * @return string
     */
    public function numberFormatTo($number, int $decPlaces = 2, string $decSep = '.', string $thouSep = ''): string
    {
        $number         = preg_replace('/\s+/', '', (string) $number);
        $numberArr      = str_split((string) $number);
        $numberArrRev   = array_reverse($numberArr);
        $decPointIsHere = '';

        foreach ($numberArrRev as $key => $value) {
            if (!is_numeric($value) && $decPointIsHere === '') {
                $decPointIsHere = $key;
            }
        }

        if ($decPointIsHere !== '') {
            $numberArrRev[$decPointIsHere] = '.';
        }

        foreach ($numberArrRev as $key => $value) {
            if (!is_numeric($value) && $key > $decPointIsHere) {
                unset($numberArrRev[$key]);
            }
        }

        $numberArr   = array_reverse($numberArrRev);
        $numberClean = implode('', $numberArr);
        $numberClean = (float) $numberClean;

        return number_format($numberClean, $decPlaces, $decSep, $thouSep);
    }

    public function numberZeroFill ($num, $zerofill = '1')
    {
        return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
    }

    public function numberRandomize($minimum, $maximum)
    {
        return mt_rand($minimum, $maximum);
    }

    public function numberBreakdown($number, $getUnsigned = false)
    {
        $negative = 1;
        if ($number < 0)
        {
            $negative = -1;
            $number  *= -1;
        }

        // get unsigned
        if ($getUnsigned){
            $data = [
                'whole' => floor($number),
                'float' => ($number - floor($number))
            ];
        }
        else{
            $data = [
                'whole' => floor($number) * $negative,
                'float' => ($number - floor($number)) * $negative,
            ];
        }

        return $data;
    }

    public function numberToPercentage(int $value, bool $inverse = false): int
    {
        return  ($inverse ? 1 - $value : $value) * 100;
    }

    public function numberPercentage($number, $total, $precision = 2)
    {

        //  variables
        $number = (float) $number;
        $total  = (float) $total;

        // if number is greater than total
        if ($number > $total){
            return false;
        }

        // calculate
        $out = $number / ($total / 100);
        if (false === $precision){
            $out = round($out,2);
        }
        elseif ($precision > 0){
            $out = round($out,$precision);
        }
        else{
            $out = round($out);
        }

        return $out;
    }

    public function numberAddOrdinalSuffix($number)
    {

        $ord = null;

        if (!in_array(($number % 100), array(11,12,13))){
            $ord = match ($number % 10) {
                1       => 'st',
                2       => 'nd',
                3       => 'rd',
                default => 'th',
            };
        }

        return Transfigure::invoke()->toObject([
            'string'  => !empty($ord) ? $number . $ord : null,
            'ordinal' => $ord,
            'number'  => $number,
        ]);
    }

    public function numberGeneratePercentages($number, $total, $precision = 2){

        //  variables
        $total = (float) $total;

        // if number is greater than total
        if ($number > $total){
            return false;
        }

        // calculate
        $out = (float) $number / ($total / 100);
        if (false === $precision){
            $out = round($out,2);
        }
        elseif ($precision > 0){
            $out = round($out,$precision);
        }
        else{
            $out = round($out);
        }

        return $out;
    }

    public function generateRandomNumber(int $length = 12, $power = null){
        $output  = null;
        $pattern = "0123456789";

        if($power !== null){
            srand((double)microtime()*1000000*$power);
        }else{
            srand((double)microtime()*1000000);
        }

        for($i = 0; $i <$length; $i++) {
            $output.= $pattern[rand()%strlen($pattern)];
        }
        return $output;
    }

    public function generateRandomNumberRange($end, $start = 0, $step = 10){
        // http://php.net/manual/en/function.range.php
        $ranges = [];
        foreach ( range($start, $end, $step) as $item ) {
            $ranges[] = $item;
        }
        return $ranges;
    }

    public function numberRoundOffToNearest ( $value, $precision = 2 ): float|int
    {
        $pow = pow ( 10, $precision );
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
    }

    public function numberEnsureIsFloat(int $value): float|int
    {
        return !empty($value) && (is_integer($value) || is_numeric($value) || is_float($value)) ? (float) $value : 0;
    }

    public function formatPrice($amount, $currency_iso = 'KES', $locale_iso = 'en_GB'): string
    {

        $amount   = floatval($amount);
        $currency = $currency_iso;
        $fmt      = new NumberFormatter($locale_iso,  NumberFormatter::CURRENCY);
        $fmt->setTextAttribute(\NumberFormatter::CURRENCY_CODE, 'EUR');
        $fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
        return $fmt->formatCurrency($amount, $currency) . PHP_EOL;
    }

    /**
     * Limits the number between two bounds.
     *
     * @param float $number
     * @param float $min
     * @param float $max
     * @return int
     */
    public function numberLimitToMinMax(float $number, float $min, float $max): int
    {
        return $this->numberMax($this->numberMin($number, $min), $max);
    }

    /**
     * Increase the number to the minimum if below threshold.
     *
     * @param float $number
     * @param float $min
     * @return int
     */
    public function numberMin(float $number, float $min): int
    {
        return (int)max($number, $min); // Not a typo
    }

    /**
     * Decrease the number to the maximum if above threshold.
     *
     * @param float $number
     * @param float $max
     * @return int
     */
    public function numberMax(float $number, float $max): int
    {
        return (int)min($number, $max); // Not a typo
    }

    /**
     * Returns true if the number is outside the min and max.
     *
     * @param float $number
     * @param float $min
     * @param float $max
     * @return bool
     */
    public function isOutsideMinMax(float $number, float $min, float $max): bool
    {
        return ($number < $min || $number > $max);
    }

    /**
     * Get relative percent
     *
     * @param float $normal
     * @param float $current
     * @return string
     */
    public function numberRelativePercent(float $normal, float $current): string
    {
        if (!$normal || $normal === $current) {
            return '100';
        }

        $percent = round($current / abs($normal) * 100);

        return number_format($percent, 0, '.', ' ');
    }

    /**
     * Ensures $value is always within $min and $max range.
     * If lower, $min is returned. If higher, $max is returned.
     *
     * @param float $value
     * @param float $min
     * @param float $max
     *
     * @return int
     */
    public function numberEnsureRange(float $value, float $min, float $max): int
    {
        $filter = Filter::invoke();
        return $this->numberLimitToMinMax($filter->int($value), $filter->int($min), $filter->int($max));
    }


}