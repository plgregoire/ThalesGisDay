<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');

$request = "http://thalesgisday.cartodb.com/api/v2/sql?q=SELECT%20cartodb_id,name%20country%20%20FROM%20tm_world_borders_simpl_0_3";
// TODO ignore countries that do not contain Thales Offices
$data = file_get_contents($request);
die($data);

?>

