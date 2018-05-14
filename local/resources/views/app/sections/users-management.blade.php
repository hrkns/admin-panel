@include("app.templates.base-list", [
	"model" => "user",
	"fields" => [[
			"code" => "id",
			"term" => "str_id",
		],[
			"code" => "statuses",
			"term" => "str_statuses",
		],[
			"code" => "profile_img",
			"term" => "str_profile_image",
		],[
			"code" => "fullname",
			"term" => "str_fullname",
		],[
			"code" => "nick",
			"term" => "str_nick",
		],[
			"code" => "email",
			"term" => "str_email",
		],[
			"code" => "options",
			"term" => "str_options",
		]
	]
])