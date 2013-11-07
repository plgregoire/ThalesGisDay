<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');


if(!isset($_POST["lat"]) || !isset($_POST["lon"])){
  // some mandatory parameters are not set.
  die;
}

$lat = $_POST["lat"];
$lon = $_POST["lon"];

//$request = "http://thalesgisday.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20*%20FROM%20OFFICES%20ORDER%20BY%20ST_DISTANCE(ST_SETSRID(ST_POINT(1,2),4326),the_geom)%20LIMIT%201";
$request = "http://thalesgisday.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20*%20FROM%20OFFICES%20ORDER%20BY%20ST_DISTANCE(ST_SETSRID(ST_POINT({$lat},{$lon}),4326),the_geom)%20LIMIT%201";
$data = file_get_contents($request);
die($data);

?>