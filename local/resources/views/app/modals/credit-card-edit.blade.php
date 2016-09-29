@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4 form-group">
		<p>{!! term("str_code") !!}</p>
		<input name = "code" class = "form-control">
	</div>
	<div class = "col-sm-4 form-group">
		<p>{!! term("str_name") !!}</p>
		<input type = "text" name = "name" class = "form-control">
	</div>
	<div class = "col-sm-4 form-group">
		<p>{!! term("str_description") !!}</p>
		<textarea name = "description" class = "form-control"></textarea>
	</div>
	<div class = "col-sm-12 form-group" data-controls="statuses">
		@include ("app.templates.statuses-select")
	</div>
</div>

@include("app.modals.include.modal-footer")