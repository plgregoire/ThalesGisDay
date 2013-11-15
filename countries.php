<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');

$request = "http://gisday2013atthales.cartodb.com/api/v2/sql?q=SELECT%20distinct(country)%20FROM%20bureaux_region_country%20order%20by%20country";


$data = file_get_contents($request);
die($data);

?>

