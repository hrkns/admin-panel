@include ("app.modals.include.modal-header")

<p>
	{!! term("str_remove_dir_text_confirmation") !!}
</p>
<br>
<input type = "checkbox" id = "remove_dir_move_content_to" style = "width:25px;height:25px;">&nbsp;
{!! term("str_remove_dir_move_content_to") !!}<br><br>
<select class = "form-control" style = "display:none;width:35%;" id = "move_content_to">
</select>

@include ("app.modals.include.modal-footer")