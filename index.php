<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<link rel="shortcut icon" href="./img/gisday_logo.png">
		<link rel="icon" href="./img/gisday_logo.png">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

        <link rel="stylesheet" href="css/normalize.min.css">
        
		<style>
			html, body, #map{
			height:100%;
			padding:0;
			margin:0;
			}
		</style>
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		
		
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />

		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
		<link rel="stylesheet" href="css/leaflet.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.mobile.simpledialog.css" /> 
		
		<link rel="stylesheet" href="css/main.css">
		<!--[if lte IE 8]>
			<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.ie.css" />
		<![endif]-->

		<script src="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js"></script>

	
		
		<link rel="stylesheet" href="http://libs.cartocdn.com/cartodb.js/v3/themes/css/cartodb.css" />
		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="http://libs.cartocdn.com/cartodb.js/v3/themes/css/cartodb.ie.css" />
		<![endif]-->
		<script src="http://libs.cartocdn.com/cartodb.js/v3/cartodb.js"></script>
		<script type="text/javascript" src="http://dev.jtsage.com/cdn/simpledialog/latest/jquery.mobile.simpledialog2.min.js"></script>
	</head>
	
	<body data-role="page">
		<div data-role="popup" id="popupSubmitted" data-history="false" data-transition="slidedown">
		  <p>Thank you for participating, the selected Thales office has been updated.<p>
		</div>
	
		<div id="map"></div>

		<div >
			<div id="formPopup" style="display:none;" data-options='{"mode":"blank","blankContentAdopt":true,"headerText":"Thales GIS Day","headerClose":false,"blankContent":true, "fullScreen":true, "width": "400px"}'>
				<div class="dialogContent">
					<fieldset>
						<legend style="font-weight:bold;">Select your Thales office:</legend>
						<div>
							<label for="countrySelect">Country:</label>
							<select name="countrySelect" id="countrySelect" tabindex="1" data-mini="true">
								<?php
									$json = file_get_contents("http://gisdayatthales.azurewebsites.net/countries.php");
									$data = json_decode($json);
									
									//$defaultCountry = '';
									//if (!empty($data->rows)) {
									//	$defaultCountry = $data->rows[0]->country;
									//}
									
									foreach ($data->rows as $row){
										echo '<option value="' . htmlspecialchars($row->country) . '">' 
											. htmlspecialchars($row->country) 
											. '</option>';
									}						
								?>
							</select>
						</div>
						<div>
							<label for="thalesOfficeSelect" >Thales Office:</label>
							<select name="thalesOfficeSelect" id="thalesOfficeSelect" tabindex="2" data-mini="true">
								<?php
									$defaultCountry = 'France';
									$query = "";
									if (!empty($defaultCountryId)) {
										$query = "?country=".$defaultCountry;
									}
									
									$json = file_get_contents("http://gisdayatthales.azurewebsites.net/office.php?country=France");
									$data = json_decode($json);
									foreach ($data->features as $feature){
										echo '<option value="' . htmlspecialchars($feature->properties->cartodb_id) . '">' 
											. htmlspecialchars($feature->properties->name) 
											. '</option>';
									}						
								?>
							</select>
						</div>
					</fieldset>
					<br />
					<fieldset>
						<legend style="font-weight:bold;">What is the primary mode of transportation in your daily commute?</legend>
						<div >			
							<select name="transportationInput" id="transportationInput" tabindex="3" data-mini="true">
								<?php		
									$json = file_get_contents("http://gisdayatthales.azurewebsites.net/transportation.php");
									$data = json_decode($json);
									foreach ($data->features as $feature){
										echo '<option value="' . htmlspecialchars($feature->properties->cartodb_id) . '">' 
											. htmlspecialchars($feature->properties->name) 
											. '</option>';
									}						
								?>
								
							</select>
						</div>
					</fieldset>
					<br />
					<div style="text-align: center; white-space:nowrap;">		
						<button id="seeResultButton" style="position: absolute, top: 50%;" data-inline="true" tabindex="5">See Results</button>
						<button id="submitButton" style="position: absolute, top: 50%;" data-icon="check" data-inline="true" data-theme="b" tabindex="4">Submit</button>
					</div>
				</div>
			</div>
		</div>
		
		<script src="js/main.js"></script>
		<script type="text/javascript">
			var map;
			var nativeMap;
			var layer;
			var refreshTimeOut;
			var selectedOfficeId;
			var accuratePosition;
			 
			 function refreshLayer(){
				layer.setQuery(layer.getQuery());
			 }
			 
			 function centerOn(coords, zoom) {
				if (map) {
					map.setView(new L.LatLng(coords[1], coords[0]), 8);
				}
			 }
			 
			 function initialisation() {
				// Default to France
				$('#countrySelect').val('France').selectmenu('refresh');
				
				// Default to Car
				$('#transportationInput').val(1).selectmenu('refresh');
			 }
			 
			 function bindEvents() {
				$('#countrySelect').change(function() {
					getOfficesByCountry($(this).val(), function(offices) {
						$('#thalesOfficeSelect option').remove();
						$('#thalesOfficeSelect').empty();
						$.each(offices, function(key, value) {
						  $('#thalesOfficeSelect').append($("<option></option>")
							 .attr("value", value.properties.cartodb_id).text(value.properties.name));
						});
						
						$('#thalesOfficeSelect').selectmenu('refresh');
						
						if (selectedOfficeId && selectedOfficeId != null) {
							$('#thalesOfficeSelect').val(selectedOfficeId);
							selectedOfficeId = null;
						}
						$('#thalesOfficeSelect').change();
					});
				});
				
				$('#thalesOfficeSelect').change(function() {
					getOfficeCoordinates($(this).val(), function(coordinates) {
						centerOn(coordinates, 8);
					});
				});
				
				$('#submitButton').click(function(){
					$.mobile.sdCurrentDialog.close();
					
					var lat = nativeMap.getCenter().lat;
					var lng = nativeMap.getCenter().lng;
					if (accuratePosition) {
						console.log('Using accurate position');
						lat = accuratePosition.coords.latitude;
						lng = accuratePosition.coords.longitude;
					}
					
					submit( lat, 
							lng, 
							$('#thalesOfficeSelect').val(), 
							$('#transportationInput').val(),
							function(){
								refreshLayer();
								
								$('#popupSubmitted').popup('open',{
								  y: 30
								});
							
								stopWatchingPosition();
							});
				});
				
				$('#seeResultButton').click(function(){
					$.mobile.sdCurrentDialog.close();
						
					stopWatchingPosition();
				});
												
				$(document).on('popupafteropen', '#popupSubmitted', function() {					
					setTimeout(function () {
						$('#popupSubmitted').popup('close');
					}, 2500);
				});
			 }
			 
			window.onload = function() {
				$("#formPopup").simpledialog2().resize();
				
				bindEvents();
				
				initialisation();
				
				cartodb.createVis('map', 'http://gisday2013atthales.cartodb.com/api/v2/viz/b8922ef0-4e1c-11e3-8dc5-49eac9e42462/viz.json', {
						shareable: false,
						title: false,
						description: false,
						search: false,
						tiles_loader: false,
						legends:true,
						center_lat: 0,
						center_lon: 0,
						zoom: 3
					})
					.done(function(vis, layers) {
						// layer 0 is the base layer, layer 1 is cartodb layer
						// setInteraction is disabled by default
						
						layers[1].setInteraction(true);
						layers[1].on('featureOver', function(e, pos, latlng, data) {
							cartodb.log.log(e, pos, latlng, data);
						});
						
						layer = layers[1];
						
						layer.getSubLayer(0).hide();
						layer.getSubLayer(1).hide();
						layer.getSubLayer(2).show();
						layer.getSubLayer(3).show();
						
												
						//refreshLayer();
						// you can get the native map to work with it
						// depending if you use google maps or leaflet
						nativeMap = vis.getNativeMap();
						map = vis.map;

						// now, perform any operations you need
						// map.setZoom(3)
						// map.setCenter(new google.maps.Latlng(...))
						$('.cartodb-logo').html('<div class="customLogo"><a href="https://www.thalesgroup.com/en/homepage/canada"><img id="thalesLogo" src="img/ThalesLogo_White.png" title="Thales Canada inc." alt="Thales Canada inc." /></a><img id="gisLogo" src="img/GISDay_logo_white.png" title="GIS Day" alt="GIS Day" /></div>');
						
					})
					.error(function(err) {
						console.log(err);
					});
				
				getLocation(function(location) {
					getClosestOffice(location, function(feature) {
						if (feature) {
							$('#countrySelect').val(feature.properties.country).change();
							selectedOfficeId = feature.properties.cartodb_id;
							//getCountryByOffice(feature.properties.cartodb_id, function(countryId) {
							//	selectedOfficeId = feature.properties.cartodb_id;
							//	$('#countrySelect').val(countryId).change();
							//});
						}
					});
				});
				
				getMoreAccurateLocation(function(location) {
					accuratePosition = location;
				});
			}
		</script>

        <!--<script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>-->
	</body>
</html>

  
