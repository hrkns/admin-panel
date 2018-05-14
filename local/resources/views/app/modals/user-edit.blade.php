@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4 form-group" align = "center">
		<p>{!! term("str_profile_image") !!}</p>
		<input type = "file" name = "profile_img" style = "display:none;" id = "file_profile_img_edit_user">
		<img src = "assets/images/profile/default.jpg" style = "width:65%;" id = "profile_img_edit_user"><br>
		@if ($role_actions["update"])
			<button class = "btn btn-info" id = "change_profile_img_edit_user">{!! term("str_change_image") !!}</button><br>
			<button class = "btn btn-danger" id = "remove_profile_img_edit_user" style = "display:none;">{!! term("str_remove_image") !!}</button>
		@endif
		<br>
	</div>

	<div class="col-sm-8">
		<div class = "col-sm-6 form-group">
			<p>{!! term("str_fullname") !!}</p>
			<input type = "text" name = "fullname" class = "form-control" {!! !$role_actions["update"]?"readonly":"" !!}>
		</div>
		<div class = "col-sm-6 form-group">
			<p>{!! term("str_nick") !!}</p>
			<input type = "text" name = "nick" class = "form-control" {!! !$role_actions["update"]?"readonly":"" !!}>
		</div>
		<div class = "col-sm-6 form-group">
			<p>{!! term("str_email") !!}</p>
			<input type = "text" name = "email" class = "form-control" {!! !$role_actions["update"]?"readonly":"" !!}>
		</div>
		<div class = "col-sm-6 form-group">
			<p>{!! term("str_password") !!}</p>
			<input type = "password" name = "password" class = "form-control" placeholder = "{!! term("str_leave_password_empty_if_no_change") !!}" {!! !$role_actions["update"]?"readonly":"" !!}>
		</div>
		<div class = "col-sm-6 form-group">
			<p>{!! term("str_role") !!}</p>
			<select class = "form-control" name = "role" {!! !$role_actions["update"]?"disabled":"" !!}>
				@foreach ($roles as $value)
					<option value = "{!! $value["id"] !!}">{!! translate($value["name"]) !!}</option>
				@endforeach
			</select>
		</div>
		<div class = "col-sm-6 form-group" data-controls = "statuses">
			@include("app.templates.statuses-select")
		</div>
	</div>
</div>

<hr>

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

@include("app.modals.include.modal-footer")