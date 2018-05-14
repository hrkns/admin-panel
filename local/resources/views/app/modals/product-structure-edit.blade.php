@include("app.modals.include.modal-header")

<div style = "overflow:scroll;">
	<h4 id = "modal_product_update_title"></h4>

	<button class = "btn btn-info" id = "add_field_edit_product">{!! term("str_add_field") !!}</button><br>
	<table class = "table table-responsive table-bordered table-striped table-hover">
		<tbody >
			<tr id = "list_fields_edit_product">
			</tr>
		</tbody>
	</table>
</div>

@include("app.modals.include.modal-footer")