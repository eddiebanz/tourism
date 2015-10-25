<?php
	function getMainSites($site){
		echo 'i am here in get main sites';
		require('mongoDBConnect.php');
		$collection = new MongoCollection($db,"links");
		// set the query
		$query = array ("main_site" => array('&eq' => $site));
		$sort = array('ref_link' => 1);
	    $result = $collection->find($query)->sort($sort);
	    return $result;
	}

	function getSiteList(){
		require('mongoDBConnect.php');
		$collection = new MongoCollection($db,"sites");
		$query = array ("drillStatus" => "Completed");
		$sort = array('mainSite' => 1);
		$result = $collection->find($query);
		var_dump($result);die;
	    return $result;
	}
?>