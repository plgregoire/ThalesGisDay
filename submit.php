<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

//header('Content-type: application/json; charset=utf-8');

$lat = $_POST["lat"];
$lon = $_POST["lon"];
$office = $_POST["office"];
$transportation = $_POST["transportation"];
if(is_numeric($lat) && is_numeric($lon) && is_numeric($office) && is_numeric($transportation)){
  $apikey = "5a6d29fb178dbdd1a1cd394b7a85102e3258872c";
  $ipaddress = getenv('REMOTE_ADDR');
  $proxyIPAddress = getenv('HTTP_X_FORWARDED_FOR');
  $request = "http://thalesgisday.cartodb.com/api/v2/sql?q=INSERT%20INTO%20RESULTS%20(OFFICE,%20TRANSPORTATION,%20LATITUDE,%20LONGITUDE,%20IPADDRESS,%20PROXYIPADDRESS)%20VALUES%20({$office},{$transportation},{$lat},{$lon},'{$ipaddress}','{$proxyIPAddress}')&api_key={$apikey}";
  $data = file_get_contents($request);
  
}
?>
