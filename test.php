<?php 

$urls = array();  

$DomDocument = new DOMDocument();
$DomDocument->preserveWhiteSpace = false;
$DomDocument->load('http://soloflighted.com/sitemap-misc.xml');
$DomNodeList = $DomDocument->getElementsByTagName('loc');

foreach($DomNodeList as $url) {
    $urls[] = $url->nodeValue;
}

//display it
echo "<pre>";
print_r($urls);
echo "</pre>";

?>