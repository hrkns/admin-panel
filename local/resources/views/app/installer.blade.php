@include("app.installer.html_start")
@include("app.installer.head")
	<body>
		<form id = "form">
			<div class = "container">
				{{-- db credentials --}}
				<div class = "row">
					<div class = "col-sm-12">
						<h3>{!! term("str_db_credentials", true) !!}</h3>
					</div>
					<div class = "col-sm-3">
						<br>
						<p><strong>{!! term("str_db_server_address", true) !!}</strong></p>
						<input type="text" name="db_address" value = "{!! $settings["db_address"] !!}" class = "form-control">
					</div>
					<div class = "col-sm-3">
						<br>
						<p><strong>{!! term("str_db_name", true) !!}</strong></p>
						<input type="text" name="db_name" value = "{!! $settings["db_name"] !!}" class = "form-control">
					</div>
					<div class = "col-sm-3">
						<br>
						<p><strong>{!! term("str_db_user", true) !!}</strong></p>
						<input type="text" name="db_user" value = "{!! $settings["db_user"] !!}" class = "form-control">
					</div>
					<div class = "col-sm-3">
						<br>
						<p title = "{!! term("str_leave_empty_if_doesnt_change_password", true) !!}"><strong>{!! term("str_db_password", true) !!}</strong></p>
						<input type="password" name="db_password" placeholder = "{!! term("str_leave_empty_if_doesnt_change_password", true) !!}" class = "form-control">
					</div>
					<div class = "col-sm-12">
						<br>
						<p>{!! term("str_db_credentials_explanation_part_1", true)." <strong>".FILE_ADMIN_PANEL_SETTINGS."</strong> ".term("str_db_credentials_explanation_part_2", true)." <strong>".PROJECT_SYSTEM_ROOT."/config/database.php" !!}</strong></p>
					</div>
				</div>

				<hr>

				{{-- mailer config --}}
				<div class = "row">
					<div class = "col-sm-12">
						<h3>{!! term("str_smtp_config", true) !!}</h3>
					</div>
					<div class = "col-sm-3">
						<br>
						<p><strong>{!! term("str_smtp_host", true) !!}</strong></p>
						<input type="text" name="smtp_host" value = "{!! $settings["smtp_host"] !!}" class = "form-control">
					</div>
					<div class = "col-sm-3">
						<br>
						<p><strong>{!! term("str_smtp_port", true) !!}</strong></p>
						<input type="text" name="smtp_port" value = "{!! $settings["smtp_port"] !!}" class = "form-control">
					</div>
					<div class = "col-sm-3">
						<br>
						<p><strong>{!! term("str_smtp_email_from", true) !!}</strong></p>
						<input type="text" name="smtp_email_from" value = "{!! $settings["smtp_email_from"] !!}" class = "form-control">
					</div>
					<div class = "col-sm-3">
						<br>
						<p title = "{!! term("str_leave_empty_if_doesnt_change_password", true) !!}"><strong>{!! term("str_smtp_password_from", true) !!}</strong></p>
						<input type="password" name="smtp_password_from" placeholder = "{!! term("str_leave_empty_if_doesnt_change_password", true) !!}" class = "form-control">
					</div>
					<div class = "col-sm-3">
						<br>
						<p><strong>{!! term("str_smtp_fullname_from", true) !!}</strong></p>
						<input type="text" name="smtp_fullname_from" value = "{!! $settings["smtp_fullname_from"] !!}" class = "form-control">
					</div>
					<div class = "col-sm-3">
						<br>
						<input type="checkbox" name="smtp_secure" {!! $settings["smtp_secure"]?"checked":"" !!}>&nbsp;<strong>{!! term("str_smtp_secure", true) !!}</strong>
					</div>
					<div class = "col-sm-12">
						<br>
						<p>{!! term("str_smtp_config_explanation_part_1", true)." <strong>".FILE_ADMIN_PANEL_SETTINGS."</strong> ".term("str_smtp_config_explanation_part_2", true)." <strong>".PROJECT_SYSTEM_ROOT."/app/helpers.php" !!}</strong></p>
					</div>
				</div>

				@if (!$settings["default_user_changed"])
					<hr>
					<div class = "row">
						<div class = "col-sm-12">
							<h3>{!! term("str_default_user_credentials", true) !!}</h3>
							<p><strong>{!! term("str_default_user_nick", true) !!}: </strong>developer</p>
							<p><strong>{!! term("str_default_user_password", true) !!}: </strong>123456</p>
						</div>
					</div>
				@endif

				<div class = "row">
					<div class = "col-sm-12">

						<input type = "submit" class = "btn btn-success pull-right" value = '{!! term("str_save", true) !!}'>

						@if ($settings["installed"] > 0)
							<button class = "btn btn-danger pull-right" onclick = 'window.location.href = App.WEB_ROOT;return false;'>{!! term("str_cancel", true) !!}</button>
						@endif

					</div>
				</div>

				<br><br>
			</div>
		</form>

		@include("app.installer.footer")
	</body>
</html>