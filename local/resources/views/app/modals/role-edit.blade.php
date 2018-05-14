@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4">
		<br>
		<p>{!! term("str_code") !!}</p>
		<input name = "code" class = "form-control">
		<br>
	</div>
	<div class = "col-sm-4">
		<br>
		<p>{!! term("str_name") !!}</p>
		<input name = "name" class = "form-control">
		<br>
	</div>
	<div class = "col-sm-4">
		<br>
		<p>{!! term("str_description") !!}</p>
		<textarea name = "description" class = "form-control"></textarea>
		<br>
	</div>
	<div class = "col-sm-12" data-controls="statuses">
		<br>
		@include ("app.templates.statuses-select")
		<br>
	</div>
</div>

@include("app.modals.include.modal-footer")