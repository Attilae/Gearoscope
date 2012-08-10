<?php
class CMS_Validate_MakeTime
{	public static function timeToStamp($value)
        {
            $year = substr($value, 0, 4);
			$month = substr($value, 5, 2);
			$day = substr($value, 8, 2);
			$hour = substr($value, 12, 2);
			$min = substr($value, 15, 2);
			$sec = substr($value, 18, 2);
			
			//die($year . "-" . $month . "-" . $day . "-" . $hour . "-" . $min . "-"  . $sec);
			return mktime($hour, $min, $sec, $month, $day, $year);
        }
}

?>