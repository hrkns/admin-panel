@include("app.templates.base-list", [
	"model" => "administrative-division",
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
			"code" => "parents",
			"term" => "str_parents",
		],[
			"code" => "statuses",
			"term" => "str_statuses",
		],[
			"code" => "options",
			"term" => "str_options",
		]
	]
])