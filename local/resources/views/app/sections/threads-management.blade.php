<?php
	use App\Models\UserPreferences;
	use App\Models\PanelAdminSound;

	$users = GetForUse("User");
	$idaudio = UserPreferences::where("id_user", "=", Request::session()->get("iduser"))->get()[0]->chat_alert_sound;
	$file_alert = PanelAdminSound::where("id", "=", $idaudio)->get();

	if(count($file_alert)){
		$file_alert = $file_alert[0]->file;
	}else{
		$file_alert = "";
	}
?>

<div style = "padding:2%;" id = "the_container">
	<select id = "db_users" style = "display:none;">
		@foreach ($users as $key => $value) 
			@if($value->id != Request::session()->get("iduser")){
				<option value = "{!! $value->id !!}">{!! $value->fullname !!}</option>
			@endif
		@endforeach
	</select>
	<input type = "hidden" id = "iduser"  value = "{!! Request::session()->get("iduser") !!}">
	<input type = "hidden" id = "file_alert_sound" value = "{!! $file_alert !!}">
	@include("app.sections.include.section-title")
	@include("app.sections.include.control-create", [
		"model" => "thread"
	])
	@include("app.sections.include.items-list", [
		"fields" => [[
				"code" => "id",
				"term" => "str_id",
			],[
				"code" => "title",
				"term" => "str_title",
			],[
				"code" => "description",
				"term" => "str_description",
			],[
				"code" => "creator",
				"term" => "str_creator",
			],[
				"code" => "options",
				"term" => "str_options",
			]
		]
	])
</div>

<div id = "thread_interface" style = "display:none;padding:2%;">
	<a id = "go_back_threads" href = "javascript:;">
		<strong>
			{!! term("str_go_back") !!}
		</strong>
	</a>
	<h1 id = "main_static_title_thread"></h1>
	<a href = "javascript:;" onclick = "$('#thread_controls').toggle(App.TIME_FOR_SHOW);"><strong>{!! term("str_configs") !!}</strong></a>
	<br><br>
	<div id = "thread_controls">
		<div class = "row">
			<div class = "col-sm-12" id = "thread_interface_title">
			</div>
			<div class = "col-sm-3" id = "thread_interface_description">
			</div>
			<div class = "col-sm-3" id = "thread_interface_admins">
			</div>
			<div class = "col-sm-3" id = "thread_interface_speakers">
			</div>
			<div class = "col-sm-3" id = "thread_interface_join_requests">
			</div>
			<div class = "col-sm-3" id = "thread_interface_controls_others">
			</div>
		</div>
	</div>
	<br>
	<div id = "thread_messages" class = "ap-messages-container">
		<button class = "btn btn-block btn-primary more-messages" id = "load_more_messages_of_thread">
			{!! term("str_see_more_messages") !!}
		</button>
		<div id = "loading_messages" align = "center" style = "display:none;">
			<img src="{!! LOADING_ICON !!}" class = "loading-more-messages">
		</div>
		<br>
		<div class = "row ap-messages-list" id = "list_messages">
		</div>
	</div>
	<div id = "message_controls">
		<div class = "row">
			<div class = "col-sm-12">
				<textarea class = "form-control" id = "txt_message"></textarea>
			</div>
			<div class = "col-sm-12">
				<input type = "checkbox" checked id = "press_enter_to_send_message">&nbsp;{!! term("str_press_enter_send_message") !!}<br>
				<button class = "btn btn-primary pull-right" id = "send_message">{!! term("str_send") !!}</button>
			</div>
		</div>
	</div>
</div>

@include("app.sections.include.section-resources")