<div id = "box_register" style = "display:none;" align = "center">
	<a href class="navbar-brand block m-t" style = "width:100%;">{!! $globalSettings["name_of_system"] !!}</a>
	<div class="m-b-lg">
		<div class="wrapper text-center">
			<strong>{!! term('str_register_description', true) !!}</strong><br><br>
		</div>
		{{-- sign-up form --}}
			<form id="form-register">
				<div class="list-group list-group-sm">
					{{-- fullname input --}}
						<div class="list-group-item">
							<input placeholder="{!! term('str_fullname_placeholder', true) !!}" class="form-control no-border" name = "fullname">
						</div>
					{{-- nick input --}}
						<div class="list-group-item">
							<input placeholder="{!! term('str_nick_placeholder', true) !!}" class="form-control no-border" name = "nick">
						</div>
					{{-- email input --}}
						<div class="list-group-item">
							<input type="email" placeholder="{!! term('str_email_placeholder', true) !!}" class="form-control no-border" name = "email">
						</div>
					{{-- password input --}}
						<div class="list-group-item">
							<input type="password" placeholder="{!! term('str_password_placeholder', true) !!}" class="form-control no-border" name = "password">
						</div>
				</div>
				{{-- input to accept the terms of use and privacy policy --}}
					<div class="checkbox m-b-md m-t-none">
						<label class="i-checks">
							<input type="checkbox"><i></i><a href = "terms-of-use-and-privacy-policy" target = "_blank">{!! term('str_agree_terms_and_policy', true) !!}</a>
						</label>
					</div>
				{{-- input to submit sign-up form --}}
				<button type="submit" class="btn btn-lg btn-primary btn-block">{!! term('str_signup', true) !!}</button>
			</form>
	{{-- link to sign-up box --}}
		<div class="line line-dashed"></div><br><br>
			<p class="text-center"><small>{!! term('str_already_have_an_account', true) !!}</small></p>
			<a class="btn btn-lg btn-default btn-block" id = "show_box_login">{!! term('str_sign_in', true) !!}</a>
		</div>
</div>