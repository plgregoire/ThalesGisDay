<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');

$office = $_POST["office"];
$transportation = $_POST["transportation"];

$apikey = "a63643ebababa18d92daf45dfbb4ebb9d868cecb";

$request = "http://thalesgisday.cartodb.com/api/v2/sql?q=INSERT INTO RESULTS (OFFICE, TRANSPORTATION) VALUES ({$office},{$transportation})&api_key={$apikey}";
$data = file_get_contents($request);
die($request);
die($data);

?>
