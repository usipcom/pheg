<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use DateTime;
use DateTimeZone;
use Exception;
use Moment\Moment;
use Carbon\Carbon;
use Simtabi\Pheg\Core\Exceptions\PhegException;
use Westsworld\TimeAgo;

final class Dates
{
    public const MINUTE     = 60;
    public const HOUR       = 3600;
    public const DAY        = 86400;
    public const WEEK       = 604800;      // 7 days
    public const MONTH      = 2592000;     // 30 days
    public const YEAR       = 31536000;    // 365 days

    public const SQL_FORMAT = 'Y-m-d H:i:s';
    public const SQL_NULL   = '0000-00-00 00:00:00';

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    /**
     * Build PHP DateTime object from mixed input
     *
     * @param mixed $time
     * @param null $timezone
     * @return DateTime
     * @throws Exception
     */
    public function factory($time = null, $timezone = null): DateTime
    {
        $timezone = $this->getTimezoneObject($timezone);

        if ($time instanceof DateTime) {
            return $time->setTimezone($timezone);
        }

        $dateTime = new DateTime('@' . $this->convertToTimestamp($time));
        $dateTime->setTimezone($timezone);

        return $dateTime;
    }

    public function getCurrentTime($asTimestamp = false, $format = "Y-m-d H:i:s", $defaultTime = null, $timezone = "Africa/Nairobi") {

        $objDateTime = new DateTime();
        $objDateTime->setTimezone(new DateTimeZone($timezone));

        if (!empty($defaultTime)) {
            $floatUnixTime = (is_string($defaultTime)) ? strtotime($defaultTime) : $defaultTime;
            if (method_exists($objDateTime, "setTimestamp")) {
                $objDateTime->setTimestamp($floatUnixTime);
            }
            else {
                $arrDate = getdate($floatUnixTime);
                $objDateTime->setDate($arrDate['year'],  $arrDate['mon'],     $arrDate['day']);
                $objDateTime->setTime($arrDate['hours'], $arrDate['minutes'], $arrDate['seconds']);
            }
        }

        return $asTimestamp ? strtotime($objDateTime->format($format)): $objDateTime->format($format);

    }

    public function getTimezones(): array
    {

        $lastRegion = null;
        $timezones  = DateTimeZone::listIdentifiers();
        $grouped    = [];
        $formed     = [];
        $flat       = [];

        $formatName = function ($name) {
            $name = str_replace('/', '_', $name);
            $name = str_replace('-', '_', $name);
            return strtolower(trim($name));
        };

        if (is_array($timezones)) {
            foreach ($timezones as $key => $timezone) {

                $dateTimeZone = new DateTimeZone($timezone);
                $expTimezone  = explode('/', $timezone);

                // Let's sample the time there right now
                $currentTime  = new DateTime('', $dateTimeZone);
                if (isset($expTimezone[0])) {
                    if ($expTimezone[0] !== $lastRegion) {
                        $lastRegion = $expTimezone[0];
                    }
                    $getOffset = $this->formatDisplayOffset($dateTimeZone->getOffset(new DateTime()));
                    $grouped[$formatName($lastRegion)][$formatName($timezone)] = [
                        'timezone' => $timezone,
                        'offset'   => $getOffset,
                        'time'     => [
                            'military' => $currentTime->format('H:i'),
                            // Americans can't handle 24hrs, so we give them am pm time
                            'am_pm'    => $currentTime->format('H') > 12 ? $currentTime->format('g:i a') : null,
                        ],
                    ];
                    $formed[$lastRegion][$formatName($timezone)] = $timezone ." (". $getOffset . ")";
                    $flat[$formatName($timezone)]   = $timezone ." (". $getOffset . ")";
                    unset($getOffset);
                }
                unset($dateTimeZone, $expTimezone);
            }
            unset($key, $timezone);
        }

        unset($lastRegion, $timezones);

        return [
            'grouped' => $grouped,
            'formed'  => $formed,
            'flat'    => $flat,
        ];
    }

