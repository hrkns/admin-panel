@include("app.modals.include.modal-header")

<h4>{!! term("str_confirmation_change_privacy_description") !!}</h4>
<input type = "checkbox" checked id = "yes_add_participants">&nbsp;{!! term("str_yes_add_participants") !!}

@include("app.modals.include.modal-footer", [
	"buttons" => [
		"submit" => [
			"term" => "str_ok",
			"class" => "success"
		],
	]
])