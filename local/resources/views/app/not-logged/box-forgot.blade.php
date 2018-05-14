<div id = "box_forgot" style = "display:none;" align = "center">
	<a href class="navbar-brand block m-t" style = "width:100%;">{!! $globalSettings["name_of_system"] !!}</a>
	<div class="m-b-lg">
		<div class="wrapper text-center">
			<strong>{!! term('str_reset_password_description', true) !!}</strong><br><br>
		</div>
		{{-- form to recover account --}}
		<form id = "form_forgot">
			<div class="list-group list-group-sm">
				<div class="list-group-item">
					{{-- input email --}}
					<input type="email" placeholder="{!! term('str_email_placeholder', true) !!}" class="form-control no-border" name = "email">
				</div>
			</div>
			{{-- input submit --}}
			<button type="submit" class="btn btn-lg btn-primary btn-block">{!! term('str_reset', true) !!}</button>
		</form>
		<div align = "center">
			{{-- link to login box --}}
			<br>
			<a href = "javascript:;" id = "show_login_from_forgot">{!! term('str_back', true) !!}</a>
			<br>
		</div>
		<div collapse="isCollapsed" class="m-t" style = "display:none;" id = "message_recover_account_success">
			<div class="alert alert-success">
				<p>{!! term('str_reset_password_email_already_sent', true) !!}&nbsp;<a ui-sref="access.signin" id = "show_box_login_from_forgot" class="btn btn-sm btn-success">{!! term('str_sign_in', true) !!}</a></p>
			</div>
		</div>
	</div>
</div>