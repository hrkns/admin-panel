<?php
	include FILE_ADMIN_PANEL_SETTINGS;
	use App\Models\UserPreferences;
	$sounds = GetForUse("PanelAdminSound");
	$userPreferences = UserPreferences::where("id_user", "=", Request::session()->get("iduser"))->get()[0];
	$userLogo = $userPreferences->logo;
	$usertab_icon = $userPreferences->tab_icon;
?>

<input type = "hidden" id = "iduser" value = "{!! Request::session()->get("iduser") !!}">

<div style = "padding:2%;" id = "the_container">
	<br>
	<div class = "pull-left">
		<h1 style = "padding:0px;margin:0px;">{!! term("str_preferences") !!}</h1>
	</div>
	<br><br>
	<div class = "container" style = "width:100%;">
		<div class = "row">
			<div class = "col-sm-4" align = "center">
				{{-- 
					custom logo
				--}}
					<hr style = "border:solid 1px">
					<h3>{!! term("str_customized_logo") !!}</h3>
					<img src = "{!! AP_Asset("assets/images/logos/".$userLogo) !!}" id = "src_personal_logo" style = "width:35%;"><br><br>
					<input type = "file" id = "personal_logo" style = "display:none;">
					<button class = "btn btn-primary" id = "change_personal_logo">{!! term("str_change") !!}</button>
					<button class = "btn btn-warning" style = "display:none;" id = "undo_change_personal_logo">{!! term("str_undo") !!}</button>
					<button class = "btn btn-success" style = "display:none;" id = "save_personal_logo">{!! term("str_save") !!}</button>

				{{-- 
					custom tab icon
				--}}
					<hr style = "border:solid 1px">
					<h3>{!! term("str_customized_tab_icon") !!}</h3>
					<img src = "{!! AP_Asset("assets/images/tab_icons/".$usertab_icon) !!}" id = "src_personal_tab_icon" style = "width:35%;"><br><br>
					<input type = "file" id = "personal_tab_icon" style = "display:none;">
					<button class = "btn btn-primary" id = "change_personal_tab_icon">{!! term("str_change") !!}</button>
					<button class = "btn btn-warning" style = "display:none;" id = "undo_change_personal_tab_icon">{!! term("str_undo") !!}</button>
					<button class = "btn btn-success" style = "display:none;" id = "save_personal_tab_icon">{!! term("str_save") !!}</button>
					<br><br>
			</div>
			<div class = "col-sm-4">
				{{-- 
					amount of items to bring in a request
				--}}
					<hr style = "border:solid 1px">
					<p><strong>{!! term("str_amount_items_to_bring_by_progressive_request") !!}</strong></p>
					<form id = "form_amount_items_per_request">
						<input class = "form-control" id = "amount_items_per_request" value = "{!! $userPreferences["amount_items_per_request"] !!}"><br>
						<input type = "submit" class = "btn btn-primary" value = "{!! term("str_save") !!}" style = "display:none;" id = "amount_items_per_request_submit">
					</form>

				{{-- 
					custom alert chat sound
				--}}
					<hr style = "border:solid 1px">
					<p>
						<strong>
							{!! term("str_customized_chat_alert_sound") !!}
						</strong>
					</p>
					<select class = "form-control" id = "chat_alert_sound_customized">
						@foreach ($sounds as $value)
							<option data-file = "{!! $value->file !!}" 
									value = "{!! $value->id !!}"
									{!! $value->id == $userPreferences["chat_alert_sound"]?"selected":"" !!}>
									{!! translate($value->name) !!}
							</option>
						@endforeach
					</select><br>
					<button class = "btn" id = "play_general_customized_alert_sound">{!! term("str_play") !!}</button>
					
					<button class = "btn btn-primary" id = "save_alerts_chat" style = "display:none;">{!! term("str_save") !!}</button>
			</div>
			<div class = "col-sm-4">
				{{-- 
					use or not session timelimit
				--}}
					<hr style = "border:solid 1px">
					<input type = "checkbox" id = "use_session_duration" {!! $userPreferences["use_session_duration"] == '1'?"checked":"" !!}>&nbsp;{!! term("str_use_session_duration") !!}
					<div id = "session_duration_custom_settings" style = "display:{!! $userPreferences["use_session_duration"] == '1'?"":"none" !!};padding:2% 0% 0% 5%;">
						<input class = "form-control" id = "custom_session_duration_amount_val" value = "{!! $userPreferences["session_duration_amount_val"] !!}" style = "width:45%;display:inline;">
						<select class = "form-control" style = "width:45%;display:inline;" id = "custom_session_duration_amount_type">
							<option value = "seconds" {!! $userPreferences["session_duration_amount_type"] == "seconds"?"selected":"" !!}>	{!! term("str_seconds", true) !!}</option>

							<option value = "minutes" {!! $userPreferences["session_duration_amount_type"] == "minutes"?"selected":"" !!}>	{!! term("str_minutes", true) !!}</option>

							<option value = "hours" {!! $userPreferences["session_duration_amount_type"] == "hours"?"selected":"" !!}>	{!! term("str_hours", true) !!}</option>

							<option value = "days" {!! $userPreferences["session_duration_amount_type"] == "days"?"selected":"" !!}>		{!! term("str_days", true) !!}</option>

							<option value = "weeks" {!! $userPreferences["session_duration_amount_type"] == "weeks"?"selected":"" !!}>	{!! term("str_weeks", true) !!}</option>
						</select>
					</div>
					<br>
					<button class = "btn btn-primary" id = "save_changes_custom_session_duration" style = "display:none;">{!! term("str_save") !!}</button>

				{{--
					custom configuration inactivity time limit
				--}}
					<hr style = "border:solid 1px">
					<p><strong>{!! term("str_custom_configuration_inactivity_time_limit") !!}</strong></p>
					<input type = "radio" name = "custom_config_inactivity_time_limit" value = "no"			{!! $userPreferences["use_inactivity_time_limit_as"] == 'no'?"checked":"" !!}>&nbsp;{!! term("str_no_apply") !!}<br>
					<input type = "radio" name = "custom_config_inactivity_time_limit" value = "lock_screen" 	{!! $userPreferences["use_inactivity_time_limit_as"] == 'lock_screen'?"checked":"" !!}>&nbsp;{!! term("str_lock_screen") !!}<br>
					<input type = "radio" name = "custom_config_inactivity_time_limit" value = "logout"  		{!! $userPreferences["use_inactivity_time_limit_as"] == 'logout'?"checked":"" !!}>&nbsp;{!! term("str_logout") !!}<br>
					<div id = "custom_config_inactivity_time_limit" style = "display:{!! $userPreferences["use_inactivity_time_limit_as"] == "no"?"none":"" !!};">
						<input class = "form-control" id = "custom_config_inactivity_time_limit_amount_val" value = "{!! $userPreferences["inactivity_time_limit_amount_val"] !!}" style = "width:45%;display:inline;">
						<select class = "form-control" style = "width:45%;display:inline;" id = "custom_config_inactivity_time_limit_amount_type">
							<option value = "seconds" {!! $userPreferences["inactivity_time_limit_amount_type"] == "seconds"?"selected":"" !!}>	{!! term("str_seconds", true) !!}</option>

							<option value = "minutes" {!! $userPreferences["inactivity_time_limit_amount_type"] == "minutes"?"selected":"" !!}>	{!! term("str_minutes", true) !!}</option>

							<option value = "hours" {!! $userPreferences["inactivity_time_limit_amount_type"] == "hours"?"selected":"" !!}>	{!! term("str_hours", true) !!}</option>

							<option value = "days" {!! $userPreferences["inactivity_time_limit_amount_type"] == "days"?"selected":"" !!}>		{!! term("str_days", true) !!}</option>

							<option value = "weeks" {!! $userPreferences["inactivity_time_limit_amount_type"] == "weeks"?"selected":"" !!}>	{!! term("str_weeks", true) !!}</option>
						</select>
					</div>
					<br>
					<button class = "btn btn-primary" id = "save_changes_custom_inactivity_time_limit" style = "display:none;">{!! term("str_save") !!}</button>

				{{-- 
					custom format to show items
				--}}
					<hr style = "border:solid 1px">
					<p><strong>{!! term("str_format_to_show_items") !!}</strong></p>
					<input type = "radio" value = "progressive" name = "format_show_items" {!! $userPreferences["format_show_items"] == "progressive"?"checked":"" !!}>&nbsp;<span>{!! term("str_progressive_load") !!}</span>
					<br>
					<input type = "radio" value = "pagination" name = "format_show_items" {!! $userPreferences["format_show_items"] == "pagination"?"checked":"" !!}>&nbsp;<span>{!! term("str_pagination") !!}</span>

				{{-- 
					custom format to edit items
				--}}
					<hr style = "border:solid 1px">
					<p><strong>{!! term("str_format_to_edit_items") !!}</strong></p>
					<input type = "radio" value = "inline" name = "format_edit_items" {!! $userPreferences["format_edit_items"] == "inline"?"checked":"" !!}>&nbsp;<span>{!! term("str_inline") !!}</span>
					<br>
					<input type = "radio" value = "modal" name = "format_edit_items" {!! $userPreferences["format_edit_items"] == "modal"?"checked":"" !!}>&nbsp;<span>{!! term("str_modal") !!}</span>
			</div>
		</div>
	</div>
</div>

@include("app.sections.include.section-resources")