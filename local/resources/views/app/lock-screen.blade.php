<!DOCTYPE html>
<!--[if IE 8]><html class="ie8 no-js" lang="{!! __LNG__ !!}"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="{!! __LNG__ !!}"><![endif]-->
<!--[if !IE]><!-->
<html 	lang="{!! __LNG__ !!}" 
	data-web-root = "{!! WEB_ROOT !!}"
	class="no-js">
	<meta charset="utf-8" />
	<title>{!! term('str_locked_screen', true) !!}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.css" />
	<link rel="stylesheet" href="assets/libs/font-awesome/css/font-awesome.css" />
	<link rel="stylesheet" href="assets/libs/magnific-popup/magnific-popup.css" />
	<link rel="stylesheet" href="assets/libs/bootstrap-datepicker/css/datepicker3.css" />
	<link rel="stylesheet" href="assets/libs/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
	<link rel="stylesheet" href="assets/libs/bootstrap-multiselect/bootstrap-multiselect.css" />
	<link rel="stylesheet" href="assets/libs/morris/morris.css" />
	<link rel="stylesheet" href="assets/libs/jquery-datatables/media/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" href="assets/css/stylesheets/theme.css" />
	<link rel="stylesheet" href="assets/css/stylesheets/skins/default.css" />
	<link rel="stylesheet" href="assets/css/stylesheets/theme-custom.css">
	<link rel="stylesheet" href="assets/css/employee.css">
	<meta name="_token" content="{!! csrf_token() !!}"/>
	<link rel="shortcut icon" href="{!! $tab_icon !!}" id = "original_global_tab_icon"/>
	</head>
	<body>
	<div class = "container" style = "width:100%;padding:5%;">
		<div class = "row">
			<div class = "col-sm-4">
				{{-- empty column --}}
			</div>
			<div class = "col-sm-4" align = "center">
				<form id = "form_unlock_screen">
					<h1>
						{!! $name_of_system !!}
					</h1>
					<br>

					<img src="{!! PUBLIC_PROFILE_IMAGES_FOLER.$datauser["profile_img"] !!}" style = "width:200px;height:200px;border-radius:1000px;">

					<br>

					<h2>
						{!! $datauser["fullname"] !!}
					</h2>

					<p>
						<strong>
							{!! $datauser["nick"] !!}
						</strong>
					</p>

					{{-- user password input (to unlock screen) --}}
						<br>
						<input class = "form-control" type = "password" placeholder = "Password" id = "password">
						<br>

					{{-- button to submit data to unlock screen --}}
					<button class = "btn btn-success btn-block">
						{!! term('str_unlock_screen', true) !!}
					</button>
				</form>

				{{-- button to close session --}}
					<button class = "btn btn-danger btn-block" onclick = 'window.location.href = App.WEB_ROOT+"/logout";'>
						{!! term('str_close_session', true) !!}
					</button>
			</div>
			<div class = "col-sm-4">
				{{-- empty column --}}
			</div>
		</div>
	</div>
	<script src="assets/libs/modernizr/modernizr.js"></script>
	<script src="assets/libs/jquery/jquery.js"></script>
	<script src="assets/libs/jquery-browser-mobile/jquery.browser.mobile.js"></script>
	<script src="assets/libs/bootstrap/js/bootstrap.js"></script>
	<script src="assets/libs/nanoscroller/nanoscroller.js"></script>
	<script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/libs/magnific-popup/magnific-popup.js"></script>
	<script src="assets/libs/jquery-placeholder/jquery.placeholder.js"></script>
	<script src="assets/libs/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
	<script src="assets/libs/jquery-canvas/jquery.canvasjs.min.js"></script>
	<script src="assets/libs/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
	<script src="assets/libs/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
	<script src="assets/libs/jquery-appear/jquery.appear.js"></script>
	<script src="assets/libs/bootstrap-multiselect/bootstrap-multiselect.js"></script>
	<script src="assets/libs/jquery-easypiechart/jquery.easypiechart.js"></script>
	<script src="assets/libs/flot/jquery.flot.js"></script>
	<script src="assets/libs/flot-tooltip/jquery.flot.tooltip.js"></script>
	<script src="assets/libs/flot/jquery.flot.pie.js"></script>
	<script src="assets/libs/flot/jquery.flot.categories.js"></script>
	<script src="assets/libs/flot/jquery.flot.resize.js"></script>
	<script src="assets/libs/jquery-sparkline/jquery.sparkline.js"></script>
	<script src="assets/libs/raphael/raphael.js"></script>
	<script src="assets/libs/morris/morris.js"></script>
	<script src="assets/libs/gauge/gauge.js"></script>
	<script src="assets/libs/snap-svg/snap.svg.js"></script>
	<script src="assets/libs/liquid-meter/liquid.meter.js"></script>
	<script src="assets/libs/jqvmap/jquery.vmap.js"></script>
	<script src="assets/libs/jqvmap/data/jquery.vmap.sampledata.js"></script>
	<script src="assets/libs/jqvmap/maps/jquery.vmap.world.js"></script>
	<script src="assets/libs/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
	<script src="assets/libs/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
	<script src="assets/libs/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
	<script src="assets/libs/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
	<script src="assets/libs/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
	<script src="assets/libs/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>
	<script src="assets/libs/ios7-switch/ios7-switch.js"></script>
	<script src="assets/js/theme/theme.js"></script>
	<script src="assets/js/theme/theme.custom.js"></script>
	<script src="assets/js/theme/theme.init.js"></script>
	<script src="assets/js/admin-panel.js"></script>
	<script type="text/javascript">
		{{-- ajax request to unlock screen --}}
		$("#form_unlock_screen").submit(function(e){
			e.preventDefault();
			App.LockScreen();
			App.DOM_Disabling(".container");

			App.HTTP.post({
				url : App.WEB_ROOT + "/unlock-screen",
				data : {
					password : $("#password").val()
				},
				success : function(d, e, f){
					window.location.href = App.WEB_ROOT;
				}, error : function(x, y, z){
					App.UnlockScreen();
					App.DOM_Enabling(".container");
				}
			});
		});
	</script>
	</body>
</html>