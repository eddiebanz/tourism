<?php 
require('new-connection.php');

$numberOfDays = 390;
$newdayofweek = 1;
$weekNumber = 1;	
$days = "+0 days";

// check table for the last date on the the table
$query = "SELECT juliandate FROM fiscalcalendar ORDER BY juliandate desc Limit 1";
$result = fetch_record($query);
if ($result['juliandate'] == 0) {	$juliandate = gregoriantojd(01,01,date('Y',strtotime($days)));	}
else {	$juliandate = $result['juliandate']+1;	}

// $juliandate = gregoriantojd(01,01,date('Y',strtotime($days)));
	echo "<table border='5px'><thead>";
	echo "<td>Julian Date</td>";
	echo "<td>Gregorian Date</td>";
	echo "<td>end of week</td>";
	echo "<td>end of month</td>";
	echo "<td>week#</td>";
	echo "</thead><tbody>";
for ( $i = 0; $i <= $numberOfDays; $i++ )
{ 
	// check for end of week
	$days = JDToGregorian($juliandate);
	if (date('N',strtotime($days)) == 7){	$endOfWeek= 'Y';	}
	else 	{	$endOfWeek= 'N';	}

	// end of year
	if (date("m",strtotime($days)) == 12 && date("j",strtotime($days)) == date("t",strtotime($days)) ) { $endofyear= 'Y';	}
	else { $endofyear= 'N';	}

	// end of month
	if (date("j",strtotime($days)) == date("t",strtotime($days)) ) { 
		$endofmonth= 'Y';	
	}
	else { 
		$endofmonth= 'N';	
	}

	if (date("j",strtotime($days)) == 1) {
		$weekNumber = 1;	
		$newdayofweek = date('N',strtotime($days));
	}
	else{
		if ($newdayofweek == date('N',strtotime($days))){
			$weekNumber += 1;	
		}
	}
	echo "<tr>";
	echo "<td>".$juliandate.'</td>';
	echo "<td>".JDToGregorian($juliandate).'</td>';
	echo '<td>'.$endOfWeek .'</td>';
	echo '<td>'.$endofmonth .'</td>';
	echo '<td>'.$weekNumber .'</td>';
	echo '</tr>';
	
	$query = "INSERT INTO fiscalcalendar (
		juliandate,
		fdate, 
		fmonth, 
		fday, 
		fyear, 
		endOfWeek, 
		endofmonth,  
		dayOfWeek, 
		weekNumber) VALUES('". $juliandate."','".
		date("y-m-d",strtotime($days))."',".
		date("m",strtotime($days)) .",". 
		date('j',strtotime($days)) .",". 
		date('Y',strtotime($days)).",'".
		$endOfWeek."','".
		$endofmonth."','".
		date('N',strtotime($days)) ."',". 
		$weekNumber .")";
// echo $query;die;
	$result = run_mysql_query($query);
	$juliandate += 1;
}
?>