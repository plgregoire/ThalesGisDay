<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<link rel="shortcut icon" href="http://designshack.net/favicon.ico">
		<link rel="icon" href="http://designshack.net/favicon.ico">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/normalize.min.css">
        <link rel="stylesheet" href="css/main.css">
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
		<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" />
		<!--[if lte IE 8]>
			<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.ie.css" />
		<![endif]-->

		<script src="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js"></script>

	
		
		<link rel="stylesheet" href="http://libs.cartocdn.com/cartodb.js/v3/themes/css/cartodb.css" />
		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="http://libs.cartocdn.com/cartodb.js/v3/themes/css/cartodb.ie.css" />
		<![endif]-->
		<script src="http://libs.cartocdn.com/cartodb.js/v3/cartodb.js"></script>
	</head>
	
	<body data-role="page">
		
		<div id="map"></div>

		<div data-role="content">
			<div data-role="popup" class="ui-content" data-dismissible="false" id="formPopup" aria-disabled="false" data-disabled="false" data-overlay-theme="a" data-shadow="true" data-corners="true" data-transition="none" data-position-to="window" >
				<a href="#" data-rel="back" data-role="button" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
				<form id="commuteform" name="commuteform" method="post" action="index.php" style="line-height:2;">
					<div data-role="fieldcontain">
						<label for="thalesOfficeSelect" class="select">Thales Office:</label>
						<select name="thalesOfficeSelect" data-native-menu="false" id="thalesOfficeSelect">
							<?php
										
								$json = file_get_contents("http://gisdayatthales.azurewebsites.net/office.php");
								$data = json_decode($json);
								foreach ($data->features as $feature){
									echo '<option value="' . htmlspecialchars($feature->properties->cartodb_id) . '">' 
										. htmlspecialchars($feature->properties->address) 
										. '</option>';
								}						
							?>
						</select>
					</div>
					<div data-role="fieldcontain">			
						<label for="transportationInput" class="select">Transportation Mode:</label>
						<select name="transportationInput" id="transportationInput" tabindex="2">
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
					
				
					<div style="text-align: center;">		
						<button style="position: absolute, top: 50%;" type="submit" data-icon="check" data-inline="true" data-theme="b">Submit</button>
					</div>
	
						
					</div>
				</form>
			</div>
		</div>
		
		<script src="js/main.js"></script>
		<script type="text/javascript">
			
			 
			window.onload = function() {
				$("#formPopup").popup("open");
			
				cartodb.createVis('map', 'http://thalesgisday.cartodb.com/api/v2/viz/05106560-4640-11e3-9bc2-0f8a20733a5f/viz.json', {
					shareable: false,
					title: false,
					description: false,
					search: false,
					tiles_loader: false,
                                        legends:false,
					center_lat: 46.81,
					center_lon: -71.31,
					zoom: 6
				})
				.done(function(vis, layers) {
				  // layer 0 is the base layer, layer 1 is cartodb layer
				  // setInteraction is disabled by default
				  layers[1].setInteraction(true);
				  layers[1].on('featureOver', function(e, pos, latlng, data) {
					cartodb.log.log(e, pos, latlng, data);
				  });

				  // you can get the native map to work with it
				  // depending if you use google maps or leaflet
				  map = vis.getNativeMap();

				  // now, perform any operations you need
				  // map.setZoom(3)
				  // map.setCenter(new google.maps.Latlng(...))
				})
				.error(function(err) {
					console.log(err);
				});
				
				getLocation(function(location) {
					getClosestOffice(location, function(feature) {
						if (feature) {
							$('#thalesOfficeSelect').val(feature.properties.cartodb_id).change();
							
							if (map) {
								map.panTo(feature.geometry.coordinates, null);
								map.setZoom(8);
							}
						}
					});
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

  

