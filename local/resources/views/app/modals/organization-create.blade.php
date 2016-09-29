@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4 form-group" data-controls="statuses">
		<p>{!! term("str_address") !!}</p>
		<input type = "file" name = "img" style = "display:none;" id = "file_img_new_organization">
		<img src = "assets/images/organization/default.jpg" style = "width:65%;" id = "img_new_organization"><br>
		<button class = "btn btn-info" id = "change_img_new_organization">{!! term("str_change_image") !!}</button><br>
		<button class = "btn btn-danger" id = "remove_img_new_organization" style = "display:none;">{!! term("str_undo") !!}</button>
	</div>
	<div class = "col-sm-8">
		<div class = "row">
			<div class = "col-sm-12 form-group">
				<p>{!! term("str_name_organization") !!}</p>
				<input type = "text" name = "name" class = "form-control">
			</div>
			<div class = "col-sm-12 form-group" align = "center">
				@include ("app.templates.statuses-select")
			</div>
		</div>
	</div>
</div>
<hr>
<h5 style = "display:inline;font-weight:bold;">{!! term("str_communication_routes") !!}</h5>&nbsp;<button style = "display:inline;" class = "btn" id = "add_communication_route_new_organization">{!! term("str_add") !!}</button><br><br>
<table class = "table table-responsive table-bordered table-striped table-hover">
	<thead>
		<td><p><strong>{!! term("str_type") !!}</strong></p></td>
		<td><p><strong>{!! term("str_value") !!}</strong></p></td>
		<td><p><strong></strong></p></td>
	</thead>
	<tbody id = "list_communication_routes_new_organization">
	</tbody>
</table>
<hr>
<h5 style = "display:inline;font-weight:bold;">{!! term("str_addresses") !!}</h5>&nbsp;<button style = "display:inline;" class = "btn" id = "add_address_new_organization">{!! term("str_add") !!}</button><br><br>
<table class = "table table-responsive table-bordered table-striped table-hover">
	<thead>
		<td><p><strong>{!! term("str_address") !!}</strong></p></td>
	</thead>
	<tbody id = "list_addresses_new_organization">
	</tbody>
</table>
<hr>
<h5 style = "display:inline;font-weight:bold;">{!! term("str_ids") !!}</h5>&nbsp;<button style = "display:inline;" class = "btn" id = "add_real_id_new_organization">{!! term("str_add") !!}</button><br><br>
<table class = "table table-responsive table-bordered table-striped table-hover">
	<thead>
		<td><p><strong>{!! term("str_type") !!}</strong></p></td>
		<td><p><strong>{!! term("str_value") !!}</strong></p></td>
		<td><p><strong></strong></p></td>
	</thead>
	<tbody id = "list_real_ids_new_organization">
	</tbody>
</table>
<hr>
<h5 style = "display:inline;font-weight:bold;">{!! term("str_available_payment_methods") !!}</h5>&nbsp;<button style = "display:inline;" class = "btn" id = "add_payment_method_new_organization">{!! term("str_add") !!}</button><br><br>
<table class = "table table-responsive table-bordered table-striped table-hover">
	<thead>
		<td><p><strong>{!! term("str_method") !!}</strong></p></td>
		<td><p><strong>{!! term("str_information") !!}</strong></p></td>
		<td><p><strong></strong></p></td>
	</thead>
	<tbody id = "list_payment_methods_new_organization">
	</tbody>
</table>

@include("app.modals.include.modal-footer")