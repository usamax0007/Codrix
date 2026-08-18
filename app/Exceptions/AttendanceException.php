<?php

namespace App\Exceptions;

use Exception;

class AttendanceException extends Exception
{
    public static function alreadyCheckedIn(): self
    {
        return new self('You are already checked in. Please check out first.');
    }

    public static function notCheckedIn(): self
    {
        return new self('You are not checked in.');
    }

    public static function checkoutBeforeCheckIn(): self
    {
        return new self('Check-out time cannot be earlier than check-in time.');
    }

    public static function alreadyAttendedToday(): self
    {
        return new self('You already completed attendance for today.');
    }

    public static function nonWorkingDay(): self
    {
        return new self('Today is not a working day. Check-in is not available.');
    }
}
