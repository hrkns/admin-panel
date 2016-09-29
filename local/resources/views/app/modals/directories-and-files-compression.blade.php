@include ("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4">
		<br>
		<p>
			{!! term("str_compressed_file_name") !!}
		</p>
		<input type = "text" name = "name" class = "form-control">
	</div>
	<div class = "col-sm-4">
		<br>
		<p>
			{!! term("str_compressed_file_description") !!}
		</p>
		<textarea class = "form-control" name = "description"></textarea>
	</div>
	<div class = "col-sm-4">
		<br>
		<input type = "checkbox" id = "save_compressed_file_in" style = "width:25px;height:25px;">&nbsp;
		{!! term("str_save_compressed_file_in") !!}<br><br>
		<select class = "form-control" style = "display:none;width:35%;" id = "save_compressed_file_in_folder">
		</select>
	</div>
</div>

@include ("app.modals.include.modal-footer", [
	"buttons" => [
		"submit" => [
			"term" => "str_compress"
		]
	]
])