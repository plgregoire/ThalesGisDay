<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');


if(isset($_GET["country_id"]) && is_numeric($_GET["country_id"])){
  $id = $_GET["country_id"];
  $request = "http://thalesgisday.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20*%20FROM%20bureaux_thales inner join tm_world_borders_simpl_0_3 on st_contains(tm_world_borders_simpl_0_3.the_geom,%20bureaux_thales.the_geom) where tm_world_borders_simpl_0_3.cartodb_id = {$id}";
  die($request);
  $data = file_get_contents($request);
  die($data);
}else{
  $request = "http://thalesgisday.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20*%20FROM%20bureaux_thales";
  $data = file_get_contents($request);
  die($data);
}


?>
