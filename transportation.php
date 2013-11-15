<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');

$request = "http://gisday2013atthales.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20*%20FROM%20TRANSPORTATION%20Order%20by%20name";
$data = file_get_contents($request);
die($data);

?>
