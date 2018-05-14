@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4 form-group" align = "center">
		<p>{!! term("str_image") !!}</p>
		<input type = "file" name = "img" style = "display:none;" id = "file_img_new_client">
		<img src = "{!! DEFAULT_CLIENT_IMAGE !!}" style = "width:65%;" id = "img_new_client"><br>
		<button class = "btn btn-info" id = "change_img_new_client">{!! term("str_change_image") !!}</button><br>
		<button class = "btn btn-danger" id = "remove_img_new_client" style = "display:none;">{!! term("str_remove_image") !!}</button>
	</div>
	<div class = "col-sm-8">
		<div class = "row">
			<div class = "col-sm-12 form-group">
				<p>{!! term("str_name") !!}</p>
				<input type = "text" name = "name" class = "form-control">
			</div>
			<div class = "col-sm-12 form-group" data-controls="statuses">
				@include ("app.templates.statuses-select")
			</div>
		</div>
	</div>
</div>
<hr>
<h5 style = "display:inline;font-weight:bold;">{!! term("str_communication_routes") !!}</h5>&nbsp;<button style = "display:inline;" class = "btn" id = "add_communication_route_new_client">{!! term("str_add") !!}</button><br><br>
<table class = "table table-responsive table-bordered table-striped table-hover">
	<thead>
		<td><p><strong>{!! term("str_type") !!}</strong></p></td>
		<td><p><strong>{!! term("str_value") !!}</strong></p></td>
		<td><p><strong></strong></p></td>
	</thead>
	<tbody id = "list_communication_routes_new_client">
	</tbody>
</table>

<hr>
<h5 style = "display:inline;font-weight:bold;">{!! term("str_addresses") !!}</h5>&nbsp;<button style = "display:inline;" class = "btn" id = "add_address_new_client">{!! term("str_add") !!}</button><br><br>
<table class = "table table-responsive table-bordered table-striped table-hover">
	<thead>
		<td><p><strong>{!! term("str_address") !!}</strong></p></td>
	</thead>
	<tbody id = "list_addresses_new_client">
	</tbody>
</table>

<hr>
<h5 style = "display:inline;font-weight:bold;">{!! term("str_ids") !!}</h5>&nbsp;<button style = "display:inline;" class = "btn" id = "add_real_id_new_client">{!! term("str_add") !!}</button><br><br>
<table class = "table table-responsive table-bordered table-striped table-hover">
	<thead>
		<td><p><strong>{!! term("str_type") !!}</strong></p></td>
		<td><p><strong>{!! term("str_value") !!}</strong></p></td>
		<td><p><strong></strong></p></td>
	</thead>
	<tbody id = "list_real_ids_new_client">
	</tbody>
</table>

@include("app.modals.include.modal-footer")