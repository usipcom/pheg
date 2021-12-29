<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use Exception;

/**
 * Class Stats
 * @package Simtabi\Pheg\Toolbox
 *
 * @see     https://github.com/phpbench/phpbench/blob/master/lib/Math/Statistics.php
 */
final class Stats
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    /**
     * Returns the standard deviation of a given population.
     *
     * @param array $values
     * @param bool  $sample
     *
     * @return float
     */
    public function stdDev(array $values, bool $sample = false): float
    {
        $variance = $this->variance($values, $sample);
        return \sqrt($variance);
    }

    /**
     * Returns the variance for a given population.
     *
     * @param array $values
     * @param bool  $sample
     *
     * @return float
     */
    public function variance(array $values, bool $sample = false): float
    {
        $average = $this->mean($values);
        $sum = 0;

        foreach ($values as $value) {
            $diff = ($value - $average) ** 2;
            $sum += $diff;
        }

        if (count($values) === 0) {
            return 0;
        }

        return $sum / (count($values) - ($sample ? 1 : 0));
    }

    /**
     * Returns the mean (average) value of the given values.
     *
     * @param array|null $values
     * @return float
     */
    public function mean(?array $values): float
    {
        if (empty($values)) {
            return 0;
        }

        $sum = array_sum($values);

        if (!$sum) {
            return 0;
        }

        $count = count($values);

        return $sum / $count;
    }

    /**
     * Returns an array populated with $num numbers from $min to $max.
     *
     * @param float $min
     * @param float $max
     * @param int   $num
     * @param bool  $endpoint
     *
     * @return float[]
     */
    public function linSpace(float $min, float $max, int $num = 50, bool $endpoint = true): array
    {
        $range = $max - $min;

        if ($max === $min) {
            throw new Exception("Min and max cannot be the same number: {$max}");
        }

        $unit = $range / ($endpoint ? $num - 1 : $num);
        $space = [];

        for ($value = $min; $value <= $max; $value += $unit) {
            $space[] = $value;
        }

        if (!$endpoint) {
            array_pop($space);
        }

        return $space;
    }

    /**
     * Generate a histogram.
     *
     * Note this is not a great function, and should not be relied upon
     * for serious use.
     *
     * For a better implementation copy:
     *   http://docs.scipy.org/doc/numpy-1.10.1/reference/generated/numpy.histogram.html
     *
     * @param float[]    $values
     * @param int        $steps
     * @param float|null $lowerBound
     * @param float|null $upperBound
     *
     * @return array
     */
    public function histogram(
        array $values,
        int $steps = 10,
        ?float $lowerBound = null,
        ?float $upperBound = null
    ): array {
        if (empty($values)) {
            throw new Exception('Empty array of values is given');
        }

        $min = $lowerBound ?? min($values);
        $max = $upperBound ?? max($values);

        $range = $max - $min;

        $step = $range / $steps;
        $steps++; // add one extra step to catch the max value

        $histogram = [];

        $floor = $min;

        for ($i = 0; $i < $steps; $i++) {
            $ceil = $floor + $step;

            if (!isset($histogram[(string)$floor])) {
                $histogram[(string)$floor] = 0;
            }

            foreach ($values as $value) {
                if ($value >= $floor && $value < $ceil) {
                    $histogram[(string)$floor]++;
                }
            }

            $floor += $step;
        }

        return $histogram;
    }

    /**
     * Render human readable string of average value and system error
     *
     * @param array $values
     * @param int   $rounding
     * @return string
     */
    public function renderAverage(array $values, int $rounding = 3): string
    {
        $avg    = number_format($this->mean($values), $rounding);
        $stdDev = number_format($this->stdDev($values), $rounding);

        return "{$avg}±{$stdDev}";
    }
}
