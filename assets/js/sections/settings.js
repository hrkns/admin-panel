//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};

	var iduser = $("#iduser").val();
	var usingWhichLogo = $("#radio_button_global_logo").prop("checked")?"1":"0";
	var originalSrcGlobalLogo = $("#src_global_logo").attr("src");
	var originalSrcpersonalLogo = $("#src_personal_logo").attr("src");
	var input_amount_items_per_request = document.getElementById("amount_items_per_request");
	var lastVal_amount_items_per_request = input_amount_items_per_request.value;
	var condFormTabTitle = false;
	var usingWhichTabIcon = $("#radio_button_global_tab_icon").prop("checked")?"1":"0";
	var originalSrcGlobalTabIcon = $("#src_global_tab_icon").attr("src");
	var originalSrcpersonalTabIcon = $("#src_personal_tab_icon").attr("src");
	var lastVal_statusDisplay = {
		hide_over_show : $("#priority_display_hide_over_show").prop("checked")?0:1,
		amount_criterion : $("#priority_display_amount_criterion").prop("checked")?1:0
	};
	var lastConfigAlertChat = {
		general : $("#chat_alert_sound_general").val(),
		customized : $("#chat_alert_sound_customized").val(),
		election : $("input[name='chat_alert_sound_election']:checked").val()
	};
	var ccht1 = false, ccht2 = false, ccht3 = false;
	var custom_session_duration_checkbox = $("#use_session_duration").prop("checked");
	var custom_session_duration_amount_val = $("#custom_session_duration_amount_val").val().trim();
	var custom_session_duration_amount_type = $("#custom_session_duration_amount_type").val().trim();
	var custom_config_inactivity_time_limit_use_as = $("input[name='custom_config_inactivity_time_limit']:checked").val();
	var custom_config_inactivity_time_limit_amount_val = $("#custom_config_inactivity_time_limit_amount_val").val().trim();
	var custom_config_inactivity_time_limit_amount_type = $("#custom_config_inactivity_time_limit_amount_type").val().trim();

	/***********************************************************************************************************/

	document.getElementById("change_personal_logo").onclick = function(){
		$("#personal_logo").trigger("click");
	};

	$("#personal_logo").change(function(){
		if(this.files && this.files[0]){
			$("#undo_change_personal_logo").show(App.TIME_FOR_SHOW);
			$("#save_personal_logo").show(App.TIME_FOR_SHOW);
			var reader = new FileReader();

			reader.onload = function(e){
				$("#src_personal_logo").fadeOut(500, function() {
					$("#src_personal_logo").attr('src', e.target.result);
			    }).fadeIn(500);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});

	$("#undo_change_personal_logo").click(function(){
		$("#src_personal_logo").attr("src", originalSrcpersonalLogo);
		$("#save_personal_logo").hide(App.TIME_FOR_HIDE);
		$(this).hide(App.TIME_FOR_HIDE);
	});

	var saving_personal_logo = false;

	$("#save_personal_logo").click(function(){
		if(saving_personal_logo){
			return;
		}

		saving_personal_logo = true;
		$("#save_personal_logo").attr("disabled", true);

		App.HTTP.update({
			url:App.WEB_ROOT+"/user/"+iduser+"/system-logo",
			data:{
				img:$("#src_personal_logo").attr("src")
			},success:function(d, e, f){
				$("#save_personal_logo").hide(App.TIME_FOR_HIDE);
				$("#undo_change_personal_logo").hide(App.TIME_FOR_HIDE);
				originalSrcpersonalLogo = $("#src_personal_logo").attr("src");
				$("#original_global_logo").attr("src", originalSrcpersonalLogo);
			},after:function(x, y, z){
				saving_personal_logo = false;
				$("#save_personal_logo").attr("disabled", false);
			}
		});
	});

	$("#radio_button_global_logo").click(function(){
		App.HTTP.update({
			url:App.WEB_ROOT+"/user/"+iduser+"/use-global-logo",
			data:{
				val:1
			},success:function(d, e, f){
				$("#original_global_logo").attr("src", $("#src_global_logo").attr("src"));
				usingWhichLogo="1";
			},error:function(x, y, z){
			}
		});
	});

	$("#radio_button_personal_logo").click(function(){
		App.HTTP.update({
			url:App.WEB_ROOT+"/user/"+iduser+"/use-global-logo",
			data:{
				val:0
			},success:function(d, e, f){
				$("#original_global_logo").attr("src", $("#src_personal_logo").attr("src"));
				usingWhichLogo="0";
			},error:function(x, y, z){
			}
		});
	});
	/**********************************************************************************************************************/
	$("#let_register_user").change(function(){
		App.HTTP.update({
			url:App.WEB_ROOT+"/global-preferences/user-register",
			data:{
				val:$("#let_register_user").prop("checked")?1:0
			},success:function(d, e, f){
			},error:function(x, y, z){
			}
		});
	});
	/**********************************************************************************************************************/
	$("#recover_account_by_link").click(function(){
		App.HTTP.update({
			url:App.WEB_ROOT+"/global-preferences/recover-account-mechanism",
			data:{
				val:0
			},success:function(d, e, f){
			},error:function(d, e, f){
			}
		});
	});
	$("#recover_account_by_admin").click(function(){
		App.HTTP.update({
			url:App.WEB_ROOT+"/global-preferences/recover-account-mechanism",
			data:{
				val:1
			},success:function(d, e, f){
			},error:function(d, e, f){
			}
		});
	});
	/**********************************************************************************************************************/
	App.monitorAmountItemsPerRequest("form_amount_items_per_request");
	/**********************************************************************************************************************/

	document.getElementById("change_personal_tab_icon").onclick = function(){
		$("#personal_tab_icon").trigger("click");
	};

	$("#personal_tab_icon").change(function(){
		if(this.files && this.files[0]){
			$("#undo_change_personal_tab_icon").show(App.TIME_FOR_SHOW);
			$("#save_personal_tab_icon").show(App.TIME_FOR_SHOW);
			var reader = new FileReader();

			reader.onload = function(e){
				$("#src_personal_tab_icon").fadeOut(500, function() {
					$("#src_personal_tab_icon").attr('src', e.target.result);
			    }).fadeIn(500);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});

	$("#undo_change_personal_tab_icon").click(function(){
		$("#src_personal_tab_icon").attr("src", originalSrcpersonalTabIcon);
		$("#save_personal_tab_icon").hide(App.TIME_FOR_HIDE);
		$(this).hide(App.TIME_FOR_HIDE);
	});

	var saving_custom_tab_icon = false;
	$("#save_personal_tab_icon").click(function(){
		if(saving_custom_tab_icon){
			return;
		}

		saving_custom_tab_icon = true;
		$("#save_personal_tab_icon").attr("disabled", true);
		App.HTTP.update({
			url:App.WEB_ROOT+"/user/"+iduser+"/system-tab-icon",
			data:{
				img:$("#src_personal_tab_icon").attr("src")
			},success:function(d, e, f){
				$("#save_personal_tab_icon").hide(App.TIME_FOR_HIDE);
				$("#undo_change_personal_tab_icon").hide(App.TIME_FOR_HIDE);
				originalSrcpersonalTabIcon = $("#src_personal_tab_icon").attr("src");
				$("#original_global_tab_icon").attr("href", originalSrcpersonalTabIcon);
			},after:function(x, y, z){
				$("#save_personal_tab_icon").attr("disabled", false);
				saving_custom_tab_icon = false;
			}
		});
	});

	$("#radio_button_personal_tab_icon").click(function(){
		App.HTTP.update({
			url:App.WEB_ROOT+"/user/"+iduser+"/use-global-tab-icon",
			data:{
				val:0
			},success:function(d, e, f){
				$("#original_global_tab_icon").attr("href", $("#src_personal_tab_icon").attr("src"));
				usingWhichTabIcon="0";
			},error:function(x, y, z){
			}
		});
	});
	/**********************************************************************************************************************/

	$("#chat_alert_sound_general, #chat_alert_sound_customized").change(function(){
		var file = $(this).find("option[value='"+$(this).val()+"']").attr("data-file");
		new Audio(App.WEB_ROOT + "/assets/audio/notifications/"+file).play();

		if($(this).attr("id") == "chat_alert_sound_general"){
			if($(this).val() != lastConfigAlertChat.general){
				ccht1 = true;
				$("#save_alerts_chat").show(App.TIME_FOR_SHOW);
			}else{
				ccht1 = false;

				if(!ccht2 && !ccht3){
					$("#save_alerts_chat").hide(App.TIME_FOR_HIDE);
				}
			}
		}else{
			if($(this).val() != lastConfigAlertChat.customized){
				ccht2 = true;
				$("#save_alerts_chat").show(App.TIME_FOR_SHOW);
			}else{
				ccht2 = false;

				if(!ccht1 && !ccht3){
					$("#save_alerts_chat").hide(App.TIME_FOR_HIDE);
				}
			}
		}
	});

	$("#play_general_chat_alert_sound").click(function(){
		var file = $("#chat_alert_sound_general").find("option[value='"+$("#chat_alert_sound_general").val()+"']").attr("data-file");
		new Audio(App.WEB_ROOT + "/assets/audio/notifications/"+file).play();
	});

	$("#play_general_customized_alert_sound").click(function(){
		var file = $("#chat_alert_sound_customized").find("option[value='"+$("#chat_alert_sound_customized").val()+"']").attr("data-file");
		new Audio(App.WEB_ROOT + "/assets/audio/notifications/"+file).play();
	});

	$("input[name='chat_alert_sound_election']").click(function(){
		if($(this).val() != lastConfigAlertChat.election){
			ccht3 = true;
			$("#save_alerts_chat").show(App.TIME_FOR_SHOW);
		}else{
			ccht3 = false;

			if(!ccht1 && !ccht2){
				$("#save_alerts_chat").hide(App.TIME_FOR_HIDE);
			}
		}
	});

	var saving_alerts_chat = false;

	$("#save_alerts_chat").click(function(){
		if(saving_alerts_chat){
			return;
		}

		saving_alerts_chat = true;
		$("#save_alerts_chat").attr("disabled", true);

		App.HTTP.update({
			url : App.WEB_ROOT + "/chat-sound-alert",
			data : {
				general : $("#chat_alert_sound_general").val(),
				customized : $("#chat_alert_sound_customized").val(),
				election : $("input[name='chat_alert_sound_election']:checked").val(),
				type : "custom",
			},success : function(d, e, f){
				lastConfigAlertChat = {
					general : $("#chat_alert_sound_general").val(),
					customized : $("#chat_alert_sound_customized").val(),
					election : $("input[name='chat_alert_sound_election']:checked").val()
				};
				$("#save_alerts_chat").hide(App.TIME_FOR_HIDE);
			},after : function(x, y, z){
				$("#save_alerts_chat").attr("disabled", false);
				saving_alerts_chat = false;
			}
		});
	});
	/**********************************************************************************************************************/
	$("input[name='content_registration_email_radio']").click(function(){
		App.HTTP.update({
			url : App.WEB_ROOT + "/type-content-signup-email",
			data : {
				val : $(this).val()
			},
			success : function(d, e, f){
			},error : function(x, y , z){
			},after : function(){
			}
		});
	});

	$("input[name='account_recovering_mechanism_radio']").click(function(){
		App.HTTP.update({
			url : App.WEB_ROOT + "/account-recovering-mechanism",
			data : {
				val : $(this).val()
			},
			success : function(d, e, f){
			},error : function(x, y , z){
			},after : function(){
			}
		});
	});

	$("input[name='account_recovering_mechanism_radio_automatic']").click(function(){
		App.HTTP.update({
			url : App.WEB_ROOT + "/account-recovering-mechanism-automatic",
			data : {
				val : $(this).val()
			},
			success : function(d, e, f){
			},error : function(x, y , z){
			},after : function(){
			}
		});
	});
	/**********************************************************************************************************************/
	$("#use_session_duration").change(function(){
		if(this.checked){
			$("#session_duration_custom_settings").show(App.TIME_FOR_SHOW);
		}else{
			$("#session_duration_custom_settings").hide(App.TIME_FOR_HIDE);
		}

		checkChangesCustomSessionDuration();
	});
	$("#custom_session_duration_amount_type").change(checkChangesCustomSessionDuration);
	App.inputTextMonitor("custom_session_duration_amount_val", checkChangesCustomSessionDuration);

	function checkChangesCustomSessionDuration(){
		if(	$("#use_session_duration").prop("checked") != custom_session_duration_checkbox ||
			$("#custom_session_duration_amount_val").val().trim() != custom_session_duration_amount_val ||
			$("#custom_session_duration_amount_type").val().trim() != custom_session_duration_amount_type){
			$("#save_changes_custom_session_duration").show(App.TIME_FOR_SHOW);
		}else{
			$("#save_changes_custom_session_duration").hide(App.TIME_FOR_HIDE);
		}
	}

	var saving_changes_custom_session_duration = false;

	$("#save_changes_custom_session_duration").click(function(){
		if(saving_changes_custom_session_duration){
			return;
		}

		saving_changes_custom_session_duration = true;
		$("#save_changes_custom_session_duration").attr("disabled", true);
		App.HTTP.update({
			url : App.WEB_ROOT + "/custom-session-duration",
			data : {
				use : $("#use_session_duration").prop("checked")?"1":"0",
				amount : $("#custom_session_duration_amount_val").val().trim(),
				type : $("#custom_session_duration_amount_type").val().trim()
			},
			success : function(d, e, f){
				custom_session_duration_checkbox 	= $("#use_session_duration").prop("checked");
				custom_session_duration_amount_val	= $("#custom_session_duration_amount_val").val().trim();
				custom_session_duration_amount_type = $("#custom_session_duration_amount_type").val().trim();
				$("#save_changes_custom_session_duration").hide(App.TIME_FOR_HIDE);
			}, error : function(x, y, z){
			}, after : function(){
				$("#save_changes_custom_session_duration").attr("disabled", false);
				saving_changes_custom_session_duration = false;
			}
		});
	});
	/**********************************************************************************************************************/
	$("input[name='custom_config_inactivity_time_limit']").click(function(){
		if(this.value == "no"){
			$("#custom_config_inactivity_time_limit").hide(App.TIME_FOR_HIDE);
		}else{
			$("#custom_config_inactivity_time_limit").show(App.TIME_FOR_SHOW);
		}
		checkChangesCustomInactivityTimelimit();
	});
	$("#custom_config_inactivity_time_limit_amount_type").change(checkChangesCustomInactivityTimelimit);
	App.inputTextMonitor("custom_config_inactivity_time_limit_amount_val", checkChangesCustomInactivityTimelimit);
	function checkChangesCustomInactivityTimelimit(){
		if(	$("input[name='custom_config_inactivity_time_limit']:checked").val() != custom_config_inactivity_time_limit_use_as ||
			$("#custom_config_inactivity_time_limit_amount_val").val().trim() != custom_config_inactivity_time_limit_amount_val ||
			$("#custom_config_inactivity_time_limit_amount_type").val().trim() != custom_config_inactivity_time_limit_amount_type){
			$("#save_changes_custom_inactivity_time_limit").show(App.TIME_FOR_SHOW);
		}else{
			$("#save_changes_custom_inactivity_time_limit").hide(App.TIME_FOR_HIDE);
		}
	}

	var saving_changes_custom_inactivity_time_limit = false;

	$("#save_changes_custom_inactivity_time_limit").click(function(){
		if(saving_changes_custom_inactivity_time_limit){
			return;
		}

		saving_changes_custom_inactivity_time_limit = true;
		$("#save_changes_custom_inactivity_time_limit").attr("disabled", true);

		App.HTTP.update({
			url : App.WEB_ROOT + "/custom-config-inactivity-time-limit",
			data : {
				use_as : $("input[name='custom_config_inactivity_time_limit']:checked").val(),
				amount : $("#custom_config_inactivity_time_limit_amount_val").val().trim(),
				type : $("#custom_config_inactivity_time_limit_amount_type").val().trim()
			},
			success : function(d, e, f){
				custom_config_inactivity_time_limit_use_as 	= $("input[name='custom_config_inactivity_time_limit']:checked").val();
				custom_config_inactivity_time_limit_amount_val	= $("#custom_config_inactivity_time_limit_amount_val").val().trim();
				custom_config_inactivity_time_limit_amount_type = $("#custom_config_inactivity_time_limit_amount_type").val().trim();
				timelimitact = custom_config_inactivity_time_limit_use_as;
				timelimitval = Number(custom_config_inactivity_time_limit_amount_val) * {
					"seconds" : 1,
					"minutes" : 60,
					"hours" : 3600,
					"days" : 86400,
					"weeks" : 604800
				}[custom_config_inactivity_time_limit_amount_type];
				$("#save_changes_custom_inactivity_time_limit").hide(App.TIME_FOR_HIDE);
			}, error : function(x, y, z){
			}, after : function(){
				$("#save_changes_custom_inactivity_time_limit").attr("disabled", false);
				saving_changes_custom_inactivity_time_limit = false;
			}
		});
	});

	this.getItems = function(){
		$("#see_more_items").trigger("click");
	}
	/**********************************************************************************************************************/
	var changing_format_show_items = false;

	$("input[name='format_show_items']").change(function(){
		if(!this.checked || changing_format_show_items){
			return;
		}

		var thing = this;
		changing_format_show_items = true;
		$("input[name='format_show_items']").prop("disabled", true)

		App.changeFormatShowItems(thing.value, function(){
		}, function(){
			thing.checked = false;

			if(thing.value == "progressive"){
				$("input[value='pagination']").prop("checked", true)
			}else{
				$("input[value='progressive']").prop("checked", true)
			}
		}, function(){
			changing_format_show_items = false;
			$("input[name='format_show_items']").prop("disabled", false)
		})
	});
	/**********************************************************************************************************************/
	var changing_format_edit_items = false;

	$("input[name='format_edit_items']").change(function(){
		if(!this.checked || changing_format_edit_items){
			return;
		}

		var thing = this;
		changing_format_edit_items = true;
		$("input[name='format_edit_items']").prop("disabled", true)

		App.changeFormatEditItems(thing.value, function(){
		}, function(){
			thing.checked = false;

			if(thing.value == "inline"){
				$("input[value='modal']").prop("checked", true)
			}else{
				$("input[value='inline']").prop("checked", true)
			}
		}, function(){
			changing_format_edit_items = false;
			$("input[name='format_edit_items']").prop("disabled", false)
		})
	});
}