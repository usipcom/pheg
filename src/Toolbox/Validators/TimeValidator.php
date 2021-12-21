<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox\Traits\Validators;

use Carbon\Carbon;

class TimeValidator
{

    use WithRespectValidatorsTrait;

    /**
     * The Carbon time instance.
     *
     * @var Carbon
     */
    protected $carbonTime;

    /**
     * The difference in seconds between the Carbon time and current time.
     *
     * @var int
     */
    protected $diffInSeconds;

    /**
     * The timezone that will be used.
     *
     * @var string
     */
    protected $timezone;

    /**
     * Create a new Parser instance.
     *
     * @param Carbon $carbon
     * @param string $timezone
     * @return void
     */
    private function __construct(Carbon $carbon, $timezone = null)
    {
        $this->carbonTime = $carbon;
        $this->timezone   = $timezone;
        $this->setDifference($carbon);
    }



    /**
     * Determine if the difference is more than a minute.
     *
     * @return bool
     */
    protected function isMoreThanAMinute()
    {
        return $this->carbonTime->diffInSeconds >= 60;
    }
    
    /**
     * Determine if the difference is more than a hour.
     *
     * @return bool
     */
    protected function isMoreThanAHour()
    {
        return $this->diffInSeconds >= 3600;
    }
    
    /**
     * Determine if the difference is more than a day.
     *
     * @return bool
     */
    protected function isMoreThanADay()
    {
        return $this->diffInSeconds >= 86400;
    }
    
    /**
     * Determine if the difference is more than a week.
     *
     * @return bool
     */
    protected function isMoreThanAWeek()
    {
        return $this->diffInSeconds >= 604800;
    }
    
    /**
     * Determine if the Carbon time's year is different with current year.
     *
     * @return bool
     */
    protected function isTheYearDifferent()
    {
        return $this->carbonTime->year !== Carbon::now($this->timezone)->year;
    }

    public function isDateFormat($value = null, $format = 'MM/DD/YYYY')
    {
        // Datetime validation from http://www.phpro.org/examples/Validate-Date-Using-PHP.html
        if (empty($value)) {
            return false;
        }

        switch($format) {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
                list($y, $m, $d) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
                list($y, $d, $m) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
                list($d, $m, $y) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
                list($m, $d, $y) = preg_split('/[-\.\/ ]/', $value);
                break;

            case 'YYYYMMDD':
                $y = substr($value, 0, 4);
                $m = substr($value, 4, 2);
                $d = substr($value, 6, 2);
                break;

            case 'YYYYDDMM':
                $y = substr($value, 0, 4);
                $d = substr($value, 4, 2);
                $m = substr($value, 6, 2);
                break;

            default:
                return false;
                break;
        }
        if (checkdate($m, $d, $y)){
            return true;
        }

        return false;
    }

    public function isTimestamp($timestamp)
    {
        $check = (is_int($timestamp) OR is_float($timestamp))
            ? $timestamp
            : (string) (int) $timestamp;
        $status =  ($check === $timestamp)
        AND ( (int) $timestamp <=  PHP_INT_MAX)
        AND ( (int) $timestamp >= ~PHP_INT_MAX);

        if ($status){
            return true;
        }
        return false;
    }

    /**
     * @param $timestamp
     * @return bool
     */
    function isTimestampAlt($timestamp)
    {

        if(strtotime(date('d-m-Y H:i:s',$timestamp)) === (int)$timestamp) {
            return true;
        } else return false;

    }

    public function isZeroDate($value): bool
    {
        // http://stackoverflow.com/questions/8853956/check-if-date-is-equal-to-0000-00-00-000000
        if(empty($value) || (is_null($value))){
            $value = '0000-00-00';
        }
        switch (trim($value))
        {
            case '0000-00-00 00:00:00' : $status = true; break;
            case '0000-00-00'          : $status = true; break;
            default                    : $status = false; break;
        }

        if ($status){
            return true;
        }
        return false;
    }

    public function isDateTime($value, $format = 'Y-m-d H:i:s'): bool
    {
        if ($this->isDate($value, $format)){
            return true;
        }
        return false;
    }

    public function isDate($value, $format = 'Y-m-d'): bool
    {
        if(!empty($format)){
            if($this->respect()->date($format)->validate($value)){
                return true;
            }
        }elseif($this->respect()->date()->validate($value)){
            return true;
        }
        return false;
    }

    public function isYear($value): bool
    {
        if($this->respect()->date('Y')->validate($value)){
            return true;
        }
        return false;
    }

    public function isTimezone($value): bool
    {
        if(true === in_array($value, timezone_identifiers_list())){
            return true;
        }
        return false;
    }

    public function isValidTimeStamp($timestamp)
    {

        if(strtotime(date('d-m-Y H:i:s', $timestamp)) === (int)$timestamp) {
            return true;
        } else return false;

    }

    /**
     * Check if string is date or time
     *
     * @param string|null $date
     * @return bool
     *
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function isDateOrTime(?string $date): bool
    {
        $time = strtotime((string)$date);
        return $time > 0;
    }

    /**
     * Returns true if date passed is within this week.
     *
     * @param string|int $time
     * @return bool
     */
    public function isThisWeek($time): bool
    {
        return (self::factory($time)->format('W-Y') === self::factory()->format('W-Y'));
    }

    /**
     * Returns true if date passed is within this month.
     *
     * @param string|int $time
     * @return bool
     */
    public function isThisMonth($time): bool
    {
        return (self::factory($time)->format('m-Y') === self::factory()->format('m-Y'));
    }

    /**
     * Returns true if date passed is within this year.
     *
     * @param string|int $time
     * @return bool
     */
    public function isThisYear($time): bool
    {
        return (self::factory($time)->format('Y') === self::factory()->format('Y'));
    }

    /**
     * Returns true if date passed is tomorrow.
     *
     * @param string|int $time
     * @return bool
     */
    public function isTomorrow($time): bool
    {
        return (self::factory($time)->format('Y-m-d') === self::factory('tomorrow')->format('Y-m-d'));
    }

    /**
     * Returns true if date passed is today.
     *
     * @param string|int $time
     * @return bool
     */
    public function isToday($time): bool
    {
        return (self::factory($time)->format('Y-m-d') === self::factory()->format('Y-m-d'));
    }

    /**
     * Returns true if date passed was yesterday.
     *
     * @param string|int $time
     * @return bool
     */
    public function isYesterday($time): bool
    {
        return (self::factory($time)->format('Y-m-d') === self::factory('yesterday')->format('Y-m-d'));
    }

    public function isDateGreater($date, $defaultDate = ''): bool
    {

        $date = strtotime($date);
        if(empty($defaultDate)){
            $default = strtotime($this->getCurrentTime());
        }else{
            $default = strtotime($defaultDate);
        }

        if($date > $default) {
            echo '<span class="status expired">Expired</span>';
            return true;
        }

        return false;
    }
}
