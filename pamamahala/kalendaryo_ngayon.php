<?php
	require('util_header.php');
	// require('new-connection.php');
	require('mga_kalendaryo.php');

	$calendar = new calendar;

	function getDateByReference($RefEventId, $MainEvent){
		$query = "SELECT juliandate FROM `pistahan` WHERE event_id = '".$RefEventId."'";
		echo $query."<br>";
		$result = fetch_record($query);
		var_dump($result);
		die;
		return fetch_record("SELECT juliandate FROM `pistahan` WHERE eventid = (SELECT _id FROM `events` WHERE reference = '".$event."')");
	}

	function insertToEventTable($eventName, $eventDate, $duration){

		$calendar->insert_pistahan($eventDate, $eventName);

		// adjust duration
		$duration -= 1;
		// if duration is already 0, then exit. else call the function again to continue the insert
		if ($duration == 0) { return; }

		$eventDate = JDToGregorian(GregorianToJD(date("m",strtotime($eventDate)), date('j',strtotime($eventDate)), date('Y',strtotime($eventDate))) -1 );
		insertToEventTable($eventName, $eventDate, $duration);
		
		return;
	}

	function getDayOfWeek($day){
		switch ($day) {
			case 'MONDAY':
				return 1;
				break;
			case 'TUESDAY':
				return 2;
				break;
			case 'WEDNESDAY':
				return 3;
				break;
			case 'THURSDAY':
				return 4;
				break;
			case 'FRIDAY':
				return 5;
				break;
			case 'SATURDAY':
				return 6;
				break;
			case 'SUNDAY':
				return 7;
				break;
			case 'WEEK':
				return 1;
				break;
			case 'WEEKEND':
				return 5;
				break;
		}
	}

	// main processing
	$query = "SELECT * FROM `events` WHERE startMonth > 0 or length(reference) > 0 ORDER BY `startMonth` ASC";
	$result = fetch_all($query);

	foreach ($result as $key => $value) {
		// initializa event date
		$eventDate = getdate();
		if ($value['reference'] != 'YEAR' && $value['reference'] != 'MONTH') {
			$typeOfEvent = substr($value['reference'],0,2);
			// this is the part where the event is based on another event or holiday
			if 	($typeOfEvent == 'e9' ){	$eventDate = getDateByReference($value['reference'],$value['_id']); }
			
			// reference is not a valid event but a calendar period
			else {
				if ($value['ref_code'] == 'LAST'){
					$getJulianDate = "SELECT max(juliandate) FROM fiscalcalendar WHERE fmonth = ". $value['fmonth']. " and fyear = ". $value['fyear']. " and dayofweek = ". getDayOfWeek($value['dayofweek']);	
					$eventDate = fecth_record($getJulianDate);
				}
			}

			// determine the duration
			switch ($value['ref_code']) {
				case 'WEEK':
					$setDuration = 7;
					break;
				case 'WEEKEND':
					$setDuration = 3;
					break;
			}

			if ($typeOfEvent == 'e9'){	$setDuration = $value['ref_code'];	}
			
			if (intval($value['endMonth']) > 0 ){
				// construct ending date
				if ($value['startMonth'] > $value['endMonth']){	$endyear = $processingYear+1;	}
				
				// get duration
				$setDuration = GregorianToJD($value['startMonth'], $value['startDay'], strval($processingYear)) - GregorianToJD($value['startMonth'], $value['startDay'], endyear);
			}

			var_dump($eventdate); echo "<br>"; die;

			insertToEventTable($value['_id'], JDToGregorian($eventDate), $setDuration );
		}
	}
?>