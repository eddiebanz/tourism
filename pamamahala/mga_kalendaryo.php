<?php
require('new-connection.php');

Class calendar {

	public function insert_pistahan($date, $event){
		$jdate = $value;
		$jmonth = date("m",strtotime($value));
		$jday = date('j',strtotime($value));
		$jyear = date('Y',strtotime($value));
		$GregorianToJD = GregorianToJD($jmonth, $jday, $jyear);
		$query = "INSERT INTO `pistahan` (`juliandate`, `event_id`,`jdate`,`jday`,`jmonth`,`jyear`) VALUES (".$GregorianToJD.",".$value.",".$jdate.",".$jday.",".$jmonth.",".$jyear.")";
		run_mysql_query($query);
		return;
	}

	public function gawa_ng_kalendaryo($year){
		$numberOfDays = 390;
		$newdayofweek = 1;
		$weekNumber = 1;	
		$days = "+0 days";

		// check table for the last date on the the table
		$query = "SELECT juliandate FROM fiscalcalendar ORDER BY juliandate desc Limit 1";
		$result = fetch_record($query);
		if ($result['juliandate'] == 0) {	$juliandate = gregoriantojd(01,01,date('Y',strtotime($year)));	}
		else {	$juliandate = $result['juliandate']+1;	}

		// $juliandate = gregoriantojd(01,01,date('Y',strtotime($days)));
			echo "<table border='5px'><thead><td>Julian Date</td><td>Gregorian Date</td><td>end of week</td><td>end of month</td><td>week#</td></thead><tbody>";
		for ( $i = 0; $i <= $numberOfDays; $i++ ) { 
			// check for end of week
			$days = JDToGregorian($juliandate);
			if (date('N',strtotime($days)) == 7){	$endOfWeek= 'Y';	}
			else 	{	$endOfWeek= 'N';	}
			// end of year
			if (date("m",strtotime($days)) == 12 && date("j",strtotime($days)) == date("t",strtotime($days)) ) { $endofyear= 'Y';	}
			else { $endofyear= 'N';	}
			// end of month
			if (date("j",strtotime($days)) == date("t",strtotime($days)) ) {  $endofmonth= 'Y';	 }
			else { $endofmonth= 'N';	}
			if (date("j",strtotime($days)) == 1) {
				$weekNumber = 1;	
				$newdayofweek = date('N',strtotime($days));
			}
			else{
				if ($newdayofweek == date('N',strtotime($days))){
					$weekNumber += 1;	
				}
			}
			echo "<tr>".$juliandate.'</td><td>'.JDToGregorian($juliandate).'</td><td>'.$endOfWeek .'</td><td>'.$endofmonth .'</td><td>'.$weekNumber .'</td></tr>';
			
			$query = "INSERT INTO fiscalcalendar (juliandate,fdate, fmonth, fday, fyear, endOfWeek, endofmonth,  dayOfWeek, weekNumber) VALUES('". $juliandate."','". date("y-m-d",strtotime($days))."',". date("m",strtotime($days)) .",". date('j',strtotime($days)) .",". date('Y',strtotime($days)).",'". $endOfWeek."','". $endofmonth."','". date('N',strtotime($days)) ."',". $weekNumber .")";
			$result = run_mysql_query($query);
			$juliandate += 1;
		}
	}
}
?>