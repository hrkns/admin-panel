@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-12 form-group" align = "left">
		<p>{!! term("str_admins") !!}&nbsp;<a id = "add_admin_edit_thread" href = "javascript:;"><i class = "fa fa-plus"></i></a></p>
		<div id = "thread_update_list_admins" class = "container" style = "width:100%;">
		</div>
	</div>
</div>

@include("app.modals.include.modal-footer", [
	"buttons" => [
		"submit_plus" => false,
		"submit" => [
			"term" => "str_add_administrators"
		]
	]
])