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
									
									//$defaultCountryId = '';
									//if (!empty($data->rows)) {
									//	$defaultCountryId = $data->rows[0]->cartodb_id;
									//}
									
									foreach ($data->rows as $row){
										echo '<option value="' . htmlspecialchars($row->cartodb_id) . '">' 
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
									$defaultCountryId = 65; // France
									$query = "";
									if (!empty($defaultCountryId)) {
										$query = "?country_id=".$defaultCountryId;
									}
									
									$json = file_get_contents("http://gisdayatthales.azurewebsites.net/office.php".$query);
									$data = json_decode($json);
									foreach ($data->rows as $row){
										echo '<option value="' . htmlspecialchars($row->cartodb_id) . '">' 
											. htmlspecialchars($row->name) 
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
			var layer;
			var refreshTimeOut;
			var selectedOfficeId;
			var accuratePosition;
			 
			 function refreshLayer(){
				layer.setQuery(layer.getQuery());
			 }
			 
			 function centerOn(coords, zoom) {
				if (map) {
					map.panTo(new L.LatLng(coords[1], coords[0]), null);
					map.setZoom(8);
				}
			 }
			 
			 function initialisation() {
				// Default to France
				$('#countrySelect').val(65).selectmenu('refresh');
			 }
			 
			 function bindEvents() {
				$('#countrySelect').change(function() {
					getOfficesByCountry($(this).val(), function(offices) {
						$('#thalesOfficeSelect option').remove();
						$('#thalesOfficeSelect').empty();
						$.each(offices, function(key, value) {
						  $('#thalesOfficeSelect').append($("<option></option>")
							 .attr("value", value.cartodb_id).text(value.name));
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
												
												
												var lat = map.getCenter().lat;
												var lng = map.getCenter().lng;
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
														});
										});
				
				$('#seeResultButton').click(function(){
													$.mobile.sdCurrentDialog.close();
												});
												
				$(document).on('popupafteropen', '#popupSubmitted', function() {					
					setTimeout(function () {
						$('#popupSubmitted').popup('close');
					}, 5000);
				});
			 }
			 
			window.onload = function() {
				$("#formPopup").simpledialog2().resize();
				
				bindEvents();
				
				initialisation();
				
				cartodb.createVis('map', 'http://gisdayatthales.cartodb.com/api/v2/viz/2e43a0c6-4bbf-11e3-9010-6d1de2be0463/viz.json', {
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
												
						refreshLayer();
						// you can get the native map to work with it
						// depending if you use google maps or leaflet
						map = vis.getNativeMap();

						// now, perform any operations you need
						// map.setZoom(3)
						// map.setCenter(new google.maps.Latlng(...))
						$('.cartodb-logo').html('<a href="https://www.thalesgroup.com/en/homepage/canada"><img src="img/ThalesLogo.png" class="thalesLogo" title="Thales Canada inc." alt="Thales Canada inc." /></a>');
						
					})
					.error(function(err) {
						console.log(err);
					});
				
				getLocation(function(location) {
					getClosestOffice(location, function(feature) {
						if (feature) {
							getCountryByOffice(feature.properties.cartodb_id, function(countryId) {
								selectedOfficeId = feature.properties.cartodb_id;
								$('#countrySelect').val(countryId).change();
							});
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

  
