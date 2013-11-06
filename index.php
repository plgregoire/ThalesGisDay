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
		<!--
		<iframe width='100%' style="position: absolute; height: 100%" class="mapFrame" frameborder='0' src='http://thalesgisday.cartodb.com/viz/05106560-4640-11e3-9bc2-0f8a20733a5f/embed_map?title=false&description=false&search=false&shareable=false&cartodb_logo=true&layer_selector=false&legends=false&scrollwheel=true&sublayer_options=1&sql=&sw_lat=45.73685954736049&sw_lon=-5.053710937499999&ne_lat=52.74959372674114&ne_lon=16.040039062499996'></iframe>
        -->
		<div id="map"></div>
		
		<div data-role="content">
			<div id="formPopup" data-role="popup" class="ui-content" data-theme="a" data-transition="pop">
				<form id="commuteform" name="commuteform" method="post" action="index.php" style="line-height:2;">
					<label for="thalesOfficeSelect" >Thales Office:</label>
					<select name="thalesOfficeSelect" style="width:100%" id="thalesOfficeSelect" data-native-menu="false" tabindex="1">
						<option value="quebec">Quebec</option>
					</select>

					<label for="transportationInput" >Transportation Mode:</label>
					<select name="transportationInput" id="transportationInput" style="width:100%" data-native-menu="false" tabindex="2">
						<option value="bus">Bus</option>
						<option value="bike">Bike</option>
						<option value="car">Car</option>
						<option value="carpooling">Car Pooling</option>
						<option value="ferry">Ferry</option>
						<option value="motorbike">Motorbike</option>
						<option value="subway">Subway</option>
						<option value="train">Train</option>
						<option value="tramway">Tramway</option>
						<option value="walking">Walking</option>
						<option value="others">Others</option>
					</select> 
					
					<input type="submit" name="submit" id="submit" value="Submit" data-theme="b" data-icon="arrow-r" data-iconpos="right" />
				</form>
			</div>
		</div>
		
		<script src="js/main.js"></script>
		<script type="text/javascript">
			getLocation();
						
			/*$('#formDiv').simpledialog({
				'mode': 'string',
				'buttons': {
					"Commute": function() {
						  var bValid = true;
						  allFields.removeClass( "ui-state-error" );
				 
						  if ( bValid ) {
							$( this ).dialog( "close" );
						  }
						}
				}
			});*/
			
			/*$(":jqmData(role='page'):last").on("pageshow", function (event) {	
				$('#formDiv', $(this)).popup("open", { history:false });
			});*/
			
			// $(function() {
				  // $( "#formDiv" ).dialog({
					  // autoOpen: true,
					  // height: 300,
					  // width: 350,
					  // resizable: false,
					  // draggable: false,
					  // modal: true,
					  // buttons: {
						// "Commute": function() {
						  // var bValid = true;
						  // allFields.removeClass( "ui-state-error" );
				 
						  // if ( bValid ) {
							// $( this ).dialog( "close" );
						  // }
						// }
					  // },
					  // close: function() {
						// allFields.val( "" ).removeClass( "ui-state-error" );
					  // }
				// });
				
				// $(window).resize(function() {
					// $("#formDiv").dialog("option", "position", "center");
				// });
			 //});
			 
			window.onload = function() {
				$("#formPopup").popup("open", {history:false, dismissible: false});
			/*
				var map = new L.Map('map', {
					center: [0,0],
					zoom: 2
				});

				 cartodb.createLayer(map, 'http://thalesgisday.cartodb.com/api/v2/viz/05106560-4640-11e3-9bc2-0f8a20733a5f/viz.json')
					.addTo(map)
					.on('done', function(layer) {
						//do stuff
					})
					.on('error', function(err) {
					  alert("some error occurred: " + err);
				});
			*/
				cartodb.createVis('map', 'http://thalesgisday.cartodb.com/api/v2/viz/05106560-4640-11e3-9bc2-0f8a20733a5f/viz.json', {
					shareable: false,
					title: false,
					description: false,
					search: false,
					tiles_loader: false,
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

  

