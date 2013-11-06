<?php
	if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
		// todo retrieve thales office
		$nearestThalesOffice = json_encode(array("thalesOffice" => "Thales Quebec"));
		echo $nearestThalesOffice;
	}
?>
