@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-3 form-group">
		<p>{!! term("str_code") !!}</p>
		<input type = "text" name = "code" class = "form-control" value = "">
	</div>
	<div class = "col-sm-3 form-group">
		<p>{!! term("str_name") !!}</p>
		<input type = "text" name = "name" class = "form-control">
	</div>
	<div class = "col-sm-3 form-group">
		<p>{!! term("str_description") !!}</p>
		<textarea name = "description" class = "form-control"></textarea>
	</div>
	<div class = "col-sm-3 form-group">
		<div data-controls = "statuses">
			@include ("app.templates.statuses-select")
			<br><br>
		</div>

		<input type = "checkbox" name = "show_default">&nbsp;{!! term("str_text_checkbox_1") !!}
		<br>
		<input type = "checkbox" name = "show_item" checked>&nbsp;{!! term("str_text_checkbox_2") !!}
		<br>
		<input type = "checkbox" name = "for_delete">&nbsp;{!! term("str_text_checkbox_3") !!}
	</div>
</div>

@include("app.modals.include.modal-footer")