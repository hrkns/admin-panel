@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4">
		<br>
		<p>{!! term("str_name") !!}</p>
		<input class = "form-control" name = "name">
	</div>
	<div class = "col-sm-4">
		<br>
		<p>{!! term("str_description") !!}</p>
		<textarea class = "form-control" name = "description"></textarea>
	</div>
	<div class = "col-sm-4">
		<br>
		<p>{!! term("str_directory") !!}</p>
		<select class = "form-control" id = "edit_file_select_parent" name = "parent">
		</select>
	</div>
</div>

@include("app.modals.include.modal-footer")