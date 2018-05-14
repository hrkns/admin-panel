@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4 form-group">
		<p>{!! term("str_title") !!}</p>
		<input type = "text" name = "title" class = "form-control">
	</div>
	<div class = "col-sm-4 form-group">
		<p>{!! term("str_description") !!}</p>
		<textarea name = "description" class = "form-control"></textarea>
	</div>
	<div class = "col-sm-4 form-group">
		<p>{!! term("str_privacy") !!}</p>
		<input type = "radio" name = "privacy" id = "thread_privacy_0">&nbsp;{!! term("str_secret") !!}<br>
		<input type = "radio" name = "privacy" id = "thread_privacy_1">&nbsp;{!! term("str_protected") !!}<br>
		<input type = "radio" name = "privacy" id = "thread_privacy_2" checked>&nbsp;{!! term("str_public") !!}<br>
		<div id = "thread_create_participants" style = "display:none;">
			<br>
			<p>{!! term("str_participants") !!}</p>
			<select multiple name = "users">
				@foreach ($users as $value)
					@if($value->id != Request::session()->get("iduser"))
						<option value = "{!! $value["id"] !!}">{!! $value["fullname"] !!}</option>
					@endif
				@endforeach
			</select>
		</div>
	</div>
	<div class = "col-sm-12 form-group" align = "left">
		<p>{!! term("str_admins") !!}&nbsp;<a id = "add_admin" href = "javascript:;"><i class = "fa fa-plus"></i></a></p>
		<div id = "thread_create_list_admins" class = "container" style = "width:100%;">
		</div>
	</div>
</div>

@include("app.modals.include.modal-footer")