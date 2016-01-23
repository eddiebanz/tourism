<?php
	require('new-connection.php');
	session_start();
	foreach ($_POST as $key => $value) {
		if ( strlen($value) > 0) {
			$jdate = $value;
			$jmonth = date("m",strtotime($value));
			$jday = date('j',strtotime($value));
			$jyear = date('Y',strtotime($value));
			$GregorianToJD = GregorianToJD($jmonth, $jday, $jyear);
			$query = "INSERT INTO `pistahan` (`juliandate`, `event_id`,) VALUES (".$GregorianToJD.",'".$key."')";
			$result = run_mysql_query($query);
		}
	}
?>