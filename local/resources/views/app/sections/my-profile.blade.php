<div id = "my_profile_content" style = "display:none;">
	<div class = "container" style = "width:100%;" align = "center">
		<form id = "form_user_update">
			<div class = "row">
				<div class = "col-sm-4">
					<br>
					<p>{!! term("str_profile_image") !!}</p>
					<input type = "file" name = "profile_img" style = "display:none;" id = "file_profile_img_edit_user">
					<img src = "{!! DEFAULT_PROFILE_IMG !!}" style = "width:65%;" id = "profile_img_edit_user"><br>
					<button class = "btn btn-info" id = "change_profile_img_edit_user">{!! term("str_change_image") !!}</button><br>
					<button class = "btn btn-danger" id = "remove_profile_img_edit_user" style = "display:none;">{!! term("str_remove_image") !!}</button>
					<br>
				</div>
				<div class = "col-sm-8">
					<div class = "row">
						<div class = "col-sm-6">
							<br>
							<p>{!! term("str_fullname") !!}</p>
							<input type = "text" name = "fullname" class = "form-control" value = "" autocomplete = "off">
						</div>
						<div class = "col-sm-6">
							<br>
							<p>{!! term("str_nick") !!}</p>
							<input type = "text" name = "nick" class = "form-control" value = "" autocomplete = "off">
						</div>
						<div class = "col-sm-6">
							<br>
							<p>{!! term("str_email") !!}</p>
							<input type = "email" name = "email" class = "form-control" value = "" autocomplete = "off">
						</div>
						<div class = "col-sm-6">
							<br>
							<p>{!! term("str_password") !!}</p>
							<input type = "password"  value = "" autocomplete = "off" name = "password" class = "form-control" placeholder = "{!! term("str_leave_password_empty_if_no_change") !!}">
						</div>
					</div>
				</div>
			</div>
			<h5 style = "display:inline;font-weight:bold;">{!! term("str_communication_routes") !!}</h5>&nbsp;<button style = "display:inline;" class = "btn" id = "add_communication_route_edit_user">{!! term("str_add") !!}</button><br><br>
			<table class = "table table-responsive table-bordered table-striped table-hover">
				<thead>
					<td><p><strong>{!! term("str_type") !!}</strong></p></td>
					<td><p><strong>{!! term("str_value") !!}</strong></p></td>
					<td><p><strong></strong></p></td>
				</thead>
				<tbody id = "list_communication_routes_edit_user">
				</tbody>
			</table>
			<input id = "send_form" type = "submit" value = "{!! term("str_save_changes", true) !!}" class = "btn btn-primary">
			<br>
			<br>
			<br>
			<br>
			<br>
		</form>
	</div>
	@include("app.sections.include.section-resources")
</div>