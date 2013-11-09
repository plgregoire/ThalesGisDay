function getLocation(callback) {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(callback, handleError);
	}
	else {
		console.log("Geolocation is not supported by this browser.");
	}
}

function submit(latitude, longitude, office, transportation) {
	
	$.ajax({
		url: "submit.php",	
		type: "POST",	  
		data: {
			lat: latitude,
			lon: longitude,
			office: office,
			transportation: transportation
		},
		cache: false,
		dataType: "json",
		success: function(data) {
				console.log('submit success');
				
		},
		error: function() {
			console.log('submit error');

		}
	});
	
	console.log("Latitude: " + latitude + " Longitude: " + longitude + " office: " + office + " transportation: " + transportation);
}

function getClosestOffice(position, callback) {
	$.ajax({
		url: "closestOffice.php",	
		type: "GET",	  
		data: {
			lat: position.coords.latitude,
			lon: position.coords.longitude
		},
		cache: false,
		dataType: "json",
		success: function(data) {
				if (data.features) {
					console.log(data.features[0].properties.name);
					
					callback(data.features[0]);
				}

				if (data.error) {
					console.log(data.error[0]);
				}
		},
		error: function() {
			console.log('error');
		}
	});
	
	console.log("Latitude: " + position.coords.latitude + " Longitude: " + position.coords.longitude);
}

function getOfficesByCountry(countryId, callback) {
	$.ajax({
		url: "office.php",	
		type: "GET",	  
		data: {
			country_id: +countryId
		},
		cache: false,
		dataType: "json",
		success: function(data) {
				if (data.features) {					
					callback(data.features);
				}

				if (data.error) {
					console.log(data.error[0]);
				}
		},
		error: function(jqXHR, textStatus, errorThrown ) {
			console.log('error');
		}
	});
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
			