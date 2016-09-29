{{-- place in a hidden input the language of the system (in order to be used for some javascript code) --}}
<input type = "hidden" id = "lng" value = "{!! __LNG__ !!}">

<div id = "box_login" align = "center">
	<a href class="navbar-brand block m-t" style = "width:100%;">{!! $globalPreferences["name_of_system"] !!}</a>
	<div class="m-b-lg">
		{{-- login description --}}
			<div class="wrapper text-center">
				<br>
				<br>
				<br>
				<strong>{!! term('str_login_description', true) !!}</strong>
				<br>
				<br>
			</div>

		{{-- login form --}}
			<form name="form" class="form-validation" id = "form-login">
				<div class="list-group list-group-sm">
					<div class="list-group-item">
						{{-- email or nick input --}}
						<input type="text" placeholder="{!! term('str_email_or_nick_placeholder', true) !!}" class="form-control no-border" name = "username">
					</div>
					<div class="list-group-item">
						{{-- password input --}}
						<input type="password" placeholder="{!! term('str_password_placeholder', true) !!}" class="form-control no-border" name = "password">
					</div>
				</div>
				{{-- submit input --}}
				<button type="submit" class="btn btn-lg btn-primary btn-block">{!! term('str_log_in', true) !!}</button>
			</form>

		{{-- link to recover-account view/box --}}
			<div class="text-center m-t m-b"><a href = "javascript:;" id = "click_show_box_forgot_from_login"><br>{!! term('str_forgotten_password', true) !!}</a><br><br></div>
			<div class="line line-dashed"></div>

		{{-- if it's configured to let anybody sign-up by himself, show the link to the sign-up box --}}
			@if (intval($globalPreferences["let_register_user"]) == 1)
				<p class="text-center"><small>{!! term('str_do_not_have_an_account', true) !!}</small></p>
				<a ui-sref="access.signup" class="btn btn-lg btn-default btn-block" id = "show_create_account">{!! term('str_create_an_account', true) !!}</a>
			@endif
	</div>
</div>