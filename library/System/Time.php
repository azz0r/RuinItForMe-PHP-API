<?php


class System_Time {


	# http://board.phpbuilder.com/showthread.php?10342598-quot-Convert-quot-hours-minutes-quot-to-quot-total_minutes-quot-and-back-quot
	public static function hoursToMinutes($hours) {
		$minutes = 0;
		if (strpos($hours, ':') !== false)
		{
			// Split hours and minutes.
			list($hours, $minutes) = explode(':', $hours);
		}
		return $hours * 60 + $minutes;
	}



	// Transform minutes like "105" into hours like "1:45".
	public static function minutesToHours($minutes) {
		$hours = (int)($minutes / 60);
		$minutes -= $hours * 60;
		return sprintf("%d:%02.0f", $hours, $minutes);
	}


    # http://stackoverflow.com/questions/336127/calculate-business-days
    public static function getWorkingDays($from, $to) {
        if ($from == $to) {
            return 1;
        }
        $workingDays = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)
        $holidayDays = array('*-12-25', '*-01-01', '2013-12-23'); # variable and fixed holidays

        $from = new DateTime($from);
        $to = new DateTime($to);
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {
            if (!in_array($period->format('N'), $workingDays)) continue;
            if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
            if (in_array($period->format('*-m-d'), $holidayDays)) continue;
            $days++;
        }
        return $days;
    }


    public static function appendZero($number) {
        return $number < 10 && $number[0] != '0' ? '0'.$number : $number;
    }


    public static function getTotalDays($from, $to) {
        if ($from == $to) {
            return 1;
        }
        $workingDays = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)

        $from = new DateTime($from);
        $to = new DateTime($to);
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {
            $days++;
        }
        return $days;
    }

}