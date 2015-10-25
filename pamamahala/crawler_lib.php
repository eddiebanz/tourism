<?php
	
	// require ('new-connection.php');

	function curlHREF($url)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	    $data = curl_exec($ch);
	    $info = curl_getinfo($ch);  
	    curl_close($ch);
    	$array = explode("<", html_entity_decode($data));
    	return $array;
    }

	function getHREF($text)
	{
        $pos  = strpos($text, "href=") + 6;
        $temp = substr($text,$pos, strlen($text));
        $pos2 = strpos($temp, '"');
        return substr($text, $pos, $pos2);
    }

    function getStart($arraylist)
    {
        // get the line counter for the body. 
        // anything above the body is irrelevant
        for ($counter = 0; $counter < count($arraylist); $counter++)
        {
            if (substr($arraylist[$counter],0,2) === "a ") 
            {
                return $counter;
            }
        }
        // if no a-html-tag is found, return the last index of the array
        return count($arraylist);
    }

    function getLast($arraylist, $starting)
    {
        // get the line counter for the body. 
        // anything above the body is irrelevant
        for ($counter = $starting; $counter < count($arraylist); $counter++)
        {
            if (substr($arraylist[$counter],0,2) !== "a ") 
            {
                return $counter-1;
            }
        }
        return $starting;
    }

    function excludLink($text, $main_site)
    {
		// include in the ignore list anyhing that does not share the parent link
		// ignore the links that have any of the following
    	if ( ( strpos($text, 'sitemap.xml') > 0)||
    		 ( strpos($text, '/?share') > 0)	||
    		 ( strpos($text, 'class=') > 0)		||
    		 ( strpos($text, '/404-error/') > 0)||
    		 ( strpos($text, '.jpg') > 0)		||
    		 ( strpos($text, '.gif') > 0)		||
    		 ( strpos($text, '.bmp') > 0)		||
    		 ( strpos($text, '#') != 0)			||
    		 ( strpos($text, '/podcast') > 0)	||
             ( strpos($text, '/page/') > 0)     ||
             ( strpos($text, $main_site) !== 0)  ||
             ( $text === "")
    		)
    	{
    		// if any of the above is true,
    		// then ignore the link (exclude = true)
    		// otherwise, do not ignore the link (exclude = false)
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }

    function updateDocument($hrefLinks) 
    {
    	$query = "UPDATE scrapper SET drill='N', drillStatus = 'Completed' WHERE main_site_id = '".$hrefLinks['main_site_id']."' AND ref_link = '".$hrefLinks['ref_link']."' AND link_id = ".$hrefLinks['link_id'];
        run_mysql_query($query);
    }

    function checkduplicate($text)
    {
		// if the last string is a forward slash, then remove the forward slash and re-evaluate
		if (substr($text,strlen($text)-1,1) !=='/')
		{
			$text = $text.'/';
		}

		$query = 'SELECT * FROM scrapper WHERE ref_link = "'.$text.'"';
		$query_result = fetch_all($query);
        if ( count($query_result) == 0)
		{
			return true;
		}

		return false;
    }

    function grabAnchors ($hrefLinks, $main_site)
    {
        // grab the last id for this main site
        $query = 'SELECT max(link_id),link_id FROM scrapper WHERE main_site_id = '.$hrefLinks['main_site_id'];
        $result = fetch_record($query);
        $id = intval($result['link_id']);

		// cURL the link and dump into the array the result       
		$array = curlHREF($hrefLinks['ref_link']);
		sort($array);

		// $array is returned as sorted array. get the fist and last
		// index of the a-html-tag and start the loop from there
		// anything else can be ignored
		// get begin index
		$startpos = getStart($array);
		// get last index
		$endpos = getLast($array, $startpos);
		// if startpos = endpos, it would mean that the a-html-tag is not found properly
		// update the document and exit
		if ($startpos == $endpos){
			updateDocument($hrefLinks);
			return;
		}
		
		// start parsing the a-html-tag
	    for ($i = $startpos; $i <= $endpos ; $i++) 
	    {
            // extract the href from the a-html-tag
    		$href_a= getHREF($array[$i]);
    		// check if link if to be excluded
    		if (excludLink($href_a, $main_site) === false) 
    		{
	    		if (checkduplicate($href_a) === true)
	    		{    
                    $id = $id + 1;
	    			$query = "INSERT INTO scrapper (main_site_id, ref_link, link_id) VALUES ('".$hrefLinks['main_site_id']."','". $href_a."','".$id."')";					
					// add the links
					run_mysql_query($query);
				}
			}
		}
		// update the document after the parsing of the a-html-tag
		updateDocument($hrefLinks);
        return;
	}
?>