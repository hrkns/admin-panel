{{-- This is the view where the Terms of Service/Use and Privacy Policy (TSPP) is print --}}
<!DOCTYPE html>
<html>
	<head>
		<title>{!! term('str_terms_of_use_and_privacy_policy', true) !!}</title>
		<link rel="shortcut icon" href="{!! $tab_icon !!}"/>
		<meta charset="utf-8"/>
	</head>
	<body>
		<div style "width:100%;" align = "center">
			<a href = "{!! WEB_ROOT !!}">{!! term('str_go_back', true) !!}</a>
		</div>
		<br>
		<br>
		<div style = "padding:5%;">
			{{-- The TSPP is print here --}}
			{!! $terms_of_use_and_privacy_policy !!}
		</div>
		<br>
		<br>
		<div style "width:100%;" align = "center">
			<a href = "{!! WEB_ROOT !!}">{!! term('str_go_back', true) !!}</a>
		</div>
	</body>
</html>