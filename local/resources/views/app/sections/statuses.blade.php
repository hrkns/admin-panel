@include("app.templates.base-list", [
	"model" => "status",
	"fields" => [[
			"code" => "id",
			"term" => "str_id",
		],[
			"code" => "language",
			"term" => "str_language",
		],[
			"code" => "code",
			"term" => "str_code",
		],[
			"code" => "name",
			"term" => "str_name",
		],[
			"code" => "description",
			"term" => "str_description",
		],[
			"code" => "statuses",
			"term" => "str_statuses",
		],[
			"code" => "statuses_config",
			"term" => "str_statuses_config",
		],[
			"code" => "options",
			"term" => "str_options",
		]
	]
])