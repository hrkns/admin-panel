	<head>
		<meta charset="utf-8" />
		<title id = "tab_title">
			{!! $globalPreferences["name_of_system"]; !!}
		</title>

		{{-- the AP_Asset function is located in the file 'local/app/helpers.php' --}}

		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/libs/bootstrap/css/bootstrap.css") !!}" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/libs/font-awesome/css/font-awesome.css") !!}" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/css/ionicons.css") !!}">
		<link rel="stylesheet" href="{!! AP_Asset("assets/libs/magnific-popup/magnific-popup.css") !!}" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/libs/bootstrap-datepicker/css/datepicker3.css") !!}" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/libs/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css") !!}" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/libs/bootstrap-multiselect/bootstrap-multiselect.css") !!}" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/libs/morris/morris.css") !!}" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/libs/jquery-datatables/media/css/jquery.dataTables.min.css") !!}" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/css/stylesheets/theme.css") !!}" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/css/stylesheets/skins/default.css") !!}" />
		<link rel="stylesheet" href="{!! AP_Asset("assets/css/stylesheets/theme-custom.css") !!}">
		<link rel="stylesheet" href="{!! AP_Asset("assets/css/employee.css") !!}">
		<link rel="stylesheet" href="{!! AP_Asset("assets/plugins/select2/select2.css") !!}">
		<link rel="stylesheet" href="{!! AP_Asset("assets/plugins/alertify/css/alertify.rtl.css") !!}">
		<link rel="stylesheet" href="{!! AP_Asset("assets/css/admin-panel.css") !!}">
		<link rel="stylesheet" href="{!! AP_Asset("assets/plugins/emoji/emoji.css") !!}">
		<link rel="shortcut icon" href="{!! intval($userPreferences->use_global_tab_icon) == 1?$globalPreferences["tab_icon"]:AP_Asset("assets/images/tab_icons/".$userPreferences["tab_icon"]) !!}" id = "original_global_tab_icon"/>
		<meta name="_token" content="{!! csrf_token() !!}"/>
		<meta name="use_inactivity_time_limit" content = "{!! $userPreferences->use_inactivity_time_limit_as !!}">
		<meta name="format_show_items" content = "{!! $userPreferences->format_show_items !!}">
		<meta name="format_edit_items" content = "{!! $userPreferences->format_edit_items !!}">
		<meta name="inactivity_time_limit" content = "{!! intval($userPreferences->inactivity_time_limit_amount_val) * ['seconds' => 1,'minutes' => 60,'hours' => 3600,'days' => 86400,'weeks' => 604800][$userPreferences->inactivity_time_limit_amount_type] !!}">
	</head>