<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');


if(!isset($_GET["lat"]) || !isset($_GET["lon"])){
  // some mandatory parameters are not set.
  die;
}

$lat = $_GET["lat"];
$lon = $_GET["lon"];
select * from calls order by ST_Distance(ST_SetSRID(ST_Point(4,4),4326), geom_p) limit 1;

$request = "http://thalesgisday.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20*%20FROM%20OFFICES%20ORDER%20BY%20ST_DISTANCE(ST_SETSRID(ST_POINT({$lat},{$lon}),4326),the_geom)%20LIMIT%201";
$data = file_get_contents($request);
die($data);

?>
