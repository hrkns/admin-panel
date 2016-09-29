@include("app.templates.base-list", [
	"model" => "organization",
	"fields" => [[
			"code" => "id",
			"term" => "str_id",
		],[
			"code" => "statuses",
			"term" => "str_statuses",
		],[
			"code" => "logo",
			"term" => "str_logo",
		],[
			"code" => "name",
			"term" => "str_name_organization",
		],[
			"code" => "options",
			"term" => "str_options",
		]
	]
])