    public function getTimezoneObject($timezone = null): DateTimeZone
    {
        if ($timezone instanceof DateTimeZone) {
            return $timezone;
        }

        $timezone = $timezone ?: date_default_timezone_get();

        return new DateTimeZone($timezone);
    }

    public function getTimeAgo($time, $fromTimestamp = false, $tense = "ago"){

        return (new TimeAgo())->inWordsFromStrings($time);

        if(empty($time)) return "n/a";
        $time       = true === $fromTimestamp ? $time : strtotime($time);
        $periods    = ["second", "minute", "hour", "day", "week", "month", "year", "decade"];
        $lengths    = ["60","60","24","7","4.35","12","10"];
        $now        = time();
        $difference = $now - $time;
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if($difference != 1) {
            $periods[$j].= "s";
        }
        return "$difference $periods[$j] $tense ";
    }

    public function getTimeBasedGreetings($timezone = 'Europe/London'): string | bool
    {

        $time = (Carbon::now(new DateTimeZone($timezone)))->hour;

        /* If the time is less than 1200 hours, show good morning */
        if ($time < 12)
        {
            $greetings = "Good morning";
        }

        /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
        elseif ($time >= 12 && $time < 17)
        {
            $greetings = "Good afternoon";
        }

        /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
        elseif ($time >= 17 && $time < 19)
        {
            $greetings = "Good evening";
        }

        /* Finally, show good night if the time is greater than or equal to 1900 hours */
        elseif ($time >= 19)
        {
            $greetings = "Good night";
        }

        return $greetings ?? false;
    }

    public function getDateDifference($end, $start, $endTimeZone = 'Africa/Nairobi', $startTimeZone = 'Africa/Nairobi'){
        $moment = new Moment($end, $endTimeZone);
        return $moment->from($start, $startTimeZone);
    }

    public function getDateTimeDifference($endTime, $startTime, $twoDigitView = false){

        $fmt  = 'Y-m-d H:i:s';
        $str  = $this->convertToSimpleTime($startTime, $fmt);
        $now  = new DateTime($str);
        $end  = $this->convertToSimpleTime($endTime, $fmt);
        $ref  = new DateTime($end);
        $diff = $now->diff($ref);

        // build formats
        if ($twoDigitView){
            $_y     = $diff->format("%Y");
            $y_s    = $diff->format("%Y years");

            $_mn    = $diff->format("%a");
            $mn_s   = $diff->format("%a months");

            $_d     = $diff->format("%D");
            $d_s    = $diff->format("%D days");

            $_h     = $diff->format("%H");
            $h_s    = $diff->format("%H hours");

            $_m     = $diff->format("%I");
            $m_s    = $diff->format("%I minutes");

            $_s     = $diff->format("%S");
            $s_s    = $diff->format("%S seconds");

            $string = $diff->format("%Y years %a months %D days %H hours %I minutes %S seconds");
        }
        else{
            $_y     = $diff->format("%y");
            $y_s    = $diff->format("%y years");

            $_mn    = $diff->format("%a");
            $mn_s   = $diff->format("%a months");

            $_d     = $diff->format("%y");
            $d_s    = $diff->format("%y days");

            $_h     = $diff->format("%i");
            $h_s    = $diff->format("%i hours");

            $_m     = $diff->format("%i");
            $m_s    = $diff->format("%i minutes");

            $_s     = $diff->format("%s");
            $s_s    = $diff->format("%s seconds");

            $string = $diff->format("%y years %a months %d days %h hours %i minutes %s seconds");
        }

        return TypeConverter::toObject(array(
            'years' => array(
                'digits' => $_y,
                'string' => $y_s,
            ),
            'months' => array(
                'digits' => $_mn,
                'string' => $mn_s,
            ),
            'days' => array(
                'digits' => $_d,
                'string' => $d_s,
            ),
            'hours' => array(
                'digits' => $_h,
                'string' => $h_s,
            ),
            'minutes' => array(
                'digits' => $_m,
                'string' => $m_s,
            ),
            'seconds' => array(
                'digits' => $_s,
                'string' => $s_s,
            ),

            'string' => $string,
        ));
    }

