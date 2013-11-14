<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');

$lat = $_POST["lat"];
$lon = $_POST["lon"];
$office = $_POST["office"];
$transportation = $_POST["transportation"];
if(is_numeric($lat) && is_numeric($lon) && is_numeric($office) && is_numeric($transportation)){
  $apikey = "3cb290cbfbca55ec6170ac319531238a97ae42a0";
  $ipaddress = getenv('REMOTE_ADDR');
  $proxyIPAddress = getenv('HTTP_X_FORWARDED_FOR');
  $request = "http://gisdayatthales.cartodb.com/api/v2/sql?q=INSERT%20INTO%20RESULTS%20(the_geom, OFFICE,%20TRANSPORTATION,%20LATITUDE,%20LONGITUDE,%20IPADDRESS,%20PROXYIPADDRESS)%20VALUES%20(ST_SetSRID(ST_Point({$lon},{$lat}),4326),{$office},{$transportation},{$lat},{$lon},'{$ipaddress}','{$proxyIPAddress}')&api_key={$apikey}";
  
  echo($request);
  die();
  $data = file_get_contents($request);
  
  echo $data;
}
?>
