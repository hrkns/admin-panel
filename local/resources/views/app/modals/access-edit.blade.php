@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-6 form-group">
		<p>{!! term("str_route") !!}</p>
		<input type = "text" name = "route" class = "form-control">
	</div>
	<div class = "col-sm-6 form-group">
		<p>{!! term("str_text") !!}</p>
		<input type = "text" name = "name" class = "form-control">
	</div>
	<div class = "col-sm-12">
		<p>{!! term("str_select_icon") !!}</p>
	</div>
	<div class = "col-sm-12 row" id = "access_update_list_icons" style = "max-height:300px;overflow:scroll;">
	</div>
</div>

@include("app.modals.include.modal-footer")