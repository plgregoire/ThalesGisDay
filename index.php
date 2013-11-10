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
        <meta name="viewport" content="width=device-width">

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
		<div id="map"></div>

		<div>
			<div id="formPopup" style="display:none" data-options='{"mode":"blank","blankContentAdopt":true,"headerText":"Thales GIS Day","headerClose":false,"blankContent":true, "fullScreen":true}'>
				<div class="dialogContent">
					<div >
						<label class="formInstruction">Select the transportation mode you used to commute today.</label>
					</div>
					<div >
						<label for="countrySelect">Country:</label>
						<select name="countrySelect" id="countrySelect" data-mini="true">
							<?php
										
								$json = file_get_contents("http://gisdayatthales.azurewebsites.net/countries.php");
								$data = json_decode($json);
								foreach ($data->rows as $row){
									echo '<option value="' . htmlspecialchars($row->cartodb_id) . '">' 
										. htmlspecialchars($row->country) 
										. '</option>';
								}						
							?>
						</select>
					</div>
					<div >
						<label for="thalesOfficeSelect" >Thales Office:</label>
						<select name="thalesOfficeSelect" id="thalesOfficeSelect" data-mini="true">
							<?php
										
								$json = file_get_contents("http://gisdayatthales.azurewebsites.net/office.php");
								$data = json_decode($json);
								foreach ($data->rows as $row){
									echo '<option value="' . htmlspecialchars($row->cartodb_id) . '">' 
										. htmlspecialchars($row->name) 
										. '</option>';
								}						
							?>
						</select>
					</div>
					<div >			
						<label for="transportationInput" >Transportation Mode:</label>
						<select name="transportationInput" id="transportationInput" tabindex="2" data-mini="true">
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
					
					<div style="text-align: center; white-space:nowrap;">		
						<button id="seeResultButton" style="position: absolute, top: 50%;" data-inline="true">See Results</button>
						<button id="submitButton" style="position: absolute, top: 50%;" data-icon="check" data-inline="true" data-theme="b">Submit</button>
					</div>
				</div>
			</div>
		</div>
		
		<script src="js/main.js"></script>
		<script type="text/javascript">
			var map;
			var layer;
			var refreshTimeOut;
			 
			 function refreshLayer(){
				window.clearTimeout(refreshTimeOut);
				layer.setQuery(layer.getQuery());
				refreshTimeOut = setTimeout(function(){refreshLayer();},5000);
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
					});
				});
				
				$('#submitButton').click(function(){
												$.mobile.sdCurrentDialog.close();
												submit( map.getCenter().lat, 
														map.getCenter().lng, 
														$('#thalesOfficeSelect').val(), 
														$('#transportationInput').val(),
														function(){
															
															refreshLayer();
														});
										});
				
				$('#seeResultButton').click(function(){
													$.mobile.sdCurrentDialog.close();
												});
			 }
			 
			window.onload = function() {
				//$("#formPopup").popup("open");
			$("#formPopup").simpledialog2();
				cartodb.createVis('map', 'http://thalesgisday.cartodb.com/api/v2/viz/05106560-4640-11e3-9bc2-0f8a20733a5f/viz.json', {
						shareable: false,
						title: false,
						description: false,
						search: false,
						tiles_loader: false,
						legends:false,
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
						$('.cartodb-logo').html('<a href="https://www.thalesgroup.com/en/homepage/canada"><img src="img/ThalesLogo.png" style="position:absolute; bottom:8px; left:8px; height:29px!important; display:block; border:none; outline:none;" title="Thales Canada inc." alt="Thales Canada inc." /></a>');
						
					})
					.error(function(err) {
						console.log(err);
					});
				
				getLocation(function(location) {
					getClosestOffice(location, function(feature) {
						if (feature) {
							$('#thalesOfficeSelect').val(feature.properties.cartodb_id).change();
							if (map) {
								map.panTo(new L.LatLng(feature.geometry.coordinates[1], feature.geometry.coordinates[0]), null);
								map.setZoom(8);
							}	
						
						}
					});
				});
				
				
				
				bindEvents();
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

  
