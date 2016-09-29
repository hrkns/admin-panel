//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};

	var iduser = $("#iduser").val();
	var originalSrcGlobalLogo = $("#src_global_logo").attr("src");
	var originalSrcpersonalLogo = $("#src_personal_logo").attr("src");
	var condFormTabTitle = false;
	var lastVals_name_of_system = {
		global:$("#global_name_of_system").val().trim(),
		personal:"",//$("#personal_name_of_system").val().trim(),
		option:$("#radio_global_name_of_system").prop("checked")?"1":"0"
	};
	var inputGlobalTabTitle = document.getElementById("global_name_of_system");
	var usingWhichTabIcon = $("#radio_button_global_tab_icon").prop("checked")?"1":"0";
	var originalSrcGlobalTabIcon = $("#src_global_tab_icon").attr("src");
	var originalSrcpersonalTabIcon = $("#src_personal_tab_icon").attr("src");
	var lastConfigAlertChat = {
		general : $("#chat_alert_sound_general").val(),
		customized : $("#chat_alert_sound_customized").val(),
		election : $("input[name='chat_alert_sound_election']:checked").val()
	};
	var ccht1 = false, ccht2 = false, ccht3 = false;
	var original_general_session_duration_checkbox = $("#apply_general_session_duration").prop("checked");
	var original_general_session_duration_amount_val = $("#general_session_duration_amount_val").val().trim();
	var original_general_session_duration_amount_type = $("#general_session_duration_amount_type").val().trim();
	var default_config_inactivity_time_limit_use = $("input[name='general_config_inactivity_time_limit']:checked").val();
	var default_config_inactivity_time_limit_amount_val = $("#default_config_inactivity_time_limit_amount_val").val().trim();
	var default_config_inactivity_time_limit_amount_type = $("#default_config_inactivity_time_limit_amount_type").val().trim();

	/***********************************************************************************************************/

	var already_clicked_global_logo = false;

	document.getElementById("change_global_logo").onclick = function(){
		$("#global_logo").trigger("click");
	}

	$("#global_logo").change(function(){
		if(this.files && this.files[0]){
			$("#undo_change_global_logo").show(App.TIME_FOR_SHOW);
			$("#save_global_logo").show(App.TIME_FOR_SHOW);

			var reader = new FileReader();

			reader.onload = function(e){
				$("#src_global_logo").fadeOut(500, function() {
					$("#src_global_logo").attr('src', e.target.result);
			    }).fadeIn(500);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});

	$("#undo_change_global_logo").click(function(){
		$("#src_global_logo").attr("src", originalSrcGlobalLogo);
		$("#save_global_logo").hide(App.TIME_FOR_HIDE);
		$(this).hide(App.TIME_FOR_HIDE);
	});

	var saving_global_logo = false;

	$("#save_global_logo").click(function(){
		if(saving_global_logo){
			return;
		}

		saving_global_logo = true;
		$("#save_global_logo").attr("disabled", true);

		App.HTTP.update({
			url:App.WEB_ROOT+"/global-preferences/logo",
			data:{
				img:$("#src_global_logo").attr("src")
			},success:function(d, e, f){
				$("#save_global_logo").hide(App.TIME_FOR_HIDE);
				$("#undo_change_global_logo").hide(App.TIME_FOR_HIDE);
				originalSrcGlobalLogo = $("#src_global_logo").attr("src");
			},after:function(x, y, z){
				saving_global_logo = false;
				$("#save_global_logo").attr("disabled", false);
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
	function funcCheckTabTitleForm(){
		if(	$("#global_name_of_system").val().trim() != lastVals_name_of_system.global ||
			($("#radio_global_name_of_system").prop("checked") && Number(lastVals_name_of_system.option) == 0) ||
			($("#radio_personal_name_of_system").prop("checked") && Number(lastVals_name_of_system.option) == 1)){
			condFormTabTitle = true;
	    	$("#submit_name_of_system").show(App.TIME_FOR_SHOW);
		}else{
			condFormTabTitle = false;
	    	$("#submit_name_of_system").hide(App.TIME_FOR_HIDE);
		}
	}

	inputGlobalTabTitle.onchange = inputGlobalTabTitle.onkeydown = inputGlobalTabTitle.onkeyup = inputGlobalTabTitle.onchange = inputGlobalTabTitle.onfocus = inputGlobalTabTitle.onblur = function(e) {
		funcCheckTabTitleForm();
	}

	$("#radio_global_name_of_system").click(function(){
		funcCheckTabTitleForm();
	});

	$("#radio_personal_name_of_system").click(function(){
		funcCheckTabTitleForm();
	});

	var saving_name_of_system = false;

	$("#form_name_of_system").submit(function(e){
		e.preventDefault();

		if(saving_name_of_system){
			return;
		}

		saving_name_of_system = true;
		$("#submit_name_of_system").attr("disabled", true);

		if(condFormTabTitle){
			App.HTTP.update({
				url:App.WEB_ROOT+"/tab-title-preferences",
				data:{
					val_global:$("#global_name_of_system").val().trim()
				},success:function(d, e, f){
					$("#submit_name_of_system").hide(App.TIME_FOR_HIDE);
					lastVals_name_of_system = {
				    	global:$("#global_name_of_system").val().trim(),
					}

					if(Number(lastVals_name_of_system.option) == 1){
						$("#name_of_system").html(lastVals_name_of_system.global);
					}else{
						$("#name_of_system").html(lastVals_name_of_system.personal);
					}
				},after:function(x, y, z){
					$("#submit_name_of_system").attr("disabled", false);
					saving_name_of_system = false;
				}
			});
		}
	});
	/**********************************************************************************************************************/

	document.getElementById("change_global_tab_icon").onclick = function(){
		$("#global_tab_icon").trigger("click");
	};

	$("#global_tab_icon").change(function(){
		if(this.files && this.files[0]){
			$("#undo_change_global_tab_icon").show(App.TIME_FOR_SHOW);
			$("#save_global_tab_icon").show(App.TIME_FOR_SHOW);
			var reader = new FileReader();

			reader.onload = function(e){
				$("#src_global_tab_icon").fadeOut(500, function() {
					$("#src_global_tab_icon").attr('src', e.target.result);
			    }).fadeIn(500);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});

	$("#undo_change_global_tab_icon").click(function(){
		$("#src_global_tab_icon").attr("src", originalSrcGlobalTabIcon);
		$("#save_global_tab_icon").hide(App.TIME_FOR_HIDE);
		$(this).hide(App.TIME_FOR_HIDE);
	});

	var saving_global_tab_icon = false;

	$("#save_global_tab_icon").click(function(){
		if(saving_global_tab_icon){
			return;
		}

		saving_global_tab_icon = true;
		$("#save_global_tab_icon").attr("disabled", true);

		App.HTTP.update({
			url:App.WEB_ROOT+"/global-preferences/tab-icon",
			data:{
				img:$("#src_global_tab_icon").attr("src")
			},success:function(d, e, f){
				$("#save_global_tab_icon").hide(App.TIME_FOR_HIDE);
				$("#undo_change_global_tab_icon").hide(App.TIME_FOR_HIDE);
				originalSrcGlobalTabIcon = $("#src_global_tab_icon").attr("src");

				if(usingWhichTabIcon == "1"){
					$("#original_global_tab_icon").attr("href", originalSrcGlobalTabIcon);
				}
			},after:function(x, y, z){
				$("#save_global_tab_icon").attr("disabled", false);
				saving_global_tab_icon = false;
			}
		});
	});
	/**********************************************************************************************************************/
	$("#terms").summernote({
		height:"500px"
	});

	var saving_terms = false;

	$("#save_terms").click(function(){
		if(saving_terms){
			return;
		}

		saving_terms = true;
		$("#save_terms").attr("disabled", true);

		App.HTTP.update({
			url:App.WEB_ROOT+"/global-preferences/terms-of-use-and-privacy-policy",
			data:{
				val:$("#terms").code()
			},success:function(d, e, f){
			},after:function(x, y, z){
				$("#save_terms").attr("disabled", false);
				saving_terms = false;
			}
		});
	});

	$(".note-editable").css("height", "");
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
				type : "global",
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
	$("#apply_general_session_duration").change(function(){
		if(this.checked){
			$("#general_session_duration_settings").show(App.TIME_FOR_SHOW);
		}else{
			$("#general_session_duration_settings").hide(App.TIME_FOR_HIDE);
		}

		checkChangesGeneralSessionDuration();
	});
	$("#general_session_duration_amount_type").change(checkChangesGeneralSessionDuration);
	App.inputTextMonitor("general_session_duration_amount_val", checkChangesGeneralSessionDuration);

	function checkChangesGeneralSessionDuration(){
		if(	$("#apply_general_session_duration").prop("checked") != original_general_session_duration_checkbox ||
			$("#general_session_duration_amount_val").val().trim() != original_general_session_duration_amount_val ||
			$("#general_session_duration_amount_type").val().trim() != original_general_session_duration_amount_type){
			$("#save_changes_general_session_duration").show(App.TIME_FOR_SHOW);
		}else{
			$("#save_changes_general_session_duration").hide(App.TIME_FOR_HIDE);
		}
	}

	var saving_changes_general_session_duration = false;

	$("#save_changes_general_session_duration").click(function(){
		if(saving_changes_general_session_duration){
			return;
		}

		saving_changes_general_session_duration = true;
		$("#save_changes_general_session_duration").attr("disabled", true);

		App.HTTP.update({
			url : App.WEB_ROOT + "/general-session-duration",
			data : {
				apply : $("#apply_general_session_duration").prop("checked")?"1":"0",
				amount : $("#general_session_duration_amount_val").val().trim(),
				type : $("#general_session_duration_amount_type").val().trim()
			},
			success : function(d, e, f){
				original_general_session_duration_checkbox 		= $("#apply_general_session_duration").prop("checked");
				original_general_session_duration_amount_val	= $("#general_session_duration_amount_val").val().trim();
				original_general_session_duration_amount_type 	= $("#general_session_duration_amount_type").val().trim();
				$("#save_changes_general_session_duration").hide(App.TIME_FOR_HIDE);
			}, error : function(x, y, z){
			}, after : function(){
				$("#save_changes_general_session_duration").attr("disabled", false);
				saving_changes_general_session_duration = false;
			}
		});
	});
	/**********************************************************************************************************************/
	$("input[name='general_config_inactivity_time_limit']").click(function(){
		if(this.value == "no"){
			$("#default_config_inactivity_time_limit").hide(App.TIME_FOR_HIDE);
		}else{
			$("#default_config_inactivity_time_limit").show(App.TIME_FOR_SHOW);
		}
		checkChangesDefaultInactivityTimelimit();
	});
	$("#default_config_inactivity_time_limit_amount_type").change(checkChangesDefaultInactivityTimelimit);
	App.inputTextMonitor("default_config_inactivity_time_limit_amount_val", checkChangesDefaultInactivityTimelimit);
	function checkChangesDefaultInactivityTimelimit(){
		if(	$("input[name='general_config_inactivity_time_limit']:checked").val() != default_config_inactivity_time_limit_use ||
			$("#default_config_inactivity_time_limit_amount_val").val().trim() != default_config_inactivity_time_limit_amount_val ||
			$("#default_config_inactivity_time_limit_amount_type").val().trim() != default_config_inactivity_time_limit_amount_type){
			$("#save_changes_inactivity_time_limit").show(App.TIME_FOR_SHOW);
		}else{
			$("#save_changes_inactivity_time_limit").hide(App.TIME_FOR_HIDE);
		}
	}

	var saving_changes_inactivity_time_limit = false;

	$("#save_changes_inactivity_time_limit").click(function(){
		if(saving_changes_inactivity_time_limit){
			return;
		}

		saving_changes_inactivity_time_limit = true;
		$("#save_changes_inactivity_time_limit").attr("disabled", true);

		App.HTTP.update({
			url : App.WEB_ROOT + "/default-config-inactivity-time-limit",
			data : {
				use_as : $("input[name='general_config_inactivity_time_limit']:checked").val(),
				amount : $("#default_config_inactivity_time_limit_amount_val").val().trim(),
				type : $("#default_config_inactivity_time_limit_amount_type").val().trim()
			},
			success : function(d, e, f){
				default_config_inactivity_time_limit_use 	= $("input[name='general_config_inactivity_time_limit']:checked").val();
				default_config_inactivity_time_limit_amount_val	= $("#default_config_inactivity_time_limit_amount_val").val().trim();
				default_config_inactivity_time_limit_amount_type = $("#default_config_inactivity_time_limit_amount_type").val().trim();
				$("#save_changes_inactivity_time_limit").hide(App.TIME_FOR_HIDE);
			}, error : function(x, y, z){
			}, after : function(){
				saving_changes_inactivity_time_limit = false;
				$("#save_changes_inactivity_time_limit").attr("disabled", false);
			}
		});
	});
	/**********************************************************************************************************************/
	var changing_default_language_system = false;
	var previous_default_language = $("#select_default_language_system").val();

	$("#select_default_language_system").change(function(){
		if(changing_default_language_system){
			return;
		}

		var lng = $("#select_default_language_system").val();
		$("#select_default_language_system").attr("disabled", true);
		changing_default_language_system = true;

		App.HTTP.update({
			url : App.WEB_ROOT + "/default-language-system",
			data : {
				lng : lng
			},
			received : function(){
				$("#select_default_language_system").attr("disabled", false);
			},
			success : function(){
				previous_default_language = lng;
			},
			error : function(){
				$("#select_default_language_system").val(previous_default_language);
			},
			after: function(){
				changing_default_language_system = false;
			}
		});
	});
	/**********************************************************************************************************************/
	var changing_format_show_items = false;

	$("input[name='format_show_items']").change(function(){
		if(!this.checked || changing_format_show_items){
			return;
		}

		var thing = this;
		changing_format_show_items = true;
		$("input[name='format_show_items']").prop("disabled", true)

		App.HTTP.update({
			url : App.WEB_ROOT + "/default-format-show-items",
			data : {
				format : thing.value
			},
			error : function(){
				thing.checked = false;

				if(thing.value == "progressive"){
					$("input[value='pagination']").prop("checked", true)
				}else{
					$("input[value='progressive']").prop("checked", true)
				}
			},after : function(){
				changing_format_show_items = false;
				$("input[name='format_show_items']").prop("disabled", false)
			}
		});
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

		App.HTTP.update({
			url : App.WEB_ROOT + "/default-format-edit-items",
			data : {
				format : thing.value
			},
			error : function(){
				thing.checked = false;

				if(thing.value == "inline"){
					$("input[value='modal']").prop("checked", true)
				}else{
					$("input[value='inline']").prop("checked", true)
				}
			},after : function(){
				changing_format_edit_items = false;
				$("input[name='format_edit_items']").prop("disabled", false)
			}
		});
	});
}