<?php
//Developer Clinton (C)2018
//new DateDifference($_from, $_to)

//$_from, $_to (any date format)
//'seconds' (converts output for seconds only)
//'minutes' (converts output for minutes only)
//'days' (converts output for days only)
//'weeks' (converts output for weeks only)
//'months' (converts output for months only)
//'years' (converts output for years only)
//'combined' (shows relatable non-empty differences)
//'smart' (shows human relatable time differences)
//'all' (shows all values for years, months, weeks, days, seconds)

class DateDifference {
	public static  $from;
	public static  $to;
	public static  $interval;
	public static  $timediff;

	public function __construct($_from, $_to)	{
		$from_ 			= new DateTime(trim($_from));
		$to_ 				= new DateTime(trim($_to));
		$from 			= $from_->format('Y-m-d H:i:s');
		$to 				= $to_->format('Y-m-d H:i:s');
		self::$from = $from;
		self::$to 	= $to;
		self::$interval = $from_->diff($to_);
		self::$timediff = strtotime($to) - strtotime($from);
	}

	public function seconds($attach_desc = true){
		$response = self::$timediff > 1 ? self::$timediff.' seconds ago' : self::$timediff.' second ago';
		if(!$attach_desc)$response = self::$timediff;
		return $response;
	}

	public function munites($attach_desc = true){
		$timediff = self::$timediff;
		$minute 	= round($timediff/60);
		$response = $minute > 1 ? "{$minute} minutes ago" : "$minute minute ago";
		if(!$attach_desc)$response = $minute;
		return $response;
	}

	public function hours($attach_desc = true){
		$timediff = self::$timediff;
		$hour 		= round($timediff/3600);
		$response = $hour > 1 ? "{$hour} hours ago" : "{$hour} hour ago";
		if(!$attach_desc)$response = $hour;
		return $response;
	}

	public function days($attach_desc = true){
		$interval = self::$interval;
		$response = $interval->days > 1 ? "{$interval->days} days ago" : "{$interval->days} day ago";
		if(!$attach_desc)$response = $interval->days;
		return $response;
	}

	public function weeks($attach_desc = true){
		$timediff = self::$timediff;
		$week 		= round(($timediff/3600)/(24*7));
		$response = $week > 1 ? "{$week} weeks ago" : "{$week} week ago";
		if(!$attach_desc)$response = $week;
		return $response;
	}

	public function months($attach_desc = true){
		$interval = self::$interval;
		$month 		= ($interval->y * 12) + $interval->m;
		$response = $month > 1 ? "{$month} months ago" : "{$month} month ago";
		if(!$attach_desc)$response = $month;
		return $response;
	}

	public function combined($attach_desc = true){
		$interval		= self::$interval;
		$year 		= $interval->y < 1 ? '' : self::plurize($interval->y, "year");
		$month 	= $interval->m < 1 ? ''	: self::plurize($interval->m, "month");
		$week 		= $interval->d > 6 && $interval->d < 31 ? self::plurize(floor($interval->d/7), 'week') : '';
		$days 		= $interval->d < 1 ? '' : self::plurize($interval->d, "day");
		$hours 		= $interval->h < 1 ? ''	: self::plurize($interval->h, "hour");
		$diff 		= trim("$year $month $week $days $hours");
		$response = empty($diff) ? 'seconds ago' : "{$diff} ago";
		if(!$attach_desc)$response = trim(str_replace("ago", "", $response));
		return $response;
	}

	public function smart($attach_desc = true){
		$interval = self::$interval;
		$timediff = self::$timediff;
		$year 		= $interval->y < 1 ? '': self::plurize($interval->y, 'year');
		$month 		= $interval->m < 1 ? '': self::plurize($interval->m, "month");
		$week 		= $interval->d > 6 && $interval->d < 31 ? self::plurize(floor($interval->d/7), 'week') : '';
		$days 		= $interval->d < 1 || $interval->d > 6  ? '': self::plurize($interval->d, "day");
		$hours 		= $interval->h < 1 ? '': self::plurize($interval->h, "hour");
		$minutes 	= round($timediff/60) > 59 ? '': self::plurize(round($timediff/60), "minute");
		$diff 		= trim("$year$month$week$days$hours$minutes");
		$diffa 		= explode('  ',$diff)[0];
		$response = empty($diffa) || $diffa == 0 ? 'seconds ago' : " $diffa ago";
		if(!$attach_desc)$response = trim(str_replace(" ago", "", $response));
		return $response;
	}

	public function all($attach_desc = true){
		$response = json_encode(self::$interval);
		return $response;
	}

	public function plurize($num, $word){
		$word = "  {$num} {$word}";
		$response = (intval($num) > 1) ? "{$word}s" : $word;
		return($response);
	}
}

?>
