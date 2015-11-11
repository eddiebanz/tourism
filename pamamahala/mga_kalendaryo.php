<?php
	require('new-connection.php');

	Class calendar {

		function insert_pistahan($date, $event){
			$jdate = $value;
			$jmonth = date("m",strtotime($value);
			$jday = date('j',strtotime($value));
			$jyear = date('Y',strtotime($value));
			$GregorianToJD = GregorianToJD($jmonth, $jday, $jyear);
			$query = "INSERT INTO `pistahan` (`juliandate`, `event_id`,`jdate`,`jday`,`jmonth`,`jyear`) VALUES ("$GregorianToJD.",".$value.",".$jdate.",".$jday.",".$jmonth.",".$jyear.")";
			$result = run_mysql_query($query);
		}



	}
?>