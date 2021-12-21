<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

class NumberValidator
{

    use WithRespectValidatorsTrait;

    public function isOddNumber(int $number): bool
    {
        return ($number % 2 == 0) ? true : false;
    }

    public function isNotANumber(int $number): bool
    {
        $number = !empty($number) && (
            is_integer($number) || is_numeric($number) || is_float($number)
        ) ? (float) $number : 0;

        return ($number === 0) ? true : false;
    }

    /**
     * Returns true if the number is within the min and max.
     *
     * @param float $number
     * @param float $min
     * @param float $max
     * @return bool
     */
    public function isIn(float $number, float $min, float $max): bool
    {
        return ($number >= $min && $number <= $max);
    }

    /**
     * Is the current value even?
     *
     * @param int $number
     * @return bool
     */
    public function isEven(int $number): bool
    {
        return ($number % 2 === 0);
    }

    /**
     * Is the current value negative; less than zero.
     *
     * @param float $number
     * @return bool
     */
    public function isNegative(float $number): bool
    {
        return ($number < 0);
    }

    /**
     * Is the current value odd?
     *
     * @param int $number
     * @return bool
     */
    public function isOdd(int $number): bool
    {
        return !$this->isEven($number);
    }

    /**
     * Is the current value positive; greater than or equal to zero.
     *
     * @param float $number
     * @param bool  $zero
     * @return bool
     */
    public function isPositive(float $number, bool $zero = true): bool
    {
        return ($zero ? ($number >= 0) : ($number > 0));
    }

}