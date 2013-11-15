<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');



if(isset($_GET["country"])){
    $country = $_GET["country"];
	$countryEncoded = urlencode($country);
    $request = "http://gisday2013atthales.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20*%20FROM%20bureaux_region_country%20where%20country%20=%20'{$countryEncoded}'%20order%20by%20name";
  }
else if (isset($_GET["office_id"]) && is_numeric($_GET["office_id"])){
  $id = $_GET["office_id"];
  $request = "http://gisday2013atthales.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20bureaux_region_country.the_geom%20FROM%20bureaux_region_country%20%20where%20bureaux_region_country.cartodb_id%20%3D".$id;
}
else{
  $request = "http://gisday2013atthales.cartodb.com/api/v2/sql?q=SELECT%20cartodb_id%2C%20name%20FROM%20bureaux_region_country%20Order%20by%20name";
}
  $data = file_get_contents($request);
  die($data);


?>
