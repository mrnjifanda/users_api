<?php

namespace App\Helpers;

use DateInterval;
use DateTime;

class Date
{
    private static function getDateTime(?string $date = null): DateTime
    {
        return new DateTime($date);
    }

    private static function getDateInterval(string $interval): DateInterval
    {
        return new DateInterval($interval);
    }

    private static function defaultFormat(?string $format = null): string
    {
        return $format ?? 'd M Y à H:i:s';
    }

    private static function add(string $date, int $period, string $designation, string $time, ?string $format = null): string
    {
        $addPeriod = self::getDateTime($date)->add(self::getDateInterval($time . $period . $designation));
        return $addPeriod->format(self::defaultFormat($format));
    }

    private static function addPeriod(string $date, int $period, string $designation, ?string $format = null): string
    {
        return self::add($date, $period, $designation, 'P', $format);
    }

    private static function addTime(string $date, int $period, string $designation, ?string $format = null): string
    {
        return self::add($date, $period, $designation, 'PT', $format);
    }

    public static function format(?string $date = null, ?string $format = null): string
    {
        return self::getDateTime($date)->format(self::defaultFormat($format));
    }

    public static function addYear(string $date, int $yearToAdd = 1, ?string $format = null): string
    {
        return self::addPeriod($date, $yearToAdd, 'Y', $format);
    }

    public static function addMonth(string $date, int $monthToAdd = 1, ?string $format = null): string
    {
        return self::addPeriod($date, $monthToAdd, 'M', $format);
    }

    public static function addWeek(string $date, int $weekToAdd = 1, ?string $format = null): string
    {
        return self::addPeriod($date, $weekToAdd, 'W', $format);
    }

    public static function addDay(string $date, int $dayToAdd = 1, ?string $format = null): string
    {
        return self::addPeriod($date, $dayToAdd, 'D', $format);
    }

    public static function addHour(string $date, int $hourToAdd = 1, ?string $format = null): string
    {
        return self::addTime($date, $hourToAdd, 'H', $format);
    }

    public static function addMinute(string $date, int $minuteToAdd = 1, ?string $format = null): string
    {
        return self::addTime($date, $minuteToAdd, 'M', $format);
    }

    public static function addSecond(string $date, int $secondToAdd = 1, ?string $format = null): string
    {
        return self::addTime($date, $secondToAdd, 'S', $format);
    }

    public static function equal(string $date, string $dateCompare): bool
    {
        return self::format($date) === self::format($dateCompare);
    }

    public static function superior(string $date, string $dateCompare): bool
    {
        return self::format($date) > self::format($dateCompare);
    }

    public static function inferior(string $date, string $dateCompare): bool
    {
        return self::format($date) < self::format($dateCompare);
    }

    public static function compareToDay (string $date): bool
    {
        return self::toDay() > $date;
    }

    public static function toDay(?string $format = null): string
    {
        return self::format(null, $format);
    }
}
