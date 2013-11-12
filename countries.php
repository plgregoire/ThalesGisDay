<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With");

header('Content-type: application/json; charset=utf-8');

$request = "http://gisdayatthales.cartodb.com/api/v2/sql?q=SELECT%20cartodb_id%2Cname%20country%20%0AFROM%20tm_world_borders_simpl_0_3%20%0AWHERE%20%0A(SELECT%20count(bureaux_region_country.cartodb_id)%20%0A%20FROM%20bureaux_region_country%20%0A%20WHERE%20st_contains(tm_world_borders_simpl_0_3.the_geom%2C%20bureaux_region_country.the_geom))%20%3E%200%20Order%20by%20country";

// TODO ignore countries that do not contain Thales Offices
$data = file_get_contents($request);
die($data);

?>

