@include ("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-12">
		<select class = "form-control" style = "width:35%;" id = "copy_selected_items_to_select">
			<!-- -->
		</select>
	</div>
</div>

@include ("app.modals.include.modal-footer", [
	"buttons" => [
		"submit" => [
			"term" => "str_copy"
		]
	]
])