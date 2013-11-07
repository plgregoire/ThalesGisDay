<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');

$request = "http://thalesgisday.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20tm_world_borders_simpl_0_3.cartodb_id,tm_world_borders_simpl_0_3.name,%20tm_world_borders_simpl_0_3.the_geom%20FROM%20tm_world_borders_simpl_0_3%20inner%20join%20bureaux_thales%20on%20st_contains(tm_world_borders_simpl_0_3.the_geom,%20bureaux_thales.the_geom)";
$data = file_get_contents($request);
die($data);

?>