    public function getYearsInRange($endYear = '', $startYear = 1900, $sort = true){

        // Year to start available options at
        if(empty($startYear)){
            $startYear = 1900;
        }
        if(true !== Pheg()->getValidator()->isYear($startYear)){
            $startYear = 1900;
        }

        // Set your latest year you want in the range, in this case we use PHP to
        # just set it to the current year.
        if(empty($endYear)){
            $endYear = date('Y');
        }
        if(true !== Pheg()->getValidator()->isYear($endYear)){
            $endYear = date('Y');
        }

        // build year ranges
        $years = range( $endYear, $startYear );
        $out = array();
        for($i = 0; $i < count($years); $i++){
            $out[$years[$i]] = $years[$i];
        }

        // if sort
        if($sort){
            natsort($out);
            $out = array_reverse($out, true);
        }
        return $out;
    }

    public function getYearsInRangeByOrder($startYear = 1900, $endYear = null, $sort = false){

        $currentYear = empty($endYear) ? date('Y') : $endYear;

        // range of years
        $years = range($startYear, $currentYear);

        // if sort
        if($sort){
            natsort($years);
            $years = array_reverse($years, true);
        }
        return $years;
    }

    public function getDateOrdinalSuffix(){
        return date('M j<\s\up>S</\s\up> Y'); // >= PHP 5.2.2
    }

