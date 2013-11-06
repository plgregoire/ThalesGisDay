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

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

		<script type="text/javascript">
		
		
			$(function() {
				  $( "#formDiv" ).dialog({
					  autoOpen: true,
					  height: 300,
					  width: 350,
					  resizable: false,
					  draggable: false,
					  modal: true,
					  buttons: {
						"Commute": function() {
						  var bValid = true;
						  allFields.removeClass( "ui-state-error" );
				 
						  if ( bValid ) {
							$( this ).dialog( "close" );
						  }
						}
					  },
					  close: function() {
						allFields.val( "" ).removeClass( "ui-state-error" );
					  }
				});
				
				$(window).resize(function() {
					$("#formDiv").dialog("option", "position", "center");
				});
			});
		</script>
	</head>
	
	
	<body>
		<div id="formDiv" class="commuteFormDiv">
			<form id="commuteform" name="commuteform" method="post" action="index.php" style="line-height:2;">
				
				<label for="thalesOfficeSelect" >Thales Office:</label>
				<select name="thalesOfficeSelect"  style="width:100%" id="thalesOfficeSelect" class="text ui-widget-content ui-corner-all" tabindex="1">
					<option value="quebec">Quebec</option>
				</select>

				<label for="transportationInput" >Transportation Mode:</label>
				<select  name="transportationInput" id="transportationInput" style="width:100%" class="text ui-widget-content ui-corner-all" tabindex="2">
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
			</form>
		</div>
		
		<iframe width='100%' style="position: absolute; height: 100%" class="mapFrame" frameborder='0' src='http://thalesgisday.cartodb.com/viz/05106560-4640-11e3-9bc2-0f8a20733a5f/embed_map?title=false&description=false&search=false&shareable=false&cartodb_logo=true&layer_selector=false&legends=false&scrollwheel=true&sublayer_options=1&sql=&sw_lat=45.73685954736049&sw_lon=-5.053710937499999&ne_lat=52.74959372674114&ne_lon=16.040039062499996'></iframe>
        <script src="js/main.js"></script>
		

        <!--<script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>-->
	</body>
</html>




  

