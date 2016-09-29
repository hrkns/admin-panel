<head>
	<meta charset="utf-8" />
	<title>{!! $globalPreferences["name_of_system"] !!}</title>
	<meta name="description" content="Programming Template for Administration Panel" />
	<meta name="keywords" content="admin panel back office management administration" />
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
	<link rel="stylesheet" href="assets/css/admin-panel.css">
	<meta name = "mobile-web-app-capable" content = "yes">
	<meta name = "application-name" content = "{!! $globalPreferences["name_of_system"] !!}">
	{{-- the 'AP_Asset' method is located in the 'local/app/' folder --}}
	<link rel="manifest" href="{!! AP_Asset("assets/js/manifest.json") !!}">
	<script src="assets/libs/modernizr/modernizr.js"></script>
	<link rel="stylesheet" href="assets/plugins/alertify/css/alertify.rtl.css">
	<!--[if IE 7]>
	<link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome-ie7.min.css">
	<![endif]-->
	<meta name="_token" content="{!! csrf_token() !!}"/>
	<link rel="shortcut icon" href="{!! $globalPreferences["tab_icon"] !!}" id = "original_global_tab_icon"/>
</head>