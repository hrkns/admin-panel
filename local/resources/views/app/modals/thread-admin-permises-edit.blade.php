@include("app.modals.include.modal-header")

<div class = "row">
	<div class="col-sm-3">
	</div>
	<div  align = "left" class="col-sm-6">
		<input type="checkbox" data-name="delete_thread">
		<span>&nbsp;&nbsp;{!! term("str_let_delete_thread") !!}</span>
		<br>

		<input type="checkbox" data-name="edit_title">
		<span>&nbsp;&nbsp;{!! term("str_edit_title") !!}</span>
		<br>

		<input type="checkbox" data-name="edit_description">
		<span>&nbsp;&nbsp;{!! term("str_edit_description") !!}</span>
		<br>

		<input type="checkbox" data-name="add_admin">
		<span>&nbsp;&nbsp;{!! term("str_add_admin") !!}</span>
		<br><br>

		<input type="radio" name="30655101674321705" data-name="no_remove_admin">
		<span>&nbsp;&nbsp;{!! term("str_no_remove_admin") !!}&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<br>
		<input type="radio" name="30655101674321705" data-name="remove_any_admin">
		<span>&nbsp;&nbsp;{!! term("str_remove_any_admin") !!}&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<br>
		<input type="radio" name="30655101674321705" data-name="remove_specific_admin">
		<span>&nbsp;&nbsp;{!! term("str_remove_specific_admin") !!}</span>
		<br><br>

		<input type="radio" name="7801064754863871" data-name="no_set_permises_admin">
		<span>&nbsp;&nbsp;{!! term("str_no_set_permises_admin") !!}&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<br>

		<input type="radio" name="7801064754863871" data-name="set_permises_any_admin">
		<span>&nbsp;&nbsp;{!! term("str_set_permises_any_admin") !!}&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<br>

		<input type="radio" name="7801064754863871" data-name="set_permises_specific_admin">
		<span>&nbsp;&nbsp;{!! term("str_set_permises_specific_admin") !!}</span>
		<br><br>

		<input type="checkbox" data-name="set_privacy">
		<span>&nbsp;&nbsp;{!! term("str_set_privacy") !!}</span>
		<br>

		<input type="checkbox" data-name="accept_join_requests">
		<span>&nbsp;&nbsp;{!! term("str_accept_join_requests") !!}</span>
		<br>

		<input type="checkbox" data-name="reject_join_requests">
		<span>&nbsp;&nbsp;{!! term("str_reject_join_requests") !!}</span><br>
	</div>
	<div class="col-sm-3">
	</div>
</div>

@include("app.modals.include.modal-footer", [
	"buttons" => [
		"submit" => [
			"term" => "str_ok",
			"class" => "success"
		],
	]
])