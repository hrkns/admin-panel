//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.ENDPOINT_ITEMS_INDEX = "/users";
	this.ENDPOINT_ITEMS_SEARCH = "/users-search";
	this.ENDPOINT_ITEM = "/user/";
	this.getItems = null;
	this.arrayStatusForDelete = Array();
	this.functionAcceptRecoveringAccountRequest;
	this.ID_USER_EDITING;
	this.ITEMS = {};

	/***********************************************************************************************************/

	this.add_item_form_to_dom = function(data, configs){
		var configs = App.cloneObject(Section.FLAGS.formConfigs);
		var row = App.startConfigRow(data, configs);

		const CODE_OF_CONFIRMATION_STATUS = Number($("#see_with_status").find("option[data-code='SIGNUP_CONFIRMATION']").val());
		const CODE_OF_STATUS_USER_ENABLED = Number($("#see_with_status").find("option[data-code='ENABLED']").val());
		const CODE_OF_STATUS_RECOVERING_ACCOUNT = Number($("#see_with_status").find("option[data-code='ACCOUNT_RECOVERING']").val());
		const USER_IS_WAITING_FOR_MANUAL_CONFIRMATION = data.status.indexOf(CODE_OF_CONFIRMATION_STATUS) != -1;
		const USER_IS_WAITING_FOR_RECOVER_ACCOUNT = data.status.indexOf(CODE_OF_STATUS_RECOVERING_ACCOUNT) != -1;

		/*
			column for user id
		*/
			var column_id = document.createElement("td");
			column_id.align = "center";
			column_id.innerHTML = "<strong>"+data.id+"</strong>";

		/*
			column for user status
		*/
			var column_statuses = document.createElement("td");
			column_statuses.style.display = App.isTrue(Section.CONFIGURATION.statuses.use)?"":"none";
			column_statuses.align = "center";
			column_statuses.innerHTML = App.stringify_statuses(data.status);
			column_statuses.id = "statuses_"+(Section.FLAGS.onSearch?"search_":"")+data.id;

		/*
			column for user profile image
		*/
			var column_profile_img = document.createElement("td");
			column_profile_img.align = "center";
			var profile_img = document.createElement("img");
			profile_img.src = App.IMG_PROFILE_FOLDER_ROUTE+data.profile_img;
			profile_img.style.width = "50px";
			profile_img.style.height = "50px";
			profile_img.id = "preview_user_"+data.id+"_profile_img";
			column_profile_img.appendChild(profile_img);

		/*
			column for user fullname
		*/
			var column_fullname = document.createElement("td");
			column_fullname.align = "center";
			var text_fullname = document.createElement("p");
			text_fullname.innerHTML = "<strong>"+data.fullname+"</strong>";
			text_fullname.id = "preview_user_"+data.id+"_fullname";
			column_fullname.appendChild(text_fullname);

		/*
			column for user nick
		*/
			var column_nick = document.createElement("td");
			column_nick.align = "center";
			var text_nick = document.createElement("p");
			text_nick.innerHTML = "<strong>"+data.nick+"</strong>";
			text_nick.id = "preview_user_"+data.id+"_nick";
			column_nick.appendChild(text_nick);

		/*
			column for user status
		*/
			var column_email = document.createElement("td");
			column_email.align = "center";
			var text_email = document.createElement("p");
			text_email.innerHTML = "<strong>"+data.email+"</strong>";
			text_email.id = "preview_user_"+data.id+"_email";
			column_email.appendChild(text_email);

		/*
			column of controls
		*/
			var column_controls = document.createElement("td");
			column_controls.align = "center";

		/*
			if user is awaiting for confirmation, add the controls to accept it or reject it
		*/
			if(USER_IS_WAITING_FOR_MANUAL_CONFIRMATION){
				var aceptar_solicitud = document.createElement("button");
				aceptar_solicitud.className = "btn btn-success";
				aceptar_solicitud.innerHTML = App.terms.str_accept_ingress;
				var aceptando_solicitud = false;

				aceptar_solicitud.onclick = function(){
					if(aceptando_solicitud){
						return;
					}

					aceptando_solicitud = aceptar_solicitud.disabled = denegar_solicitud.disabled = true;
					App.DOM_Disabling(data.row_selector);;
					App.HTTP.post({
						url : App.WEB_ROOT + "/signup-confirmation/"+data.id,
						success : function(d, e, f){
							$(aceptar_solicitud).hide(App.TIME_FOR_HIDE, function(){ $(aceptar_solicitud).remove(); });
							$(denegar_solicitud).hide(App.TIME_FOR_HIDE, function(){ $(denegar_solicitud).remove(); });
							data.status = data.status.slice(0, data.status.indexOf(CODE_OF_CONFIRMATION_STATUS)).concat(data.status.slice(data.status.indexOf(CODE_OF_CONFIRMATION_STATUS) + 1));

							if(data.status.indexOf(CODE_OF_STATUS_USER_ENABLED) == -1){
								data.status.push(CODE_OF_STATUS_USER_ENABLED);
							}

							column_statuses.innerHTML = App.stringify_statuses(data.status);
							row.setAttribute("data-status", JSON.stringify(data.status));
							$(button_get_info_of_user).show(App.TIME_FOR_SHOW);

							if(App.CommonsElements(Section.arrayStatusForDelete, data.status)){
								$(($('[data-button-delete='+data.id+']')[0])).show(App.TIME_FOR_SHOW);
							}

							$(button_get_sessions_list).show(App.TIME_FOR_SHOW);
						}, error: function(x, y, z){
						}, after : function(){
							aceptando_solicitud = aceptar_solicitud.disabled = denegar_solicitud.disabled = false;
							App.DOM_Enabling(data.row_selector);;
						}
					});
				}

				var denegar_solicitud = document.createElement("button");
				denegar_solicitud.className = "btn btn-danger";
				denegar_solicitud.innerHTML = App.terms.str_deny_ingress;
				var denegando_solicitud = false;

				denegar_solicitud.onclick = function(){
					if(denegando_solicitud){
						return;
					}

					denegando_solicitud = aceptar_solicitud.disabled = denegar_solicitud.disabled = true;
					App.DOM_Disabling(data.row_selector);;
					App.HTTP.post({
						url : App.WEB_ROOT + "/signup-denegation/"+data.id,
						success : function(d, e, f){
							$(row).hide(App.TIME_FOR_HIDE, function(){ $(row).remove(); });
							$(button_get_info_of_user).show(App.TIME_FOR_SHOW);

							if(App.CommonsElements(Section.arrayStatusForDelete, data.status)){
								$(($('[data-button-delete='+data.id+']')[0])).show(App.TIME_FOR_SHOW);
							}

							$(button_get_sessions_list).show(App.TIME_FOR_SHOW);
						}, error: function(x, y, z){
						}, after : function(){
							denegando_solicitud = aceptar_solicitud.disabled = denegar_solicitud.disabled = false;
							App.DOM_Enabling(data.row_selector);;
						}
					});
				}

				column_controls.appendChild(aceptar_solicitud);
				column_controls.appendChild(denegar_solicitud);
			}

		/*
			if user is awaiting for confirmation, add the controls to help to recover the account
		*/
			if(USER_IS_WAITING_FOR_RECOVER_ACCOUNT){
				var aceptar_solicitud_recuperacion = document.createElement("button");
				aceptar_solicitud_recuperacion.className = "btn btn-success";
				aceptar_solicitud_recuperacion.innerHTML = App.terms.str_accept_account_recovering_request;
				aceptar_solicitud_recuperacion.onclick = function(){
					App.getView("user-account-recovering", "create", function(){
						var aceptando_solicitud_recuperacion = false;

						Section.functionAcceptRecoveringAccountRequest = function(){
							if(aceptando_solicitud_recuperacion){
								return;
							}

							var dataToSend = {
								type : $("input[name='radio_choice_recovering_account_mode']:checked").val()
							};

							if(dataToSend.type == "data"){
								dataToSend["password"] = $("#new_password_recover_account").val()
							}

							App.LockScreen();
							App.DOM_Disabling($("#modal_user-account-recovering_create"));
							aceptando_solicitud_recuperacion = true;
							App.ShowLoading(App.terms.str_accepting_recovering_account_request);

							App.HTTP.post({
								url : App.WEB_ROOT + "/account-recovering/"+data.id,
								data : dataToSend,
								success : function(d, e, f){
									$(aceptar_solicitud_recuperacion).hide(App.TIME_FOR_HIDE, function(){ $(aceptar_solicitud_recuperacion).remove(); });
									$(denegar_solicitud_recuperacion).hide(App.TIME_FOR_HIDE, function(){ $(denegar_solicitud_recuperacion).remove(); });
									data.status = data.status.slice(0, data.status.indexOf(CODE_OF_STATUS_RECOVERING_ACCOUNT)).concat(data.status.slice(data.status.indexOf(CODE_OF_STATUS_RECOVERING_ACCOUNT) + 1));
									column_statuses.innerHTML = App.stringify_statuses(data.status);
									row.setAttribute("data-status", JSON.stringify(data.status));
									$("#modal_user-account-recovering_create").hide();
								}, error: function(x, y, z){
									App.Alert(x.message);
								}, after : function(){
									App.UnlockScreen();
									App.DOM_Enabling($("#modal_user-account-recovering_create"));
									aceptando_solicitud_recuperacion = false;
									App.HideLoading();
								}, log_ui_msg : false
							});
						}

						$("#modal_user-account-recovering_create").find("input[value='link']").prop("checked", true);
						$("#new_password_recover_account").hide().val("");
						$("#modal_user-account-recovering_create").modal("show");
					})
				}

				var denegar_solicitud_recuperacion = document.createElement("button");
				denegar_solicitud_recuperacion.className = "btn btn-danger";
				denegar_solicitud_recuperacion.innerHTML = App.terms.str_deny_account_recovering_request;
				var denegando_solicitud_recuperacion = false;

				denegar_solicitud_recuperacion.onclick = function(){
					if(denegando_solicitud_recuperacion){
						return;
					}

					denegando_solicitud_recuperacion = denegar_solicitud_recuperacion.disabled = true;
					App.DOM_Disabling(data.row_selector);;
					App.HTTP.post({
						url : App.WEB_ROOT + "/account-recovering-denegation/"+data.id,
						success : function(d, e, f){
							$(aceptar_solicitud_recuperacion).hide(App.TIME_FOR_HIDE, function(){ $(aceptar_solicitud_recuperacion).remove(); });
							$(denegar_solicitud_recuperacion).hide(App.TIME_FOR_HIDE, function(){ $(denegar_solicitud_recuperacion).remove(); });
							data.status = data.status.slice(0, data.status.indexOf(CODE_OF_STATUS_RECOVERING_ACCOUNT)).concat(data.status.slice(data.status.indexOf(CODE_OF_STATUS_RECOVERING_ACCOUNT) + 1));
							column_statuses.innerHTML = App.stringify_statuses(data.status);
							row.setAttribute("data-status", JSON.stringify(data.status));
						}, error: function(x, y, z){
						}, after : function(){
							denegando_solicitud_recuperacion = denegar_solicitud_recuperacion.disabled = false;
							App.DOM_Enabling(data.row_selector);;
						}
					});
				}

				column_controls.appendChild(aceptar_solicitud_recuperacion);
				column_controls.appendChild(denegar_solicitud_recuperacion);
			}

		/*
			button to get the complete info of the user, just to read it or to edit it, it depens of the user permises
		*/
			var button_get_info_of_user = document.createElement("button");
			button_get_info_of_user.innerHTML = Section.permises["update"]?"<i class = 'fa fa-edit'></i>":"<i class = 'fa fa-eye'></i>";
			button_get_info_of_user.title = Section.permises["update"]?App.terms.str_edit:App.terms.str_see_information;
			button_get_info_of_user.className = "btn btn-info";
			button_get_info_of_user.style.display = USER_IS_WAITING_FOR_MANUAL_CONFIRMATION?"none":"";
			var gettingInfo = false;

			button_get_info_of_user.onclick = function(e){
				if(gettingInfo){
					return;
				}

				e.preventDefault();
				App.LockScreen();
				gettingInfo = true;
				App.ShowLoading(App.terms.str_requesting_info);

				App.HTTP.read({
					url:App.WEB_ROOT+"/user/"+data.id+"/info",
					success:function(d, e, f){
						App.getView("user", "edit", function(){
							$("#modal_user_edit").find("input[name='fullname']").val(d.data.item.fullname);
							$("#modal_user_edit").find("input[name='nick']").val(d.data.item.nick);
							$("#modal_user_edit").find("input[name='email']").val(d.data.item.email);
							$("#modal_user_edit").find("input[name='password']").val("");

							if(d.data.item.profile_img.indexOf("default") != -1){
								$("#profile_img_edit_user").attr("src", "assets/images/profile/default.jpg");
								$("#remove_profile_img_edit_user").hide(App.TIME_FOR_HIDE);
							}else{
								$("#profile_img_edit_user").attr("src", App.IMG_PROFILE_FOLDER_ROUTE+d.data.item.profile_img);
								$("#remove_profile_img_edit_user").show(App.TIME_FOR_SHOW);
							}

							try{
								$("#modal_user_edit").find("select[name='status']").select2("destroy");
							}catch(e){
							}

							$("#modal_user_edit").find("select[name='role']").val(d.data.item.role);

							$("#modal_user_edit").find("select[name='status']").html($("#see_with_status").html());
							$("#modal_user_edit").find("select[name='status']").children().each(function(){
								$(this).prop("selected", false);
							});

							$.each(d.data.item.status, function(k, v){
								$("#modal_user_edit").find("select[name='status']").find("option[value='"+v+"']").prop("selected", true);
							});

							$("#modal_user_edit").find("select[name='status']").select2();
							$(".select2-container").css("width", "100%");
							$("#list_communication_routes_edit_user").empty();
							$("#list_roles_in_organization_edit_user").empty();

							$.each(d.data.item.media, function(k, v){
								Section.add_row_communication_route("edit", v);
							});

							$("#modal_user_edit").modal("show");
							Section.ID_USER_EDITING =data.id;
						});
					},after:function(x, y, z){
						App.UnlockScreen();
						gettingInfo = false;
						App.HideLoading();
					}
				});
			}

			column_controls.appendChild(button_get_info_of_user);

		/*
			if the user has authorization, create controls for delete user
		*/
			if(Section.permises["delete"]){
				App.controls_for_delete_item(data, data.row_selector, column_controls);
			}

		/*
			button to get the complete list of sessions
		*/
			var button_get_sessions_list = document.createElement("button");
			button_get_sessions_list.className = "btn";
			button_get_sessions_list.innerHTML = App.terms.str_tracking_activities_button;
			button_get_sessions_list.style.display = USER_IS_WAITING_FOR_MANUAL_CONFIRMATION?"none":"";

			var getting_sessions_list = false;

			button_get_sessions_list.onclick = function(){
				if(getting_sessions_list){
					return;
				}

				App.LockScreen();
				getting_sessions_list = true;
				App.ShowLoading(App.terms.str_requesting_sessions_list);
				Section.FULLNAME_USER = data.fullname;

				App.HTTP.read({
					url : App.WEB_ROOT + "/user/" + data.id + "/sessions",
					success : function(d, e, f){
						App.getView("user-sessions", "read", function(){
							$("#activities_tracking_user_fullname").html($("#preview_user_"+data.id+"_fullname").text());
							$("#list_sessions").empty();
							Section.ID_USER_CHECKING = data.id;
							Actions["user-sessions-read"].addItems(d.data.items)
						});
					},error : function(x, y, z){
						App.Alert(x.message);
					},after : function(){
						App.UnlockScreen();
						getting_sessions_list = false;
						App.HideLoading();
					},log_ui_msg : false
				});
			}

			column_controls.appendChild(button_get_sessions_list);

		/*
			appending columns to row of item
		*/
			row.appendChild(column_id);
			row.appendChild(column_statuses);
			row.appendChild(column_profile_img);
			row.appendChild(column_fullname);
			row.appendChild(column_nick);
			row.appendChild(column_email);
			row.appendChild(column_controls);

		/*
			styling and setting needed metadata
		*/
			row.setAttribute("data-status", JSON.stringify(data.status));
			row.id = "row_user_"+(Section.FLAGS.onSearch?"_search":"")+"_"+data.id;

		App.finalConfigRow(row, data);
	}

	this.add_row_communication_route = function(mode, data){
		var tr = document.createElement("tr");
		var column_profile_img = document.createElement("td");
		var select = document.createElement("select");

		select.className = "form-control";
		select.innerHTML = generateMediaSelect();
		select.disabled = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			select.value = data.code;
		}

		column_profile_img.appendChild(select);

		var column_fullname = document.createElement("td");
		var value = document.createElement("input");

		value.className = "form-control";
		value.readOnly = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			value.value = data.value;
		}

		column_fullname.appendChild(value);

		var column_nick = document.createElement("td");
		var btnrm = document.createElement("button");

		btnrm.innerHTML = App.terms.str_remove;
		btnrm.className = "btn btn-danger";

		btnrm.onclick = function(e){
			e.preventDefault();
			$(tr).remove();
		}

		if((mode == "edit" && Section.permises["update"]) || mode != "edit"){
			column_nick.appendChild(btnrm);
		}

		tr.appendChild(column_profile_img);
		tr.appendChild(column_fullname);
		tr.appendChild(column_nick);
		$("#list_communication_routes_"+mode+"_user").append(tr);
	}

	var generateMediaSelect = function(){
		var str = "";

		$.each(master_media, function(k, item){
			str += '<option value = "'+item.id+'" data-code = "'+item.code+'">'+item.name+'</option>';
		});

		return str;
	}

	var master_media;

	this.start = function(){
		function getMedia(){
			App.GetMasterData("media", function(d, e, f){
				master_media = d.data.items;
				App.HideLoading();
				Section.getItems(true);
			});
		}

		App.ShowLoading();
		getMedia();
	}
}