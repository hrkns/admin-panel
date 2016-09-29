<?php
	use App\Models\UserPreferences;
	include FILE_ADMIN_PANEL_SETTINGS;
	$sounds = GetForUse("PanelAdminSound");
	$userPreferences = UserPreferences::where("id_user", "=", Request::session()->get("iduser"))->get()[0];
	$userLogo = $userPreferences->logo;
	$usertab_icon = $userPreferences->tab_icon;
	$languages = GetForUse("MasterLanguage");
?>

<input type = "hidden" id = "iduser" value = "{!! Request::session()->get("iduser") !!}">

<div style = "padding:2%;" id = "the_container">
	<br>
	<div class = "pull-left">
		<h1 style = "padding:0px;margin:0px;">{!! term("str_global_settings") !!}</h1>
	</div>
	<br><br>
	<div class = "container" style = "width:100%;">
		<div class = "row">
			<div class = "col-sm-4" align = "center">
				{{--
					logo edition
				--}}
					<hr style = "border:solid 1px">
					<h3>{!! term("str_general_logo") !!}</h3>
					<img src = "{!! $globalPreferences["logo"] !!}" id = "src_global_logo" style = "width:35%;"><br><br>
					<input type = "file" id = "global_logo" style = "display:none;">
					<button class = "btn btn-primary" id = "change_global_logo">{!! term("str_change") !!}</button>
					<button class = "btn btn-warning" style = "display:none;" id = "undo_change_global_logo">{!! term("str_undo") !!}</button>
					<button class = "btn btn-success" style = "display:none;" id = "save_global_logo">{!! term("str_save") !!}</button>

				{{-- 
					let register a user or not
				--}}
					<hr style = "border:solid 1px">
					<input type = "checkbox" id = "let_register_user" {!! intval($globalPreferences["let_register_user"])==1?"checked":"" !!}>&nbsp;<strong>{!! term("str_let_user_registering") !!}</strong>

				{{-- 
					set default language of the system
				--}}
					<hr style = "border:solid 1px">
					<p><strong>{!! term("str_default_language_system") !!}</strong></p>
					<select class = "form-control" id = "select_default_language_system">
						@foreach ($languages as $value)
							<option value = "{!! $value["code"] !!}" {!! $value["code"] == $globalPreferences["default_language_system"]?"selected":"" !!}>
								{!! translate($value["name"]) !!}
							</option>
						@endforeach
					</select>
			</div>
			<div class = "col-sm-4">
				{{-- 
					choose the content of sign-up email
				--}}
					<hr style = "border:solid 1px">
					<p><strong>{!! term("str_signup_email_content") !!}</strong></p>
					<input type = "radio" value = "link" name = "content_registration_email_radio" {!! $globalPreferences["content_registration_email"] == "link"?"checked":"" !!}>&nbsp;
					<span>{!! term("str_link_autologin") !!}</span><br>
					<input type = "radio" value = "admin" name = "content_registration_email_radio"{!! $globalPreferences["content_registration_email"] == "admin"?"checked":"" !!}>&nbsp;
					<span>{!! term("str_manual_confirmation_by_admin") !!}</span><br>

				{{-- 
					choice account recovering mechanism link vs data
				--}}
					<hr style = "border:solid 1px">
					<p><strong>{!! term("str_account_recovering_mechanism") !!}</strong></p>
					<input type = "radio" value = "link" name = "account_recovering_mechanism_radio" {!! $globalPreferences["account_recovering_mechanism"] == "link"?"checked":"" !!}>&nbsp;
					<span>{!! term("str_account_recovering_mechanism_by_link") !!}</span><br>
					<input type = "radio" value = "data" name = "account_recovering_mechanism_radio"{!! $globalPreferences["account_recovering_mechanism"] == "data"?"checked":"" !!}>&nbsp;
					<span>{!! term("str_account_recovering_mechanism_by_data") !!}</span><br><br>

				{{-- 
					choice account recovering mechanism automatic vs manual (by admin)
				--}}
					<input type = "radio" value = "0" name = "account_recovering_mechanism_radio_automatic"{!! $globalPreferences["account_recovering_mechanism_automatic"] == "0"?"checked":"" !!}>&nbsp;
					<span>{!! term("str_account_recovering_mechanism_admin") !!}</span><br>
					<input type = "radio" value = "1" name = "account_recovering_mechanism_radio_automatic"{!! $globalPreferences["account_recovering_mechanism_automatic"] == "1"?"checked":"" !!}>&nbsp;
					<span>{!! term("str_account_recovering_mechanism_automatic") !!}</span><br>
			</div>
			<div class = "col-sm-4">
				{{-- 
					set or unset use of session duration
				--}}
					<hr style = "border:solid 1px">
					<input type = "checkbox" id = "apply_general_session_duration" {!! $globalPreferences["apply_general_session_duration"] == '1'?"checked":"" !!}>&nbsp;{!! term("str_apply_general_session_duration") !!}
					<div id = "general_session_duration_settings" style = "display:{!! $globalPreferences["apply_general_session_duration"] == '1'?"":"none" !!};padding:2% 0% 0% 5%;">
						<input class = "form-control" id = "general_session_duration_amount_val" value = "{!! $globalPreferences["general_session_duration_amount_val"] !!}" style = "width:45%;display:inline;">
						<select class = "form-control" style = "width:45%;display:inline;" id = "general_session_duration_amount_type">
							<option value = "seconds" {!! $globalPreferences["general_session_duration_amount_type"] == "seconds"?"selected":"" !!}>	{!! term("str_seconds", true) !!}</option>

							<option value = "minutes" {!! $globalPreferences["general_session_duration_amount_type"] == "minutes"?"selected":"" !!}>	{!! term("str_minutes", true) !!}</option>

							<option value = "hours" {!! $globalPreferences["general_session_duration_amount_type"] == "hours"?"selected":"" !!}>	{!! term("str_hours", true) !!}</option>

							<option value = "days" {!! $globalPreferences["general_session_duration_amount_type"] == "days"?"selected":"" !!}>		{!! term("str_days", true) !!}</option>

							<option value = "weeks" {!! $globalPreferences["general_session_duration_amount_type"] == "weeks"?"selected":"" !!}>	{!! term("str_weeks", true) !!}</option>
						</select>
					</div>
					<br>
					<button class = "btn btn-primary" id = "save_changes_general_session_duration" style = "display:none;">{!! term("str_save") !!}</button>

				{{-- 
					default configuration for inactivity timelimit
				--}}
					<hr style = "border:solid 1px">
					<p><strong>{!! term("str_general_configuration_inactivity_time_limit") !!}</strong></p>
					<input type = "radio" name = "general_config_inactivity_time_limit" value = "no"			{!! $globalPreferences["apply_default_config_inactivity_time_limit"] == 'no'?"checked":"" !!}>&nbsp;{!! term("str_no_apply") !!}<br>
					<input type = "radio" name = "general_config_inactivity_time_limit" value = "lock_screen" 	{!! $globalPreferences["apply_default_config_inactivity_time_limit"] == 'lock_screen'?"checked":"" !!}>&nbsp;{!! term("str_lock_screen") !!}<br>
					<input type = "radio" name = "general_config_inactivity_time_limit" value = "logout"  		{!! $globalPreferences["apply_default_config_inactivity_time_limit"] == 'logout'?"checked":"" !!}>&nbsp;{!! term("str_logout") !!}<br>
					<div id = "default_config_inactivity_time_limit" style = "display:{!! $globalPreferences["apply_default_config_inactivity_time_limit"] == "no"?"none":"" !!};">
						<input class = "form-control" id = "default_config_inactivity_time_limit_amount_val" value = "{!! $globalPreferences["default_config_inactivity_time_limit_amount_val"] !!}" style = "width:45%;display:inline;">
						<select class = "form-control" style = "width:45%;display:inline;" id = "default_config_inactivity_time_limit_amount_type">
							<option value = "seconds" {!! $globalPreferences["default_config_inactivity_time_limit_amount_type"] == "seconds"?"selected":"" !!}>	{!! term("str_seconds", true) !!}</option>

							<option value = "minutes" {!! $globalPreferences["default_config_inactivity_time_limit_amount_type"] == "minutes"?"selected":"" !!}>	{!! term("str_minutes", true) !!}</option>

							<option value = "hours" {!! $globalPreferences["default_config_inactivity_time_limit_amount_type"] == "hours"?"selected":"" !!}>	{!! term("str_hours", true) !!}</option>

							<option value = "days" {!! $globalPreferences["default_config_inactivity_time_limit_amount_type"] == "days"?"selected":"" !!}>		{!! term("str_days", true) !!}</option>

							<option value = "weeks" {!! $globalPreferences["default_config_inactivity_time_limit_amount_type"] == "weeks"?"selected":"" !!}>	{!! term("str_weeks", true) !!}</option>
						</select>
					</div>
					<br>
					<button class = "btn btn-primary" id = "save_changes_inactivity_time_limit" style = "display:none;">{!! term("str_save") !!}</button>
			</div>
		</div>
		<div class = "row">
			<div class = "col-sm-4">
				{{--
					set name of the system
				--}}
					<hr style = "border:solid 1px">
					<form id = "form_name_of_system">
						<p><strong>{!! term("str_general_name_of_system") !!}</strong></p>
						<input class = "form-control" id = "global_name_of_system" value = "{!! $globalPreferences["name_of_system"] !!}">
						<input type = "submit" class = "btn btn-primary" style = "display:none;" id = "submit_name_of_system">
					</form>

				{{--
					set default format to show items
				--}}
					<hr style = "border:solid 1px">
					<p><strong>{!! term("str_default_format_to_show_items") !!}</strong></p>
					<input type = "radio" value = "progressive" name = "format_show_items" {!! $globalPreferences["format_show_items"] == "progressive"?"checked":"" !!}>&nbsp;<span>{!! term("str_progressive_load") !!}</span>
					<br>
					<input type = "radio" value = "pagination" name = "format_show_items" {!! $globalPreferences["format_show_items"] == "pagination"?"checked":"" !!}>&nbsp;<span>{!! term("str_pagination") !!}</span>

				{{-- 
					set default format to edit items
				--}}
					<hr style = "border:solid 1px">
					<p><strong>{!! term("str_default_format_to_edit_items") !!}</strong></p>
					<input type = "radio" value = "inline" name = "format_edit_items" {!! $globalPreferences["format_edit_items"] == "inline"?"checked":"" !!}>&nbsp;<span>{!! term("str_inline") !!}</span>
					<br>
					<input type = "radio" value = "modal" name = "format_edit_items" {!! $globalPreferences["format_edit_items"] == "modal"?"checked":"" !!}>&nbsp;<span>{!! term("str_modal") !!}</span>
			</div>

			<div class = "col-sm-4">
				{{-- 
					set default tab icon
				--}}
					<hr style = "border:solid 1px">
					<h3>{!! term("str_general_tab_icon") !!}</h3>
					<img src = "{!! $globalPreferences["tab_icon"] !!}" id = "src_global_tab_icon" style = "width:35%;">
					<input type = "file" id = "global_tab_icon" style = "display:none;">
					<button class = "btn btn-primary" id = "change_global_tab_icon">{!! term("str_change") !!}</button>
					<button class = "btn btn-warning" style = "display:none;" id = "undo_change_global_tab_icon">{!! term("str_undo") !!}</button>
					<button class = "btn btn-success" style = "display:none;" id = "save_global_tab_icon">{!! term("str_save") !!}</button>
					<br><br>
			</div>

			<div class = "col-sm-4" align = "left">
				{{-- 
					default chat alert sound
				--}}
					<hr style = "border:solid 1px">
					<p>
						<strong>
							{!! term("str_general_chat_alert_sound") !!}
						</strong>
					</p>
					<select class = "form-control" id = "chat_alert_sound_general">
						@foreach ($sounds as $value)
							<option data-file = "{!! $value->file !!}" 
									value = "{!! $value->id !!}"
									{!! $value->id == $globalPreferences["chat_alert_sound"]?"selected":"" !!}>
									{!! translate($value->name) !!}
							</option>
						@endforeach
					</select><br>
					<button class = "btn" id = "play_general_chat_alert_sound">{!! term("str_play") !!}</button><br><br>
					<button class = "btn btn-primary" id = "save_alerts_chat" style = "display:none;">{!! term("str_save") !!}</button>
			</div>

			<div class = "col-sm-12">
				{{-- 
					edit terms of use and privacy policy
				--}}
					<hr style = "border:solid 1px">
					<h4>{!! term("str_terms_of_use_and_privacy_policy") !!}</h4>
					<div id = "terms">{!! $globalPreferences["terms_of_use_and_privacy_policy"][__LNG__] !!}</div><br>
					<button id = "save_terms" class = "btn btn-primary">
						{!! term("str_save") !!}
					</button>
					<a target = "_blank" href = "{!! WEB_ROOT."/terms-of-use-and-privacy-policy" !!}"><button class = "btn btn-warning">{!! term("str_see") !!}</button></a>
			</div>
		</div>
	</div>
</div>

@include("app.sections.include.section-resources")