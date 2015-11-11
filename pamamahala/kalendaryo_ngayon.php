<?php
	require('new-connection.php');
	require('mga_kalendaryo.php');

	$calendar = new calendar;

	$query = "SELECT * FROM `events` WHERE startMonth > 0 or length(reference) > 0 ORDER BY `startMonth` ASC";
	$result = fetch_all($query);


	foreach ($result as $key => $value) {
		if 	(strlen($key) > 0){
			$query2 = "SELECT * FROM `events` WHERE startMonth > 0 or length(reference) > 0 ORDER BY `startMonth` ASC";
		}
		elseif (startMonth > 0) {

		}
	}
?>