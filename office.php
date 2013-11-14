<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');

/*
if(isset($_GET["country_id"]) && is_numeric($_GET["country_id"])){
  $id = $_GET["country_id"];
  $request = "http://gisdayatthales.cartodb.com/api/v2/sql?q=SELECT%20bureaux_region_country.cartodb_id%2C%20bureaux_region_country.name%20FROM%20bureaux_region_country%20inner%20join%20tm_world_borders_simpl_0_3%20on%20st_contains(tm_world_borders_simpl_0_3.the_geom%2C%20bureaux_region_country.the_geom)%20where%20tm_world_borders_simpl_0_3.cartodb_id%20%3D".$id."%20Order%20by%20bureaux_region_country.name";
}
else if (isset($_GET["office_id"]) && is_numeric($_GET["office_id"])){
  $id = $_GET["office_id"];
  $request = "http://gisdayatthales.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20tm_world_borders_simpl_0_3.cartodb_id%2C%20bureaux_region_country.the_geom%20FROM%20bureaux_region_country%20inner%20join%20tm_world_borders_simpl_0_3%20on%20st_contains(tm_world_borders_simpl_0_3.the_geom%2C%20bureaux_region_country.the_geom)%20where%20bureaux_region_country.cartodb_id%20%3D".$id;
}
else{
  $request = "http://gisdayatthales.cartodb.com/api/v2/sql?q=SELECT%20cartodb_id%2C%20name%20FROM%20bureaux_region_country%20Order%20by%20name";
}
*/

if(isset($_GET["country"])){
    $country = $_GET["country"];
    $request = "http://gisdayatthales.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT * FROM bureaux_region_country where country = '{$country}' order by name";
  }
  $data = file_get_contents($request);
  die($data);


?>
