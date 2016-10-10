	{{-- method 'GetForUse' is in 'local/app/helpers.php' --}}
	<?php
		$languages = GetForUse("MasterLanguage");
		$parameters = array(
			"terms"=>terms(),
			"globalSettings"=>$globalSettings
		);
	?>
	<body>
		<div class = "col-sm-9">
			{{-- empty column --}}
		</div>
		<div class = "col-sm-3">
				{{-- print a select containing all the availables languages that can be used to navigate in the app
				{{-- it is shown as selected the language previously choosen by the user, or the app default language --}}
			<br>
			<select class = "form-control pull-right" id = "select_set_user_session_lng">
				@foreach ($languages as $value)
					<option {!! $value->code == __LNG__?"selected":"" !!} value = "{!! $value->code !!}">{!! translate($value["name"]) !!}</option>
				@endforeach
			</select>
			<br>
			<br>
		</div>
		<div class = "col-sm-4">
			{{-- empty column --}}
		</div>
		<div class="main-login col-sm-4">
			{{--  print the login box  --}}
			@include('app.not-logged.box-login', $parameters)

			{{--if it is configured to let anybody could register, print the sign-up box --}}

			@if(intval($globalSettings["let_register_user"])==1)
				@include('app.not-logged.box-register', $parameters)
			@endif

			{{-- print the recover-account box --}}
			@include('app.not-logged.box-forgot', $parameters)
		</div>

		{{-- print the final elements of the main view --}}
		@include('app.not-logged.footer', $parameters)
	</body>
</html>