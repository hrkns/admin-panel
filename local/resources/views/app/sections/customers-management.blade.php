@include("app.templates.base-list", [
	"model" => "customer",
	"fields" => [[
			"code" => "id",
			"term" => "str_id",
		],[
			"code" => "statuses",
			"term" => "str_statuses",
		],[
			"code" => "image",
			"term" => "str_image",
		],[
			"code" => "name",
			"term" => "str_name",
		],[
			"code" => "options",
			"term" => "str_options",
		]
	]
])