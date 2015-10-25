<?php 
require('new-connection.php');

if (date('L')){	$numberOfDays = 366;	}
else{ $numberOfDays = 365;}
$newdayofweek = 1;
$weekNumber = 1;	

for ( $i = 0; $i <= $numberOfDays; $i++ )
{ 
	$days = "+".$i.' days' ;
	// check for end of week
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

	$query = "INSERT INTO fiscalcalendar (
		fdate, 
		fmonth, 
		fday, 
		fyear, 
		endOfWeek, 
		endofmonth,  
		dayOfWeek, 
		weekNumber) VALUES('". 
		date("y-m-d",strtotime($days))."',".
		date("m",strtotime($days)) .",". 
		date('j',strtotime($days)) .",". 
		date('Y',strtotime($days)).",'".
		$endOfWeek."','".
		$endofmonth."','".
		date('N',strtotime($days)) ."',". 
		$weekNumber .")";
	$result = run_mysql_query($query);
}
?>
