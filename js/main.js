function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(handlePosition, handleError);
	}
	else {
		console.log("Geolocation is not supported by this browser.");
	}
}

function handlePosition(position) {
	// TODO Retrieve nearest Thales office from php and with callback, set Thales office in dropdown
	$.ajax({
		url: "ajax.php",	
		type: "POST",	  
		data: {
			latitude: position.coords.latitude,
			longitude: position.coords.longitude
		},
		cache: false,
		dataType: "json",
		success: function(data) {
				if (data.thalesOffice) {
					console.log(data.thalesOffice);
				}

				if (data.error) {
					console.log(data.error);
				}
			}
		});
	
	console.log("Latitude: " + position.coords.latitude + " Longitude: " + position.coords.longitude);
}

function handleError(error) {
	switch(error.code)  {
		case error.PERMISSION_DENIED:
		  console.log("User denied the request for Geolocation.");
		  break;
		case error.POSITION_UNAVAILABLE:
		  console.log(x.innerHTML="Location information is unavailable.");
		  break;
		case error.TIMEOUT:
		  console.log(x.innerHTML="The request to get user location timed out.");
		  break;
		case error.UNKNOWN_ERROR:
		  console.log(x.innerHTML="An unknown error occurred.");
		  break;
	}
}