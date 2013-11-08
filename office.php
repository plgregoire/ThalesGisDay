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
  $getData = http_build_query(
				array(
					'format' => 'GeoJSON',
					'q' => "SELECT bureaux_region_country.cartodb_id,bureaux_region_country.the_geom, bureaux_region_country.name FROM bureaux_region_country inner join tm_world_borders_simpl_0_3 on st_contains(tm_world_borders_simpl_0_3.the_geom, bureaux_region_country.the_geom) where tm_world_borders_simpl_0_3.cartodb_id =".$id
				));
  
  $request = "http://thalesgisday.cartodb.com/api/v2/sql?".$getData;
  
  $data = file_get_contents($request);
  die($data);
}else{
  $getData = http_build_query(
				array(
					'format' => 'GeoJSON',
					'q' => 'SELECT%20*%20FROM%20bureaux_region_country'
				)
			);
  $request = "http://thalesgisday.cartodb.com/api/v2/sql?".$getData;
  $data = file_get_contents($request);
  die($data);
}


?>
