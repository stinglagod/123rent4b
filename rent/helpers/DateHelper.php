<?php


namespace rent\helpers;


class DateHelper
{
    /**
     * Возращается месяцгод. Например, за месяц год берется первая секунда месяца в unixtime
     * @param int $datetime
     * @return int
     */
    public static function getMonthyear($datetime):int
    {
        if (is_string($datetime)) {
            $datetime=strtotime($datetime);
        }
//        return date('nY',$datetime);
        return self::beginMonthDayByUnixTime($datetime);
    }

    /**
     * Возращается первую секунду Месяца
     * @param int $unixTime
     * @return int
     */
    public static function beginMonthDayByUnixTime(int $unixTime):int
    {
        $dto = new \DateTime();
        $dto->setTimestamp($unixTime);
        $dto->modify("first day of this month midnight");
        return $dto->getTimestamp();
    }

    /**
     * Возращается последнию секунду Месяца
     * @param int $unixTime
     * @return int
     */
    public static function lastMonthDayByUnixTime(int $unixTime):int
    {
        $dto = new \DateTime();
        $dto->setTimestamp($unixTime);
        $dto->modify("first day of next month midnight - 1 sec");
        return $dto->getTimestamp();
    }

    /**
     * Возращает последний миллисекунд месяца
     * @param int $unixTime_m
     * @return int
     */
    public static function lastMonthDayByUnixTime_m(int $unixTime_m):int
    {
        $dto = new \DateTime();
        $dto->setTimestamp(intval($unixTime_m/1000));
        $dto->modify("first day of next month midnight - 1 sec");
        return intval($dto->getTimestamp().'999');
    }

    /**
     * Возращает первую секунду месяца минус $numMonth месяцев назад
     * @param int $unixTime
     * @param $numMonth
     * @return int
     * @throws \Exception
     */
    public static function beginMonthDayByUnixTimeMinusMonth(int $unixTime, int $numMonth):int
    {

        for ($i=1; $i<=$numMonth;$i++) {
            $unixTime=self::beginMonthDayByUnixTime($unixTime-1);
        }
        return $unixTime;
    }

    /**
     * Возращается первую секунду Недели
     * @param int $unixTime
     * @return int
     */
    public static function beginWeekDayByUnixTime(int $unixTime):int
    {
        $dto = new \DateTime();
        $dto->setTimestamp($unixTime);
        $dto->modify("Monday this week midnight");

        return $dto->getTimestamp();
    }

    public static function beginWeekDay($date):int
    {
        if (is_int($date)) {
            return self::beginWeekDayByUnixTime($date);
        } else if (is_string($date)) {
            if ($date=strtotime($date)) {
                return self::beginWeekDayByUnixTime($date);
            }
        }
        throw new \DomainException('Date failed');
    }

    /**
     * Возращается последнию секунду Недели
     * @param int $unixTime
     * @return int
     */
    public static function lastWeekDayByUnixTime(int $unixTime):int
    {
        $dto = new \DateTime();
        $dto->setTimestamp($unixTime);
        $dto->modify("Monday next week midnight - 1 sec");
        return $dto->getTimestamp();
    }
    public static function lastWeekDayByUnixTime_m(int $unixTime_m):int
    {
        $dto = new \DateTime();
        $dto->setTimestamp(intval($unixTime_m/1000));
        $dto->modify("Monday next week midnight - 1 sec");
        return intval($dto->getTimestamp().'999');
    }

    public static function lastWeekDay($date):int
    {
        if (is_int($date)) {
            return self::lastWeekDayByUnixTime($date);
        } else if (is_string($date)) {
            if ($date=strtotime($date)) {
                return self::lastWeekDayByUnixTime($date);
            }
        }
        throw new \DomainException('Date failed');
    }

    public static function formatWeek_numDayYear(int $unixTime):string
    {
        return date('W-y',$unixTime);
    }
    public static function formatWeek_numDayYearBeginEndArray(int $unixTime):array
    {
        $numberWeek=date('W',$unixTime);
        $lastDay=self::lastWeekDayByUnixTime($unixTime);
        if (date('n',$unixTime) != date('n',$lastDay)) {
            $begin=date('j.n',$unixTime);
            $end=date('j.n',$lastDay);
        } else {
            $begin=date('j',$unixTime);
            $end=date('j.n',$lastDay);
        }
        return [$numberWeek,$begin.'-'.$end];
    }
    public static function formatWeek_numDay(int $unixTime):string
    {
        return date('W',$unixTime);
    }
    public static function formatWeek_beginEndWeekDay(int $unixTime):string
    {
        return self::formatWeek_numDayYearBeginEndArray($unixTime)[1];
    }
    public static function beginDay(int $unixTime=null):int
    {
        if (empty($unixTime)) {
            $unixTime=time();
        }
        return strtotime("today", $unixTime);
    }
    public static function endDay(int $unixTime=null):int
    {
        if (empty($unixTime)) {
            $unixTime=time();
        }
        return strtotime("tomorrow", $unixTime) - 1;
    }

    public static function beginDaysByPeriod(int $begin, int $end):array
    {
        $days=[];
        if (self::beginDay($begin)==$begin) {
            $day = $begin;
        } else {
            $day = self::endDay($begin) + 1;
        }
        do {
            $days[]=$day;
            $day=$day+(24*60*60);
        } while($day<$end);

        return $days;

    }

    /**
     * Определяет является ли время полной моинуто. например 26.01.2022 16:07:00
     * @param int $time       миллисекунды или секунды
     * @return bool
     */
//    public static function isMinute(int $time):bool
//    {
//        if (self::isMilliseconds($time)) {
////            dump($time%1000);exit;
////            if (($time%1000)==0) {
//                $time = $time/1000;
////            } else {
////                return false;
////            }
//        }
//        return intval(date("s",$time))==0;
//    }

    /**
     * Получаем минуту. Отбрасываем секунд и миллисекунды
     * @param int $time
     * @return int
     */
    public static function getMinute(int $time): int
    {
        return strtotime(date("y-m-d H:i:00",$time));
    }
###

    /**
     * определяем $time это секунды или не секкнды..
     * Сравниваем с датой 01 2020 00:00:00 GMT+0000
     * @param int $time
     * @return bool
     */
    public static function isMilliseconds(int $time):bool
    {
        return ($time > 157783680000);
    }
}