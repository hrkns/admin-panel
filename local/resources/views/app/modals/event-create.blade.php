@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-6 form-group">
		<br>
		<p>{!! term("str_name") !!}</p>
		<input type = "text" name = "name" class = "form-control">
	</div>
	<div class = "col-sm-6 form-group">
		<br>
		<p>{!! term("str_description") !!}</p>
		<textarea name = "description" class = "form-control"></textarea>
	</div>
	<div class = "col-sm-12 form-group" data-controls="statuses">
		<br>
		@include ("app.templates.statuses-select")
	</div>
</div>

@include("app.modals.include.modal-footer")