    /**
     * Creating date collection between two dates
     *
     * Example 1
     * date_range("2014-01-01", "2014-01-20", "+1 day", "m/d/Y");
     *
     * Example 2 - you can use even time
     * date_range("01:00:00", "23:00:00", "+1 hour", "H:i:s");
     *
     * @author Ali OYGUR <alioygur@gmail.com>
     * @param string since any date, time or datetime format
     * @param string until any date, time or datetime format
     * @param string step
     * @param string date of output format
     * @return array
     */
    public function getIndexedDatesInArray($from, $to, $step = '+1 day', $outputFormat = 'Y-m-d'): array
    {
        $dates   = [];
        $current = strtotime($from);
        $last    = strtotime($to);

        while($current <= $last) {
            $dates[] = date($outputFormat, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    /**
     * Create associative date collection between two dates
     *
     * Example 1
     * date_range("2014-01-01", "2014-01-20", 0, "+1 day", "m/d/Y");
     *
     * Example 2 - you can use even time
     * date_range("01:00:00", "23:00:00", 0, "+1 hour", "H:i:s");
     *
     * @param $from
     * @param $to
     * @param null $default
     * @param string $step
     * @param string $outputFormat
     * @return array
     */
    public function getAssociativeDatesInArray($from, $to, $default = null, string $step = '+1 day', string $outputFormat = 'Y-m-d'): array
    {
        $dates = $this->getIndexedDatesInArray($from, $to, $step, $outputFormat);
        return array_fill_keys($dates, $default);
    }

    /**
     * Convert to timestamp
     *
     * @param string|int|DateTime|null $time
     * @param bool                     $currentIsDefault
     * @return int
     */
    public function convertToTimestamp($time = null, bool $currentIsDefault = true): int
    {
        if ($time instanceof DateTime) {
            return (int)$time->format('U');
        }

        if (null !== $time) {
            $time = is_numeric($time) ? (int)$time : (int)strtotime($time);
        }

        if (!$time) {
            $time = $currentIsDefault ? time() : 0;
        }

        return $time;
    }

    public function convertYearsToSeconds($value = '1'){
        return ceil($value * 31536000);
    }

    public function convertMonthsToSeconds($value = '1'){
        return ceil($value * 2592000);
    }

    public function convertWeeksToSeconds($value = '1'){
        return ceil($value * 604800);
    }

    public function convertDaysToSeconds($value = '1'){
        return $value * (24*(60*60));
    }

    public function convertHoursToSeconds($value){
        return $value * (60*60);
    }

    public function convertMinutesToSeconds($value){
        return $value *60;
    }

    public function convertTimestamp(int $timestamp, $format = 'j M Y H:i'): ?string
    {
        $baseTime  = Carbon::create(0000, 0, 0, 00, 00, 00);
        $timestamp = Carbon::parse($timestamp);
        if ($timestamp->lte($baseTime)) {
            return null;
        }

        return $timestamp->format($format);
    }

    public function convertSecondsToTime(float $seconds, int $minValuableSeconds = 2): string
    {
        if ($seconds < $minValuableSeconds) {
            return number_format($seconds, 3) . ' sec';
        }

        return gmdate('H:i:s', (int)round($seconds, 0)) ?: '';
    }

    public function convertMinutesToTime(int $minutes)
    {
        $minutes_per_day = (Carbon::HOURS_PER_DAY * Carbon::MINUTES_PER_HOUR);
        $days            = floor($minutes / ($minutes_per_day));
        $hours           = floor(($minutes - $days * ($minutes_per_day)) / Carbon::MINUTES_PER_HOUR);
        $mins            = (int) ($minutes - ($days * ($minutes_per_day)) - ($hours * 60));

        return "{$days} Days {$hours} Hours {$mins} Mins";
    }

    public function convertStringToDate($string, $fromFormat = 'Y-m-d', $toFormat = 'F j, Y')
    {
        $date = DateTime::createFromFormat($fromFormat, $string);
        return ($date instanceof DateTime) ? $date->format($toFormat) : '';
    }

    public function convertToSqlFormat(string $time, $forSql = true, $readFormat = self::SQL_FORMAT, $storeFormat = self::SQL_FORMAT): string
    {

        $time = str_replace( "/", "-", trim($time));
        $time = str_replace( ",", "-", $time);
        $time = str_replace( ".", "-", $time);
        $time = strtotime( $time );

        if ($forSql) {
            return $this->factory($time)->format($storeFormat);
        }

        return $this->factory($time)->format($readFormat);
    }

    public function convertToSimpleTime($time, $outputFormat = 'M j, Y g:i a', $inputFormat = 'Y-m-d H:i:s', $timezone = "Africa/Nairobi"){

        // set default fallback format
        $inputFormat = empty($inputFormat) ? 'Y-m-d H:i:s' : $inputFormat;

        // init date object
        $dateObj     = new DateTime();
        $dateObj->setTimezone(new DateTimeZone($timezone));

        $time        = $dateObj->setTimestamp($time)->format($inputFormat);
        $formatted   = DateTime::createFromFormat($inputFormat, $time);
        if($formatted && $formatted->format($inputFormat) == $time){
            return (new DateTime($time))->format($outputFormat);
        }
    }

    public function convertTime($datetime, string $format = 'M jS, Y H:i T', string $timezone = 'America/New_York'): string
    {
        return $this->factory($datetime, $timezone)->format($format);
    }

    public function formatDisplayOffset($offset, $showUTC = true): ?string
    {
        $initial = new DateTime();
        $initial->setTimestamp(abs($offset));

        return ($showUTC === true ? "UTC " : null) . ($offset >= 0 ? '+':'-') . $initial->format('H:i');
    }

    public function createCarbonObjectFromString(string $string): Carbon
    {
        return Carbon::parse($string);
    }

    public function evaluateCertainTime($dateTimeStr, $operand = '>', $datetimeFormat = "Y-m-d H:i:s")
    {
        $timeNow = new DateTime($this->getCurrentTime(true, $datetimeFormat));
        $timeAgo = new DateTime($dateTimeStr);

        return match (strtolower($operand)) {
            '>'  => ($timeAgo > $timeNow),
            '>=' => ($timeAgo >= $timeNow),
            '<'  => ($timeAgo < $timeNow),
            '<=' => ($timeAgo <= $timeNow),
            default => throw new PhegException('Operand not set or is invalid'),
        };
    }

}
