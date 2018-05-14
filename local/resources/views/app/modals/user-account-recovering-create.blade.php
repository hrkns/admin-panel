@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-6">
		<input type = "radio" name = "radio_choice_recovering_account_mode" value = "link">&nbsp;
		<span>
			{!! term("str_using_link") !!}
		</span>
	</div>
	<div class = "col-sm-6">
		<input type = "radio" name = "radio_choice_recovering_account_mode" value = "data">&nbsp;
		<span>
			{!! term("str_with_new_password") !!}
		</span>
		<br>
		<input type = "password" class = "form-control" id = "new_password_recover_account" placeholder = "{!! term("str_leave_empty_to_generate_a_random_password") !!}">
	</div>
</div>

@include("app.modals.include.modal-footer", [
	"buttons" => [
		"submit" => [
			"term" => "str_send",
			"class" => "success"
		],
		"submit_plus" => false,
	]
])