@include ("app.modals.include.modal-header")

<div class = "col-sm-12">
	<select class = "form-control" style = "width:35%;" id = "move_selected_items_to_select">
	</select>
</div>

@include ("app.modals.include.modal-footer", [
	"buttons" => [
		"submit" => [
			"term" => "str_move"
		]
	]
])