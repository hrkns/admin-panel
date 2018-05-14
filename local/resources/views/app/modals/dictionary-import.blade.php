@include ("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-12" align = "left">
		<h4>
			{!! term("str_import_dictionary_description") !!}
		</h4>
	</div>
	<div class = "col-sm-12">
		<input type="file" name="file" class = "form-control">
	</div>
</div>

@include ("app.modals.include.modal-footer", [
	"buttons" => [
		"submit" => [
			"term" => "str_import"
		]
	]
])