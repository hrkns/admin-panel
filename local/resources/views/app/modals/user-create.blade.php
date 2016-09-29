@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4 form-group" align = "center">
		<p>{!! term("str_profile_image") !!}</p>
		<input type = "file" name = "profile_img" style = "display:none;" id = "file_profile_img_new_user">
		<img src = "assets/images/profile/default.jpg" style = "width:65%;" id = "profile_img_new_user"><br>
		<button class = "btn btn-info" id = "change_profile_img_new_user">{!! term("str_change_image") !!}</button><br>
		<button class = "btn btn-danger" id = "remove_profile_img_new_user" style = "display:none;">{!! term("str_remove_image") !!}</button>
		<br>
	</div>
	<div class="col-sm-8">
		<div class = "col-sm-6 form-group">
			<p>{!! term("str_fullname") !!}</p>
			<input type = "text" name = "fullname" class = "form-control">
		</div>
		<div class = "col-sm-6 form-group">
			<p>{!! term("str_nick") !!}</p>
			<input type = "text" name = "nick" class = "form-control">
		</div>
		<div class = "col-sm-6 form-group">
			<p>{!! term("str_email") !!}</p>
			<input type = "text" name = "email" class = "form-control">
		</div>
		<div class = "col-sm-6 form-group">
			<p>{!! term("str_password") !!}</p>
			<input type = "password" name = "pass" class = "form-control">
		</div>
		<div class = "col-sm-6 form-group">
			<p>{!! term("str_role") !!}</p>
			<select class = "form-control" name = "role">
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

<h5 style = "display:inline;font-weight:bold;">{!! term("str_communication_routes") !!}</h5>&nbsp;<button style = "display:inline;" class = "btn" id = "add_communication_route_new_user">Agregar</button><br><br>

<table class = "table table-responsive table-bordered table-striped table-hover">
	<thead>
		<td><p><strong>{!! term("str_type") !!}</strong></p></td>
		<td><p><strong>{!! term("str_value") !!}</strong></p></td>
		<td><p><strong></strong></p></td>
	</thead>
	<tbody id = "list_communication_routes_new_user">
	</tbody>
</table>

<div style = "display:none;">
	<hr>
	<h5 style = "display:inline;font-weight:bold;">{!! term("str_roles_in_organizations") !!}</h5>&nbsp;<button style = "display:inline;" class = "btn" id = "add_role_in_organization_new_user">Agregar</button><br><br>
	<table class = "table table-responsive table-bordered table-striped table-hover">
		<thead>
			<td><p><strong>{!! term("str_organization") !!}</strong></p></td>
			<td><p><strong>{!! term("str_role") !!}</strong></p></td>
			<td><p><strong></strong></p></td>
		</thead>
		<tbody id = "list_roles_in_organization_new_user">
		</tbody>
	</table>
</div>

@include("app.modals.include.modal-footer")