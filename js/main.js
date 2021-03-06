var watchId;

function getLocation(callback) {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(callback, handleError);
	}
	else {
		console.log("Geolocation is not supported by this browser.");
	}
}

function getMoreAccurateLocation(callback) {
	if (navigator.geolocation) {
		watchId = navigator.geolocation.watchPosition(callback, handleError, { enableHighAccuracy: true, maximumAge: 30000, timeout: 15000 });
	}
	else {
		console.log("Geolocation is not supported by this browser.");
	}
}

function stopWatchingPosition() {
	if (watchId) {
		navigator.geolocation.clearWatch(watchId);
	}
}

function submit(latitude, longitude, office, transportation, callback) {
	
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
			callback();
		},
		error: function() {
			console.log('submit error');
		}
	});
	
	// TODO Stop watchingPosition after submit...
	
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

function getCountryByOffice(officeId, callback) {
	$.ajax({
		url: "office.php",	
		type: "GET",	  
		data: {
			office_id: +officeId
		},
		cache: false,
		dataType: "json",
		success: function(data) {
			if (data.features) {					
				callback(data.features[0].properties.cartodb_id);
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

function getOfficeCoordinates(officeId, callback) {
	$.ajax({
		url: "office.php",	
		type: "GET",	  
		data: {
			office_id: +officeId
		},
		cache: false,
		dataType: "json",
		success: function(data) {
			if (data.features) {					
				callback(data.features[0].geometry.coordinates);
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

function getOfficesByCountry(country, callback) {
	$.ajax({
		url: "office.php",	
		type: "GET",	  
		data: {
			country: country
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
		  console.log("Location information is unavailable.");
		  break;
		case error.TIMEOUT:
		  console.log("The request to get user location timed out.");
		  break;
		case error.UNKNOWN_ERROR:
		  console.log("An unknown error occurred.");
		  break;
	}
}		
			
/* Override jQuery mobile events */
 $.widget( "mobile.simpledialog2", $.mobile.simpledialog2, {
	_orientChange: function(e) {
		var self = e.data.widget,
			o = e.data.widget.options,
			coords = e.data.widget._getCoords(e.data.widget);
		
		e.stopPropagation();
		
		if ( self.isDialog === true ) {
			return true;
		} else {
			var realHeight = $(self.sdHeader).height();
			$(self.sdIntContent).children('div').each(function(e) { 
				realHeight += $(this).height(); 
			});
			
			if ( o.fullScreen === true && ( coords.width < 400 || coords.winTop + coords.high < 400 || o.fullScreenForce === true ) ) {
				self.sdIntContent.css({'border': 'none', 'position': 'absolute', 'top': coords.fullTop, 'left': coords.fullLeft, 'height': realHeight < coords.high ? coords.high : realHeight, 'width': coords.width, 'maxWidth': coords.width, 'z-index': 100000005 }).removeClass('ui-simpledialog-hidden');
			} else {
				self.sdIntContent.css({'position': 'absolute', 'top': coords.winTop, 'left': coords.winLeft, 'height': realHeight }).removeClass('ui-simpledialog-hidden');
			}
		}
	}
});