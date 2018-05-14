@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4 form-group">
		<p>{!! term("str_code") !!}</p>
		<input type = "text" name = "code" class = "form-control">
	</div>
	<div class = "col-sm-4 form-group">
		<p>{!! term("str_name") !!}</p>
		<input type = "text" name = "name" class = "form-control">
	</div>
	<div class = "col-sm-4 form-group">
		<p>{!! term("str_description") !!}</p>
		<textarea name = "description" class = "form-control"></textarea>
	</div>
	<div class = "col-sm-12 form-group" data-controls="statuses">
		@include ("app.templates.statuses-select")
	</div>
</div>
<h4>{!! term("str_structure") !!}</h4>
<button class = "btn btn-info" id = "add_field_new_product">{!! term("str_add_field") !!}</button><br>
<div style = "overflow:scroll;">
	<table class = "table table-responsive table-bordered table-striped table-hover">
		<tbody >
			<tr id = "list_fields_new_product">
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
<div align = "display:none;height:0px;"><br><br>
	<button class = "btn btn-info" id = "add_instance_new_product" style = "display:none;">{!! term("str_add_instance") !!}</button><br>
	<table class = "table table-responsive table-bordered table-striped table-hover">
		<tbody id = "list_instances_new_product">
		</tbody>
	</table><br><br>
</div>

@include("app.modals.include.modal-footer")