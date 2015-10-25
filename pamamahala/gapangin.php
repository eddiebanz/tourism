<?php    

// This program aims only to find links from a website and store the info in a database.
// A user process will be necessary to select which link will be scrapped for specific info.
// Another program will then read through the selected record for sampling, then the actual scraping itself
	// set runtime to only 10minutes
	set_time_limit(1200);
	// set the connection
	include ('new-connection.php');
	require ('crawler_lib.php');
	// set the job-scheduler
	// include( dirname(__FILE__) . "/phpjobscheduler/firepjs.php");

	// get the first record. 
	// it will be assumed that the table will have an inital record which will contain the main site
	// only get the records that are tag for drilling : drill = 'Y'.
	// this will only loop(drill) 3times (or 3levels) just to be sure
	// anything after the 3rd level will be ignored

	// get the details of the site to determine how many levels to drill
	$query = "SELECT * FROM sites WHERE drill = 'Y' AND status = 'Pending'";
	$results = fetch_record($query);

	// loop through the list
	for ($list_loop = 0; $list_loop <= count($results); $list_loop++) {

		// set runtime to only 10 minutes
		set_time_limit(600);
		for($loopCounter = 0; $loopCounter <= $results['level']; $loopCounter++)
		{
			// get the first link
			$query = "SELECT * FROM scrapper WHERE main_site_id =".$results['site_id']." AND drill = 'Y' AND drillStatus = 'Not Started'";
			$query_results = fetch_all($query);
			if (count($query_results) == 0 ){
				
				// update the site record
				$query = "UPDATE sites SET drill='N', status='Completed' WHERE main_site_id =".$results['site_id'];
				run_mysql_query($query);
				$loopCounter = $results['level'];
			}
			else{
				foreach ($query_results as $listing){
					grabAnchors($listing,$results['site_address']);
				}
			}
		}
		// update the site record
		$query = "UPDATE sites SET drill='N', status='Completed' WHERE main_site_id =".$results['site_id'];
		run_mysql_query($query);
	}
	return;
?>