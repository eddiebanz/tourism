<?php
// if there are any results, go and extract all the links from the parent site
	foreach ($subpages as $hrefLinks) 
	{
	    echo "<tr>";
	       echo "<td>".$hrefLinks['ref_link']."</td>";
	       echo "<td class='center'><input type='checkbox' name='crawl' value='".$hrefLinks['_id']."'></td>";
	    echo "</tr>";
	}
?>