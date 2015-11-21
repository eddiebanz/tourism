<?php
	require('util_header.php');
	require('new-connection.php');
	require('mga_kalendaryo.php');

	$calendar = new calendar;

	private function getDateByReference($event){
		// $query2 = "SELECT juliandate FROM `pistahan` WHERE eventid = (SELECT _id FROM `events` WHERE reference = '".$event."')";
		return  fetch_record("SELECT juliandate FROM `pistahan` WHERE eventid = (SELECT _id FROM `events` WHERE reference = '".$event."')");
	}

	private function insertToEventTable($eventName, $eventDate, $duration){

		$calendar->insert_pistahan($eventDate, $eventName);

		// adjust duration
		$duration -= 1;
		// if duration is already 0, then exit. else call the function again to continue the insert
		if ($duration == 0) { return; }

		$eventDate = JDToGregorian(GregorianToJD(date("m",strtotime($eventDate)), date('j',strtotime($eventDate)), date('Y',strtotime($eventDate))) -1 );
		insertToEventTable($eventName, $eventDate, $duration);
		
		return;
	}

	private function getDayOfWeek($day){
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
		if ($value['reference'] != 'YEAR' && $value['reference'] != 'MONTH') {
			$typeOfEvent = substr($value['reference'],2);
			// this is the part where the event is based on another event or holiday
			if 	($typeOfEvent == 'e9' ){ 
				$eventDate = getDateByReference($value['reference'])
			}
			
			// reference is not a valid event but a calendar period
			else {
				if ($value['ref_code'] == 'LAST'){
					$$eventDate = fecth_record("SELECT max(juliandate) FROM fiscalcalendar WHERE fmonth = ". $value['fmonth']." and fyear = ". $value['fyear']. " and dayofweek = ".getDayOfWeek($value['dayofweek']).")";
				}
			}

			// determine the duration
			if ($value['ref_code'] == 'WEEK'){
				$setDuration = 7;
			}
			if ($value['ref_code'] ==  'WEEKEND'){
				$setDuration = 3
			}
			if ($typeOfEvent == 'e9'){
				$setDuration = $value['ref_code'];
			}
			if (intval($value['endMonth']) > 0 ){
				// construct ending date
				if ($value['startMonth'] > $value['endMonth']){	$endyear = $processingYear+1;	}
				
				// get duration
				$setDuration = GregorianToJD($value['startMonth'], value['startDay'], strval($processingYear)) - GregorianToJD($value['startMonth'], value['startDay'], endyear);
			}

			insertToEventTable($value, JDToGregorian($eventDate), $setDuration );
		}
	}
?>