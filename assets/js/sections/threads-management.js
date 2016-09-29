//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.ENDPOINT_ITEMS_INDEX = "/threads";
	this.ENDPOINT_ITEMS_SEARCH = "/threads-search";
	this.ENDPOINT_ITEM = "/thread/";
	this.getItems = null;
	this.IN_THREAD_WITH_ID;
	this.ID_ADMIN_REMOVE;
	this.ID_ADMIN_UPDATE_PERMISES;
	this.threadsData = {};
	this.save_thread_after_confirmation;
	this.ID_USER = $("#iduser").val().trim();

	var privacyMacros = {
		"private" : 0,
		"protected" : 1,
		"public" : 2
	};
	var file_audio_alert = App.WEB_ROOT + "/assets/audio/notifications/" + $("#file_alert_sound").val();
	var notification_audio = new Audio(file_audio_alert);
	var	INTERVAL_REQUEST_UNREAD = 1;
	var	LAST_MSG_DATE = null;
	var	LAST_MSG_ID = null;
	var threadBack = "items_controls";
	var inputMessage = document.getElementById("txt_message");
	var reset = true;
	var LIMIT_INTERVAL_REQUEST_UNREAD = 30;
	this.arrayStatusForDelete = Array();
	this.ITEMS = {};

	/***********************************************************************************************************/

	inputMessage.onclick = inputMessage.onkeydown = inputMessage.onkeyup = inputMessage.onblur = inputMessage.onfocus = inputMessage.onchange = function(e){
		var code = (e.keyCode ? e.keyCode : e.which);

		if(code==13 && $("#press_enter_to_send_message").prop("checked") && $("#txt_message").val().trim().length > 0){
			$("#send_message").trigger("click");
		}
	}

	App.TimeInterval(function(){
		Section.FLAGS.LET_CHANGE_SECTION = 	($("#save_changes_in_thread").length == 0 || $("#save_changes_in_thread").css("display") == "none") && $("#txt_message").val().trim().length == 0;
	}, 1000);

	function getUnreadMessages(){
		if(typeof Section.IN_THREAD_WITH_ID == "undefined"){
			return;
		}

		if(LAST_MSG_ID != null){
			App.HTTP.read({
				url : App.WEB_ROOT + "/thread/"+Section.IN_THREAD_WITH_ID+"/recent-messages",
				data: {
					last_date : LAST_MSG_DATE,
					last_id : LAST_MSG_ID
				},
				success : function(d, e, f){
					$.each(d.data.items, function(key, val){
						add_msg_to_chat(val);
					});

					if(d.data.items.length > 0){
						notification_audio.play();
						INTERVAL_REQUEST_UNREAD = 1;
					}else{
						INTERVAL_REQUEST_UNREAD = INTERVAL_REQUEST_UNREAD*2>LIMIT_INTERVAL_REQUEST_UNREAD?LIMIT_INTERVAL_REQUEST_UNREAD:INTERVAL_REQUEST_UNREAD*2;
					}
				},after : function(x, y, z){
					setTimeout(getUnreadMessages, INTERVAL_REQUEST_UNREAD*1000);
				},log_ui_msg:false
			});
		}else{
			if(INTERVAL_REQUEST_UNREAD >= LIMIT_INTERVAL_REQUEST_UNREAD){
				reset = true;
				$("#load_more_messages_of_thread").trigger("click");
			}else{
				INTERVAL_REQUEST_UNREAD = INTERVAL_REQUEST_UNREAD*2>LIMIT_INTERVAL_REQUEST_UNREAD?LIMIT_INTERVAL_REQUEST_UNREAD:INTERVAL_REQUEST_UNREAD*2;
			}

			setTimeout(getUnreadMessages, INTERVAL_REQUEST_UNREAD*1000);
		}
	}

	/***************************************************************/

	this.add_item_form_to_dom = function(data, created){
		var row = App.startConfigRow(data);

		var idxAdmin = 0;

		while(idxAdmin < data.admins.length && Number(Section.ID_USER) != Number(data.admins[idxAdmin].id)){
			idxAdmin++;
		}

		var IS_ADMIN = idxAdmin < data.admins.length;
		var	permises = IS_ADMIN?data.admins[idxAdmin].permises:{};

		if(idxAdmin == 0){
			permises = {
				delete_thread 				: true,

				edit_title 					: true,
				edit_description 			: true,	

				add_admin 					: true,

				no_remove_admin 			: false,
				remove_any_admin 			: true,
				remove_specific_admin 		: false,

				no_set_permises_admin 		: false,
				set_permises_any_admin 		: true,
				set_permises_specific_admin : false,

				set_privacy 				: true,	
				accept_join_requests 		: true,	
				reject_join_requests 		: true 	
			}
		}

		Section.threadsData[String(data.id)] = data;
		Section.threadsData[String(data.id)]["__permises__"] = permises;

		/*
			column for attribute 'id'
		*/
			var column_id = document.createElement("td");
			column_id.align = "center";
			column_id.innerHTML = "<strong>"+data.id+"</strong>";

		/*
			column for attribute 'title'
		*/
			var	column_thread_title = document.createElement("td");
			column_thread_title.align = "center";
			var main_text_title_of_the_thread = document.createElement("p");
			main_text_title_of_the_thread.style.fontWeight = "bold";
			main_text_title_of_the_thread.innerHTML = data.title;
			column_thread_title.appendChild(main_text_title_of_the_thread);

		/*
			column for attribute 'description'
		*/
			var	column_thread_description = document.createElement("td");
			column_thread_description.align = "center";
			var text_thread_description = document.createElement("p");
			text_thread_description.style.fontWeight = "bold";
			text_thread_description.innerHTML = data.description;
			column_thread_description.appendChild(text_thread_description);

		/*
			column for attribute 'admins'
		*/
			var	column_thread_administrators = document.createElement("td");
			column_thread_administrators.align = "center";
			var text_thread_administrators = document.createElement("p");
			text_thread_administrators.style.fontWeight = "bold";
			text_thread_administrators.innerHTML = data.admins[0].fullname
			column_thread_administrators.appendChild(text_thread_administrators);

		/*
			column for controls of preview of the thread
		*/
			var	column_thread_preview_controls = document.createElement("td");
			column_thread_preview_controls.align = "center";

		/*
			button to enter to the interface of the thread
		*/
			var button_enter_to_thread = document.createElement("button");
			button_enter_to_thread.className = "btn btn-primary";
			button_enter_to_thread.innerHTML = App.terms.str_enter;

			var changed_privacy_of_the_thread, button_for_save_the_changes_applied_to_the_info_of_the_thread;

			button_enter_to_thread.onclick = function(e){
				e.preventDefault();

				Section.IN_THREAD_WITH_ID=data.id;
				reset=true;
				$("#items_controls").hide(App.TIME_FOR_HIDE);
				$("#main_div_search_results").hide(App.TIME_FOR_HIDE);
				$("	#thread_interface_title, \
					#thread_interface_description,\
					#thread_interface_admins,\
					#thread_interface_speakers,\
					#thread_interface_join_requests,\
					#thread_interface_controls_others").empty();

				$("#main_static_title_thread").html(data.title);

				if (IS_ADMIN){
					function watcher(tkn){
						var tmp1 = Array();
						$.each(data.speakers, function(k, v){
							tmp1.push(v.id);
						});
						if($(input_title_of_thread).val().trim().length > 0 && (
								$(input_title_of_thread).val().trim() != data.title ||
								$(description).val().trim() != data.description ||
								(($(input_radio_for_private_thread).prop("checked") || $(input_radio_for_protected_thread).prop("checked")) && !App.flexible_equal_array(tmp1, $(select_multiple_of_participants_on_thread).val())) || 
								($(input_radio_for_private_thread).prop("checked") && Number(data.privacy) != 0) ||
								($(input_radio_for_protected_thread).prop("checked") && Number(data.privacy) != 1) ||
								($(input_radio_for_public_thread).prop("checked") && Number(data.privacy) != 2))
							) {
							$(button_for_save_the_changes_applied_to_the_info_of_the_thread).show(App.TIME_FOR_SHOW);
						}else{
							$(button_for_save_the_changes_applied_to_the_info_of_the_thread).hide(App.TIME_FOR_HIDE);
						}

						if(typeof Section != "undefined" && typeof Section.FLAGS.TOKEN != "undefined" && tkn == Section.FLAGS.TOKEN){
							setTimeout(function(){
								watcher(tkn);
							}, (Section.FLAGS.amountItems)*100);
						}
					}

					var input_title_of_thread = document.createElement("input");
					input_title_of_thread.value = data.title;
					input_title_of_thread.className = "form-control";
					input_title_of_thread.readOnly = !App.isTrue(permises.edit_title);

					var text_for_input_title_of_thread = document.createElement("p");
					text_for_input_title_of_thread.innerHTML = "<strong>"+App.terms.str_title+":</strong>";

					$("#thread_interface_title").append(text_for_input_title_of_thread);
					$("#thread_interface_title").append(input_title_of_thread);
					$("#thread_interface_title").append(document.createElement("br"));

					var description = document.createElement("textarea");
					description.innerHTML = data.description;
					description.className = "form-control";
					description.readOnly = !App.isTrue(permises.edit_description);

					var text_thread_descriptioninput = document.createElement("p");
					text_thread_descriptioninput.innerHTML = "<br><strong>"+App.terms.str_description+":</strong>";

					$("#thread_interface_description").append(text_thread_descriptioninput);
					$("#thread_interface_description").append(description);

					var text_of_list_of_admins_of_thread = document.createElement("p");
					text_of_list_of_admins_of_thread.style.fontWeight = "bold";
					text_of_list_of_admins_of_thread.innerHTML = "<br>"+App.terms.str_administrator;

					var list_of_admins_of_thread = document.createElement("ul");
					list_of_admins_of_thread.id = "list_admins_"+Section.IN_THREAD_WITH_ID;
					$("#thread_interface_admins").append(text_of_list_of_admins_of_thread);
					$("#thread_interface_admins").append(list_of_admins_of_thread);

					var listSpeakers = Array();
					$.each(data.speakers, function(k, v){
						listSpeakers.push(Number(v.id));
					});

					$.each(Section.threadsData[Section.IN_THREAD_WITH_ID].admins, function(k, v){
						add_li_to_list_admins({
							fullname : v.fullname,
							id : v.id,
							added_by : v.added_by
						});
					});

					if(permises.add_admin){
						var button_for_show_modal_for_add_admin = document.createElement("button");
						button_for_show_modal_for_add_admin.className = "btn btn-primary";
						button_for_show_modal_for_add_admin.innerHTML = App.terms.str_add_administrator;
						button_for_show_modal_for_add_admin.onclick = function(){
							App.getView("thread-admin", "create");
						}
						$("#thread_interface_admins").append(document.createElement("br"));
						$("#thread_interface_admins").append(button_for_show_modal_for_add_admin);
					}

					var random_value = String(Math.random());
					var text_privacy_of_the_thread = document.createElement("p");
					text_privacy_of_the_thread.innerHTML = "<br><strong>"+App.terms.str_privacy+":</strong>";

					var input_radio_for_private_thread = document.createElement("input");
					input_radio_for_private_thread.type = "radio";
					input_radio_for_private_thread.checked = Number(data.privacy) == 0;
					input_radio_for_private_thread.onclick = function(){
						$(div_of_participants_on_thread).show(App.TIME_FOR_SHOW);
					}
					input_radio_for_private_thread.name = random_value;

					var description_for_input_radio_for_private_thread = document.createElement("span");
					description_for_input_radio_for_private_thread.innerHTML = "&nbsp;"+App.terms.str_private;

					var input_radio_for_protected_thread = document.createElement("input");
					input_radio_for_protected_thread.type = "radio";
					input_radio_for_protected_thread.checked = Number(data.privacy) == 1;
					input_radio_for_protected_thread.name = random_value;
					input_radio_for_protected_thread.onclick = function(){
						$(div_of_participants_on_thread).show(App.TIME_FOR_SHOW);
					}

					var description_for_input_radio_for_protected_thread = document.createElement("span");
					description_for_input_radio_for_protected_thread.innerHTML = "&nbsp;"+App.terms.str_protected;

					var input_radio_for_public_thread = document.createElement("input");
					input_radio_for_public_thread.type = "radio";
					input_radio_for_public_thread.checked = Number(data.privacy) == 2;
					input_radio_for_public_thread.name = random_value;
					input_radio_for_public_thread.onclick = function(){
						$(div_of_participants_on_thread).hide(App.TIME_FOR_HIDE);
					}

					var description_for_input_radio_for_public_thread = document.createElement("span");
					description_for_input_radio_for_public_thread.innerHTML = "&nbsp;"+App.terms.str_public;

					var select_multiple_of_participants_on_thread = document.createElement("select");
					var chl = document.getElementById("db_users").childNodes;
					select_multiple_of_participants_on_thread.multiple = "multiple";
					select_multiple_of_participants_on_thread.id = "select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID;
					var nchl = chl.length;

					for(var i = 0; i < nchl; i++){
						if(chl[i].nodeType == 1){
							var op = document.createElement("option");
							op.value = chl[i].value;
							op.innerHTML = chl[i].innerHTML;
							op.selected = listSpeakers.indexOf(Number(op.value)) != -1;
							select_multiple_of_participants_on_thread.appendChild(op);
						}
					};

					$("#thread_interface_speakers").append(text_privacy_of_the_thread);
					$("#thread_interface_speakers").append(input_radio_for_private_thread);
					$("#thread_interface_speakers").append(description_for_input_radio_for_private_thread);
					$("#thread_interface_speakers").append(document.createElement("br"));

					$("#thread_interface_speakers").append(input_radio_for_protected_thread);
					$("#thread_interface_speakers").append(description_for_input_radio_for_protected_thread);
					$("#thread_interface_speakers").append(document.createElement("br"));

					$("#thread_interface_speakers").append(input_radio_for_public_thread);
					$("#thread_interface_speakers").append(description_for_input_radio_for_public_thread);
					$("#thread_interface_speakers").append(document.createElement("br"));

					var div_of_participants_on_thread = document.createElement("div");
					div_of_participants_on_thread.appendChild(select_multiple_of_participants_on_thread);
					div_of_participants_on_thread.style.display = Number(data.privacy) < 2?"":"none";

					$("#thread_interface_speakers").append(div_of_participants_on_thread);
					$(select_multiple_of_participants_on_thread).select2();

					var title_of_interface_of_requests_to_join_to_the_thread = document.createElement("p");
					var div_of_list_of_request_to_join_to_the_thread = document.createElement("div");

					title_of_interface_of_requests_to_join_to_the_thread.innerHTML = "<br><strong>"+App.terms.str_join_thread_requests+":</strong>";

					$.each(data.joinRequests, function(k, v){
						var div_request = document.createElement("div");
						div_request.id = "speaker_request_"+v.id;

						var fullname_of_user_requesting = document.createElement("span");
						fullname_of_user_requesting.innerHTML = v.fullname;

						var anchor_to_accept_the_request = document.createElement("a");
						anchor_to_accept_the_request.innerHTML = "&nbsp;&nbsp;"+App.terms.str_yes+"&nbsp;";
						anchor_to_accept_the_request.href = "javascript:;";

						var accepting_the_request = false;

						anchor_to_accept_the_request.onclick = function(e){
							if(accepting_the_request){
								return;
							}

							accepting_the_request = true;

							App.HTTP.create({
								url:App.WEB_ROOT+"/thread/"+data.id+"/speaker",
								data:{
									iduser:v.id
								},success:function(d, e, f){
									data.speakers.push(v);
									$(select_multiple_of_participants_on_thread).select2("destroy");
									$(select_multiple_of_participants_on_thread).find("option[value='"+v.id+"']").prop("selected", true);
									$(select_multiple_of_participants_on_thread).select2();
									$(".select2-container").css("width", "100%");
									$(div_request).remove();
									listenLastActivity("row_thread_"+data.id);

									var tmp = Array();

									$.each(data.joinRequests, function(t, s){
										if(Number(s.id) != Number(v.id)){
											tmp.push(s);
										}
									});

									data.joinRequests = tmp;
								},after:function(x, y, z){
									accepting_the_request = false;
								},log_ui_msg:false
							});
						}

						var anchor_to_reject_the_request = document.createElement("a");
						anchor_to_reject_the_request.innerHTML = "&nbsp;"+App.terms.str_no+"&nbsp;";
						anchor_to_reject_the_request.href = "javascript:;";

						anchor_to_reject_the_request.onclick = function(e){
							if(accepting_the_request){
								return;
							}

							accepting_the_request = true;

							App.HTTP.delete({
								url:App.WEB_ROOT+"/thread/"+data.id+"/join-request/"+v.idrequest,
								data:{
									iduser:v.id
								},success:function(d, e, f){
									$(div_request).remove();
									var tmp = Array();

									$.each(data.joinRequests, function(t, s){
										if(Number(s.id) != Number(v.id)){
											tmp.push(s);
										}
									});

									data.joinRequests = tmp;
									listenLastActivity("row_thread_"+data.id);
								},after:function(x, y, z){
									accepting_the_request = false;
								},log_ui_msg : false
							});
						}

						div_request.appendChild(fullname_of_user_requesting);

						if(permises.accept_join_requests){
							div_request.appendChild(anchor_to_accept_the_request);
						}

						if(permises.reject_join_requests){
							div_request.appendChild(anchor_to_reject_the_request);
						}

						div_of_list_of_request_to_join_to_the_thread.appendChild(div_request);
					});

					$("#thread_interface_join_requests").append(title_of_interface_of_requests_to_join_to_the_thread).append(div_of_list_of_request_to_join_to_the_thread).show(App.TIME_FOR_SHOW);

					var button_for_save_the_changes_applied_to_the_info_of_the_thread = document.createElement("button");
					button_for_save_the_changes_applied_to_the_info_of_the_thread.className = "btn btn-success";
					button_for_save_the_changes_applied_to_the_info_of_the_thread.innerHTML = App.terms.str_save_changes;
					button_for_save_the_changes_applied_to_the_info_of_the_thread.style.display = "none";
					button_for_save_the_changes_applied_to_the_info_of_the_thread.id = "save_changes_in_thread";

					button_for_save_the_changes_applied_to_the_info_of_the_thread.onclick = function(e){
						e.preventDefault();
						changed_privacy_of_the_thread = $(input_radio_for_private_thread).prop("checked")?0:($(input_radio_for_protected_thread).prop("checked")?1:2);

						if(Number(changed_privacy_of_the_thread) < 2 && Number(data.privacy) == 2){
							//$("#modal_confirmation_change_privacy").modal("show");
							App.getView("thread-privacy", "edit")
						}else{
							update_thread();
						}
					}

					$("#thread_interface_controls_others").append(button_for_save_the_changes_applied_to_the_info_of_the_thread);
					$(".select2-container").css("width", "100%");
					watcher(Section.FLAGS.TOKEN);

					var saving_thread = false;

					function update_thread(){
						if(saving_thread){
							return;
						}

						saving_thread = button_for_save_the_changes_applied_to_the_info_of_the_thread.disabled = true;
						App.ShowLoading(App.terms.str_saving_changes);
						App.HTTP.update({
							url:App.WEB_ROOT+"/thread/"+data.id,
							data:{
								title:$(input_title_of_thread).val().trim(),
								description:$(description).val().trim(),
								privacy:changed_privacy_of_the_thread,
								select_multiple_of_participants_on_thread:$(select_multiple_of_participants_on_thread).val(),
								add_previous_participants : $("#yes_add_participants").prop("checked")?1:0
							},success:function(d, e, f){
								var valr = Array();

								$("#main_static_title_thread").html($(input_title_of_thread).val().trim());

								if($(select_multiple_of_participants_on_thread).val() != null){
									$.each($(select_multiple_of_participants_on_thread).val(), function(k, v){
										$("#speaker_request_"+v).remove();
									});
									valr = $(select_multiple_of_participants_on_thread).val();
								}

								data.speakers = Array();

								$.each(valr, function(kk, vv){
									data.speakers.push({
										id:vv, 
										fullname:$("#db_users").find("option[value='"+vv+"']").html().trim()
									});
								});

								if(Number(changed_privacy_of_the_thread) < 2 && Number(data.privacy) == 2 && d.data.speakers){
									var n = d.data.speakers.length, t;
									$("#select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID).select2("destroy");
									data.speakers = Array();
									$("#select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID).find("option").prop("selected", false);

									for(var i = 0; i < n; i++){
										t = $("#select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID).find("option[value='"+d.data.speakers[i]+"']")[0];
										if(t){
											$(t).prop("selected", true);
											data.speakers.push({
												id : d.data.speakers[i],
												fullname : $(t).html().trim()
											});
										}
									}

									$("#select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID).select2();
									$(".select2-container").css("width", "100%");
								}else if(Number(changed_privacy_of_the_thread) == 2){
									$("#select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID).select2("destroy");
									$("#select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID).find("option").prop("selected", false);
									$("#select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID).select2();
									$(".select2-container").css("width", "100%");
								}else if(Number(changed_privacy_of_the_thread) < 2){
									$("#select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID).find("option").each(function(){
										if(!$(this).prop("selected")){
											$("#li_admin_"+$(this).val().trim()).remove();
										}
									});
								}

								data.privacy = changed_privacy_of_the_thread;
								main_text_title_of_the_thread.innerHTML = data.title = $(input_title_of_thread).val().trim();
								text_thread_description.innerHTML = data.description = $(description).val().trim();
								listenLastActivity("row_thread_"+data.id);
							},after : function(x, y, z){
								saving_thread = false;
								button_for_save_the_changes_applied_to_the_info_of_the_thread.disabled = false;
								App.HideLoading();
							}
						});
					}

					Section.save_thread_after_confirmation = update_thread;
				}else{
					var title = document.createElement("h3");
					title.innerHTML = data.title;
					$("#thread_interface_title").append(title);

					var description = document.createElement("p");
					description.innerHTML = data.title;
					$("#thread_interface_description").append(description);

					var text_of_list_of_admins_of_thread = document.createElement("p");
					text_of_list_of_admins_of_thread.style.fontWeight = "bold";
					text_of_list_of_admins_of_thread.innerHTML = App.terms.str_administrator;

					var list_of_admins_of_thread = document.createElement("ul");

					$.each(Section.threadsData[Section.IN_THREAD_WITH_ID].admins, function(k, v){
						var li = document.createElement("li");
						li.innerHTML = v.fullname;
						list_of_admins_of_thread.appendChild(li);
					});

					$("#thread_interface_admins").append(text_of_list_of_admins_of_thread);
					$("#thread_interface_admins").append(list_of_admins_of_thread);

					var participants_paragraph = document.createElement("p");
					if(Number(data.privacy) < 2){
						participants_paragraph.innerHTML = "<strong>"+App.terms.str_participants+"</strong>: "+(function(){
							var arr = [];

							$.each(data.speakers, function(k, v){
								arr.push(v.fullname);
							});

							return arr;
						})().join(", ")+".";
					}else{
						participants_paragraph.innerHTML = App.terms.str_public_thread+".";
					}

					$("#thread_interface_speakers").append(participants_paragraph);
					$("#thread_interface_join_requests").hide(App.TIME_FOR_HIDE);

					if(Number(data.privacy) < 2){
						var button_for_leave_the_thread = document.createElement("button");
						button_for_leave_the_thread.className = "btn btn-danger";
						button_for_leave_the_thread.innerHTML = App.terms.str_leave_thread;
						var leaving_thread = false;

						button_for_leave_the_thread.onclick = function(e){
							e.preventDefault();

							if(leaving_thread){
								return;
							}

							leaving_thread = button_for_leave_the_thread.disabled = true;
							App.ShowLoading(App.terms.str_leaving);

							App.HTTP.delete({
								url:App.WEB_ROOT+"/thread/"+data.id+"/join-request",
								success:function(d, e, f){
									$("#thread_interface").hide(App.TIME_FOR_HIDE);
									$(button_request_for_enter_to_thread).show(App.TIME_FOR_SHOW);
									button_request_for_enter_to_thread.className = "btn btn-warning";
									button_request_for_enter_to_thread.innerHTML = App.terms.str_ask_for_enter;
									$(button_enter_to_thread).hide(App.TIME_FOR_HIDE);
									if(Number(data.privacy) == 0){
										$("#row_thread_"+data.id+", #row_thread_search_"+data.id).remove();
									}else{
										listenLastActivity("row_thread_"+data.id);
									}
									$("#"+threadBack).show(App.TIME_FOR_SHOW);
								},after:function(x, y, z){
									leaving_thread = button_for_leave_the_thread.disabled = false;
									App.HideLoading();
								}
							})
						}
						$("#thread_interface_controls_others").append(button_for_leave_the_thread);
					}
				}

				$("#thread_controls").hide();
				$("#list_messages").empty();
				$("#load_more_messages_of_thread").trigger("click");
				$("#thread_interface").show(App.TIME_FOR_SHOW);
				LAST_MSG_DATE=LAST_MSG_ID=null;
				getUnreadMessages();

				setTimeout(function(){
					$("#container_controls").hide(App.TIME_FOR_HIDE);
					$("body").scrollTop(10000);//$("#go_back_threads")[0].offsetTop);
				}, App.TIME_FOR_SHOW);
			}

		/*
			button to request to be accepted on the thread (when it's protected)
		*/
			var button_request_for_enter_to_thread = document.createElement("button");
			button_request_for_enter_to_thread.className = "btn btn-warning";
			button_request_for_enter_to_thread.innerHTML = App.terms.str_ask_for_enter;
			var asking_for_enter = false;

			button_request_for_enter_to_thread.onclick = function(e){
				e.preventDefault();

				if(asking_for_enter){
					return;
				}

				asking_for_enter = button_request_for_enter_to_thread.disabled = true;

				App.HTTP.create({
					url:App.WEB_ROOT+"/thread/"+data.id+"/join-request",
					success:function(d, e, f){
						if(Number(d.data.val) == 0){
							button_request_for_enter_to_thread.className = "btn btn-warning";
							button_request_for_enter_to_thread.innerHTML = App.terms.str_ask_for_enter;
						}else{
							button_request_for_enter_to_thread.className = "btn btn-info"
							button_request_for_enter_to_thread.innerHTML = App.terms.str_abort_apply_enter_on_thread;
						}
					},after:function(x, y, z){
						asking_for_enter = button_request_for_enter_to_thread.disabled = false;
					}
				});
			}

		/*
			here is choosen which buttons are added to the column of thread preview controls
		*/
			switch(Number(data.privacy)){
				case 0:
					column_thread_preview_controls.appendChild(button_enter_to_thread);
				break;
				case 1:
					if(Number(data.joinThread) == 1){
						if(Number(data.joinRequest) == 1){
							button_request_for_enter_to_thread.className = "btn btn-info"
							button_request_for_enter_to_thread.innerHTML = App.terms.str_abort_apply_enter_on_thread;
						}

						column_thread_preview_controls.appendChild(button_request_for_enter_to_thread);
					}else{
						column_thread_preview_controls.appendChild(button_enter_to_thread);
					}
				break;
				case 2:
					column_thread_preview_controls.appendChild(button_enter_to_thread);
				break;
			}

		/*
			if the user has authorization, create controls for delete thread
		*/
			if(App.isTrue(permises.delete_thread)){
				App.controls_for_delete_item(data, data.row_selector, column_thread_preview_controls, true);
			}

		/*
			appending columns to row of item
		*/
			row.appendChild(column_id);
			row.appendChild(column_thread_title);
			row.appendChild(column_thread_description);
			row.appendChild(column_thread_administrators);
			row.appendChild(column_thread_preview_controls);

		/*
			styling and setting needed metadata
		*/
			row.id = "row_thread_"+(Section.FLAGS.onSearch?"search_":"")+data.id;
			row.style.display = "none";

		App.finalConfigRow(row, data);
	}

	$("#col_see_with_status").hide(App.TIME_FOR_HIDE);
	$("#col_items_language").hide(App.TIME_FOR_HIDE);

	/*
		control to go back to the threads list
	*/
	$("#go_back_threads").click(function(){
		$("#container_controls").show(App.TIME_FOR_SHOW);
		$("#thread_interface").hide(App.TIME_FOR_HIDE);
		$("#"+threadBack).show(App.TIME_FOR_SHOW);
	});

	/*
		see older messages of the thread
	*/

	var getting_more_messages = false;

	$("#load_more_messages_of_thread").click(function(){
		if(getting_more_messages){
			return;
		}

		$("#load_more_messages_of_thread").hide(App.TIME_FOR_HIDE);
		$("#loading_messages").show(App.TIME_FOR_SHOW);

		var data = {
			token:Section.FLAGS.TOKEN,
			type:"messages"
		};

		if(reset){
			data["reset"] = true;
		}

		reset = false;
		getting_more_messages = true;

		App.HTTP.read({
			url:App.WEB_ROOT+"/thread/"+Section.IN_THREAD_WITH_ID+"/messages",
			data:data,
			success:function(d, e, f){
				$.each(d.data.items, function(key, val){
					add_msg_to_chat(val, true);
				});
				$("#loading_messages").hide(App.TIME_FOR_HIDE);

				if(d.data.items.length >= 1){
					$("#load_more_messages_of_thread").show(App.TIME_FOR_SHOW);
				}
			},after:function(x, y, z){
				getting_more_messages = false;
			},log_ui_msg : false
		});
	});

	/*
		send message pressing button to send it
	*/

	var sending_message = false;

	$("#send_message").click(function(){
		if(sending_message){
			return;
		}

		var val = $("#txt_message").val().trim();
		$("#txt_message").val("").prop("disabled", true);
		$("#send_message").attr("disabled", true);
		sending_message = true;

		App.HTTP.create({
			url:App.WEB_ROOT+"/thread/"+Section.IN_THREAD_WITH_ID+"/message",
			data:{
				msg:val
			},success:function(d, e, f){
				add_msg_to_chat(d.data.item);
				$("#txt_message").val("").prop("disabled", false).focus();
				listenLastActivity("row_thread_"+Section.IN_THREAD_WITH_ID);
				INTERVAL_REQUEST_UNREAD = 1;
			},after:function(x, y, z){
				$("#send_message").attr("disabled", false);
				sending_message = false;
			},
			log_ui_msg : false
		});
	});

	/*
		add msg to interface
	*/
	function add_msg_to_chat(data, pos){
		//list_messages
		var mainbox = document.createElement("div");
		mainbox.className = "row";
		mainbox.style.border = "none";
		mainbox.style.padding = "1% 3% 1% 3%";

		var colimg = document.createElement("div");
		colimg.className = "col-sm-1";

		var aimg = document.createElement("a");
		aimg.href = "javascript:;";

		var img = document.createElement("img");
		img.src = App.IMG_PROFILE_FOLDER_ROUTE+data.user.profile_img;
		img.style.width = img.style.height = "50px";
		aimg.appendChild(img);
		colimg.appendChild(aimg);
		colimg.style.padding = colimg.style.margin = "0px";
		colimg.style.marginLeft = "22.5px";

		var colmsj = document.createElement("div");
		colmsj.className = "col-sm-10";
		colmsj.style.marginLeft = "22.5px";

		var divhd = document.createElement("div");
		divhd.style.width = "100%";

		if(data.user.id != null){
			divhd.innerHTML = "<a href = 'javascript:;' style = 'font-weight:bold;'>"+data.user.fullname+"</a><span class = 'pull-right' style = 'color:#BBBBBB;'>"+data.moment+"</span><br><p>"+data.message+"</p>";
		}else{
			divhd.innerHTML = "<strong style = 'color:black;'>"+data.user.fullname+"</strong><span class = 'pull-right' style = 'color:#BBBBBB;'>"+data.moment+"</span><br><p>"+data.message+"</p>";
		}

		colmsj.appendChild(divhd);

		mainbox.appendChild(colimg);
		mainbox.appendChild(colmsj);
		var rgb = [15, 15, 15, 15, 5, 5];
		mainbox.style.backgroundColor = "#BBBBBB";
		mainbox.style.display = "none";
		mainbox.setAttribute("data-id", data.id);
		mainbox.setAttribute("data-moment", data.moment);
		mainbox.setAttribute("data-user", data.user.id == Section.ID_USER?"me":"other");

		if((data.moment > LAST_MSG_DATE && data.user.id != Section.ID_USER) || LAST_MSG_DATE == null){
			LAST_MSG_DATE = data.moment;
			LAST_MSG_ID = data.id;
		}

		if(typeof pos == "undefined"){
			$("#list_messages").append(mainbox);
			var x = $("#thread_messages")[0];
			if(x.scrollTop >= x.scrollHeight - 600)
				setTimeout(function(){
					x.scrollTop = 10000000000;
				}, App.TIME_FOR_SHOW);
		}
		else{
			$("#list_messages").prepend(mainbox);
		}

		var i = 5, cond;
		var kt = setInterval(function(){
			i=5;

			do{
				if(rgb[i] < 15){
					rgb[i]++;
					cond = false;
				}else{
					rgb[i]=0;
					i--;
					cond = true;
				}
			}while(i >= 0 && cond);

			if(i >= 0){
				mainbox.style.backgroundColor = "#"+hexcolor(rgb);
			}else{
				mainbox.style.backgroundColor = "white";
				clearInterval(kt);
			}
		}, 10);

		$(mainbox).show(App.TIME_FOR_SHOW);
	}

	/*
		helper function to show transictional colour of a new message
	*/
	function hexcolor(rgb){
		var str = "";

		for(var i in rgb){
			str += (rgb[i] < 10?rgb[i]:["A", "B", "C", "D", "E", "F"][rgb[i]-10]);
		}

		return str;
	}

	function listenLastActivity(idrow){
		$("#list_items").prepend($("#"+idrow));
	}

	/*
		used when is adding new admins to the system, this function add a new dom element with
		the default data of a new admin
	*/
	this.add_row_new_admin = function(idlist, restrictions){
		var html = $("#db_users").html();//$("#"+idmodal).find("select[name='users']").html();

		var divadmin = document.createElement("div");
		divadmin.className = "col-sm-3";

		var selectadmin = document.createElement("select");
		selectadmin.className = "form-control";
		selectadmin.innerHTML = html;
		selectadmin.style.display = "inline";
		selectadmin.style.width = "80%";

		var remove = document.createElement("a");
		remove.innerHTML = "<strong>X</strong>";
		remove.href = "javascript:;";
		remove.style.marginRight = "10px";
		remove.onclick = function(){
			$(row).remove();
		}

		divadmin.appendChild(remove);
		divadmin.appendChild(selectadmin);

		if(typeof restrictions != "undefined"){
			$(selectadmin).children().each(function(){
				if(restrictions.indexOf(Number(this.value)) != -1){
					$(this).remove();
				}
			});
		}

		/*
		$("select[data-name='select_admin']").each(function(){
			$(selectadmin).find("option[value='"+$(this).val()+"']").remove();
		});*/

		//if($(selectadmin)[0].children.length > 0){
			selectadmin.setAttribute("data-name", "select_admin");

			var colpermiseslet = document.createElement("div");
			colpermiseslet.className = "col-sm-3";

			var checkbox_all_permises = document.createElement("input");
			checkbox_all_permises.type = "checkbox";
			checkbox_all_permises.checked = true;
			checkbox_all_permises.onchange = function(){
				if(this.checked){
					$(colpermises).hide(App.TIME_FOR_HIDE);
				}else{
					$(colpermises).show(App.TIME_FOR_SHOW);
				}
			}

			var span_all_permises = document.createElement("span");
			span_all_permises.innerHTML = "&nbsp;&nbsp;" + App.terms.str_give_default_permises;

			colpermiseslet.appendChild(checkbox_all_permises);
			colpermiseslet.appendChild(span_all_permises);

			var colpermises = document.createElement("div");
			colpermises.className = "col-sm-6";
			colpermises.style.display = "none";

			/********************************/
			//eliminar el hilo
			var checkbox_delete_thread = document.createElement("input");
			checkbox_delete_thread.type = "checkbox";
			checkbox_delete_thread.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_delete_thread.setAttribute("data-name", "delete_thread");
			var span_delete_thread = document.createElement("span");
			span_delete_thread.innerHTML = "&nbsp;&nbsp;" + App.terms.str_let_delete_thread;
			colpermises.appendChild(checkbox_delete_thread);
			colpermises.appendChild(span_delete_thread);
			colpermises.appendChild(document.createElement("br"));

			//editar titulo
			var checkbox_edit_title = document.createElement("input");
			checkbox_edit_title.type = "checkbox";
			checkbox_edit_title.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_edit_title.setAttribute("data-name", "edit_title");
			var span_edit_title = document.createElement("span");
			span_edit_title.innerHTML = "&nbsp;&nbsp;" + App.terms.str_edit_title;
			colpermises.appendChild(checkbox_edit_title);
			colpermises.appendChild(span_edit_title);
			colpermises.appendChild(document.createElement("br"));


			//editar descripcion
			var checkbox_edit_description = document.createElement("input");
			checkbox_edit_description.type = "checkbox";
			checkbox_edit_description.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_edit_description.setAttribute("data-name", "edit_description");
			var span_edit_description = document.createElement("span");
			span_edit_description.innerHTML = "&nbsp;&nbsp;" + App.terms.str_edit_description;
			colpermises.appendChild(checkbox_edit_description);
			colpermises.appendChild(span_edit_description);
			colpermises.appendChild(document.createElement("br"));


			//agregar administrador
			var checkbox_add_admin = document.createElement("input");
			checkbox_add_admin.type = "checkbox";
			checkbox_add_admin.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_add_admin.setAttribute("data-name", "add_admin");
			var span_add_admin = document.createElement("span");
			span_add_admin.innerHTML = "&nbsp;&nbsp;" + App.terms.str_add_admin;
			colpermises.appendChild(checkbox_add_admin);
			colpermises.appendChild(span_add_admin);
			colpermises.appendChild(document.createElement("br"));
			colpermises.appendChild(document.createElement("br"));

			//no eliminar administrador
			var rnd1 = String(Math.random()).substr(2);
			var checkbox_no_remove_admin = document.createElement("input");
			checkbox_no_remove_admin.type = "radio";
			checkbox_no_remove_admin.name = rnd1;
			checkbox_no_remove_admin.checked = true;//[true, false][Math.floor(Math.random()*2)];
			checkbox_no_remove_admin.setAttribute("data-name", "no_remove_admin");
			var span_no_remove_admin = document.createElement("span");
			span_no_remove_admin.innerHTML = "&nbsp;&nbsp;" + App.terms.str_no_remove_admin+"&nbsp;&nbsp;&nbsp;&nbsp;";
			colpermises.appendChild(checkbox_no_remove_admin);
			colpermises.appendChild(span_no_remove_admin);
			colpermises.appendChild(document.createElement("br"));


			//eliminar cualquier administrador
			var checkbox_remove_any_admin = document.createElement("input");
			checkbox_remove_any_admin.type = "radio";
			checkbox_remove_any_admin.name = rnd1;
			checkbox_remove_any_admin.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_remove_any_admin.setAttribute("data-name", "remove_any_admin");
			var span_remove_any_admin = document.createElement("span");
			span_remove_any_admin.innerHTML = "&nbsp;&nbsp;" + App.terms.str_remove_any_admin+"&nbsp;&nbsp;&nbsp;&nbsp;";
			colpermises.appendChild(checkbox_remove_any_admin);
			colpermises.appendChild(span_remove_any_admin);
			colpermises.appendChild(document.createElement("br"));

			//eliminar solo los que el haya agregado
			var checkbox_remove_specific_admin = document.createElement("input");
			checkbox_remove_specific_admin.type = "radio";
			checkbox_remove_specific_admin.name = rnd1;
			checkbox_remove_specific_admin.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_remove_specific_admin.setAttribute("data-name", "remove_specific_admin");
			var span_remove_specific_admin = document.createElement("span");
			span_remove_specific_admin.innerHTML = "&nbsp;&nbsp;" + App.terms.str_remove_specific_admin;
			colpermises.appendChild(checkbox_remove_specific_admin);
			colpermises.appendChild(span_remove_specific_admin);
			colpermises.appendChild(document.createElement("br"));
			colpermises.appendChild(document.createElement("br"));

			//no modificar permisos de admin
			var rnd2 = String(Math.random()).substr(2);
			var checkbox_no_set_permises_admin = document.createElement("input");
			checkbox_no_set_permises_admin.type = "radio";
			checkbox_no_set_permises_admin.name = rnd2;
			checkbox_no_set_permises_admin.checked = true;//[true, false][Math.floor(Math.random()*2)];
			checkbox_no_set_permises_admin.setAttribute("data-name", "no_set_permises_admin");
			var span_no_set_permises_admin = document.createElement("span");
			span_no_set_permises_admin.innerHTML = "&nbsp;&nbsp;" + App.terms.str_no_set_permises_admin+"&nbsp;&nbsp;&nbsp;&nbsp;";
			colpermises.appendChild(checkbox_no_set_permises_admin);
			colpermises.appendChild(span_no_set_permises_admin);
			colpermises.appendChild(document.createElement("br"));

			//modificar los permisos de cualquier admin
			var checkbox_set_permises_any_admin = document.createElement("input");
			checkbox_set_permises_any_admin.type = "radio";
			checkbox_set_permises_any_admin.name = rnd2;
			checkbox_set_permises_any_admin.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_set_permises_any_admin.setAttribute("data-name", "set_permises_any_admin");
			var span_set_permises_any_admin = document.createElement("span");
			span_set_permises_any_admin.innerHTML = "&nbsp;&nbsp;" + App.terms.str_set_permises_any_admin+"&nbsp;&nbsp;&nbsp;&nbsp;";
			colpermises.appendChild(checkbox_set_permises_any_admin);
			colpermises.appendChild(span_set_permises_any_admin);
			colpermises.appendChild(document.createElement("br"));

			//modificar los permisos de solo los que el haya agregado
			var checkbox_set_permises_specific_admin = document.createElement("input");
			checkbox_set_permises_specific_admin.type = "radio";
			checkbox_set_permises_specific_admin.name = rnd2;
			checkbox_set_permises_specific_admin.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_set_permises_specific_admin.setAttribute("data-name", "set_permises_specific_admin");
			var span_set_permises_specific_admin = document.createElement("span");
			span_set_permises_specific_admin.innerHTML = "&nbsp;&nbsp;" + App.terms.str_set_permises_specific_admin;
			colpermises.appendChild(checkbox_set_permises_specific_admin);
			colpermises.appendChild(span_set_permises_specific_admin);
			colpermises.appendChild(document.createElement("br"));
			colpermises.appendChild(document.createElement("br"));


			//cambiar privacidad
			var checkbox_set_privacy = document.createElement("input");
			checkbox_set_privacy.type = "checkbox";
			checkbox_set_privacy.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_set_privacy.setAttribute("data-name", "set_privacy");
			var span_set_privacy = document.createElement("span");
			span_set_privacy.innerHTML = "&nbsp;&nbsp;" + App.terms.str_set_privacy;
			colpermises.appendChild(checkbox_set_privacy);
			colpermises.appendChild(span_set_privacy);
			colpermises.appendChild(document.createElement("br"));


			//aceptar solicitudes de ingreso
			var checkbox_accept_join_requests = document.createElement("input");
			checkbox_accept_join_requests.type = "checkbox";
			checkbox_accept_join_requests.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_accept_join_requests.setAttribute("data-name", "accept_join_requests");
			var span_accept_join_requests = document.createElement("span");
			span_accept_join_requests.innerHTML = "&nbsp;&nbsp;" + App.terms.str_accept_join_requests;
			colpermises.appendChild(checkbox_accept_join_requests);
			colpermises.appendChild(span_accept_join_requests);
			colpermises.appendChild(document.createElement("br"));


			//rechazar solicitudes de ingreso
			var checkbox_reject_join_requests = document.createElement("input");
			checkbox_reject_join_requests.type = "checkbox";
			checkbox_reject_join_requests.checked = [true, false][Math.floor(Math.random()*2)];
			checkbox_reject_join_requests.setAttribute("data-name", "reject_join_requests");
			var span_reject_join_requests = document.createElement("span");
			span_reject_join_requests.innerHTML = "&nbsp;&nbsp;" + App.terms.str_reject_join_requests;
			colpermises.appendChild(checkbox_reject_join_requests);
			colpermises.appendChild(span_reject_join_requests);
			colpermises.appendChild(document.createElement("br"));
			/********************************/


			var row = document.createElement("div");
			row.className = "row";
			row.style.padding = "2%";
			row.appendChild(divadmin);
			row.appendChild(colpermiseslet);
			row.appendChild(colpermises);
			row.setAttribute("data-name", "admin");

			$("#"+idlist).append(row);
		/*
		}else{
			$(divadmin).remove();
		}
		*/
	}

	/*
		get info about an admin to be added sending the info to the server
	*/
	this.build_admins = function(modal){
		var arr = Array();
		$("#"+modal).find("div[data-name='admin']").each(function(){
			arr.push({
				permises : {
					delete_thread 				: $(this).find("input[data-name='delete_thread']"				).prop("checked"),
					edit_title 					: $(this).find("input[data-name='edit_title']"					).prop("checked"),
					edit_description 			: $(this).find("input[data-name='edit_description']"			).prop("checked"),
					add_admin 					: $(this).find("input[data-name='add_admin']"					).prop("checked"),
					no_remove_admin 			: $(this).find("input[data-name='no_remove_admin']"				).prop("checked"),
					remove_any_admin 			: $(this).find("input[data-name='remove_any_admin']"			).prop("checked"),
					remove_specific_admin 		: $(this).find("input[data-name='remove_specific_admin']"		).prop("checked"),
					no_set_permises_admin 		: $(this).find("input[data-name='no_set_permises_admin']"		).prop("checked"),
					set_permises_any_admin 		: $(this).find("input[data-name='set_permises_any_admin']"		).prop("checked"),
					set_permises_specific_admin : $(this).find("input[data-name='set_permises_specific_admin']"	).prop("checked"),
					set_privacy 				: $(this).find("input[data-name='set_privacy']"					).prop("checked"),
					accept_join_requests 		: $(this).find("input[data-name='accept_join_requests']"		).prop("checked"),
					reject_join_requests 		: $(this).find("input[data-name='reject_join_requests']"		).prop("checked")
				},
				id_user : $(this).find("select[data-name='select_admin']").val()
			});
		});
		return arr;
	}

	/*
		add admin to interface
	*/
	function add_li_to_list_admins(data){
		var li = document.createElement("li");
		li.id = "li_admin_"+data.id;
		var fullname_of_admin = document.createElement("span");
		fullname_of_admin.innerHTML = data.fullname;
		li.appendChild(fullname_of_admin);

		if(	(Section.threadsData[Section.IN_THREAD_WITH_ID]["__permises__"].remove_any_admin ||
			(Section.threadsData[Section.IN_THREAD_WITH_ID]["__permises__"].remove_specific_admin && data.added_by == Section.ID_USER)) &&
			data.id != Section.ID_USER){

			var aremove = document.createElement("a");
			aremove.innerHTML = "<strong>X</strong>";
			aremove.style.marginLeft = "10px";
			aremove.href = "javascript:;";
			aremove.onclick = function(){
				Section.ID_ADMIN_REMOVE = data.id;
				App.getView("thread-admin", "delete");
			}

			li.appendChild(aremove);
		}

		if(	(Section.threadsData[Section.IN_THREAD_WITH_ID]["__permises__"].set_permises_any_admin ||
			(Section.threadsData[Section.IN_THREAD_WITH_ID]["__permises__"].set_permises_specific_admin && data.added_by == Section.ID_USER)) &&
			data.id != Section.ID_USER){

			var asetpermises = document.createElement("a");
			asetpermises.innerHTML = "<strong><i class = 'fa fa-pencil'></i></strong>";
			asetpermises.style.marginLeft = "10px";
			asetpermises.href = "javascript:;";

			asetpermises.onclick = function(){
				Section.ID_ADMIN_UPDATE_PERMISES = data.id;
				App.getView("thread-admin-permises", "edit")
			}

			li.appendChild(asetpermises);
		}

		document.getElementById("list_admins_"+Section.IN_THREAD_WITH_ID).appendChild(li);
	}

	this.add_li_to_list_admins = add_li_to_list_admins;

	this.start = function(){
		Section.getItems();
	}
}