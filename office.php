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
    $request = "http://gisdayatthales.cartodb.com/api/v2/sql?format=GeoJSON&q=SELECT%20*%20FROM%20bureaux_region_country_2%20where%20country%20=%20'{$countryEncoded}'%20order%20by%20name";
  }
  $data = file_get_contents($request);
  die($data);


?>
