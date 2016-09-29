@include("app.templates.base-list", [
	"model" => "event",
	"fields" => [[
			"code" => "id",
			"term" => "str_id",
		],[
			"code" => "language",
			"term" => "str_language",
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
			"code" => "options",
			"term" => "str_options",
		]
	]
])