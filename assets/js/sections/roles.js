//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.ENDPOINT_ITEMS_INDEX = "/roles";
	this.ENDPOINT_ITEMS_SEARCH = "/roles-search";
	this.ENDPOINT_ITEM = "/role/";
	this.getItems = null;
	this.globalUpdateElements = [];
	this.arrayStatusForDelete = Array();
	this.ITEMS = {};
	this.IDROLEEDITINGPERMISES = null;

	/***********************************************************************************************************/

	this.add_item_form_to_dom = function(data, configs){
		var configs = App.cloneObject(Section.FLAGS.formConfigs);
		var row = App.startConfigRow(data, configs);

		function watcher(tkn){
			if(!($(row).attr('data-getting') == '1' || $(row).attr('data-deleting') == '1' || $(row).attr('data-updating') == '1')){
		    	if( input_code.value.trim().length > 0 && input_name.value.trim().length > 0 && (input_code.value.trim() != data.code || input_name.value.trim() != data.name || $(input_description).val().trim() != data.description || !App.flexible_equal_array($(select_statuses).val(), data.status))){
		    		$(button_save_changes).show(App.TIME_FOR_SHOW);
		    		row.setAttribute("data-save", "1");
		    	}
		    	else{
		    		$(button_save_changes).hide(App.TIME_FOR_HIDE);
		    		row.setAttribute("data-save", "0");
		    	}
			}

			if(typeof Section != "undefined" && typeof Section.FLAGS.TOKEN != "undefined" && tkn == Section.FLAGS.TOKEN){
				setTimeout(function(){
					watcher(tkn);
				}, (Section.FLAGS.amountItems)*10);
			}
		}

		/*
			column for item id
		*/
			var column_id = document.createElement("td");
			column_id.align = "center";
			column_id.innerHTML = "<strong>"+data.id+"</strong>";

		/*
			column and controls for selector of item language visualization
		*/
			var column_language = App.createLanguageSelector(data, data.row_selector, configs, function(d){
				data = d.data.item;
				var modifier = App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]?"val":"html";

				$(input_description)[modifier](data.description);
				$(input_name)[modifier](data.name);
			});

		/*
			column and input for attribute 'code'
		*/
			var column_code = document.createElement("td");
			column_code.align = "center";
			var input_code;

			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
				input_code = document.createElement("input");
				input_code.className = "form-control";
				input_code.value = data.code;
				input_code.readOnly = !Section.permises["update"];
			}else{
				input_code = document.createElement("p");
				input_code.innerHTML = data.code;
			}

			column_code.appendChild(input_code);

		/*
			column and input for attribute 'name'
		*/
			var column_name = document.createElement("td");
			column_name.align = "center";
			var input_name;

			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
				input_name = document.createElement("input");
				input_name.type = "text";
				input_name.className = "form-control";
				input_name.value = data.name;
				input_name.readOnly = !Section.permises["update"];
			}else{
				input_name = document.createElement("p");
				input_name.innerHTML = data.name;
			}

			column_name.appendChild(input_name);

		/*
			column and input for attribute 'description'
		*/
			var column_description = document.createElement("td");
			column_description.align = "center";
			var input_description;

			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
				input_description = document.createElement("textarea");
				input_description.className = "form-control";
				input_description.innerHTML = data.description;
				input_description.readOnly = !Section.permises["update"];
			}else{
				input_description = document.createElement("p");
				input_description.innerHTML = data.description;
			}

			column_description.appendChild(input_description);

		/*
			column and input for attribute 'status'
		*/
			var column_statuses = document.createElement("td");
			column_statuses.align = "center";
			var select_statuses;

			column_statuses.style.display = App.isTrue(Section.CONFIGURATION.statuses.use)?"":"none";

			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
				select_statuses = App.createSelectStatuses(data, column_statuses);
			}else{
				select_statuses = document.createElement("p");
				select_statuses.innerHTML = App.stringify_statuses(data.status);
			}

			column_statuses.appendChild(select_statuses);

		/*
			column for item controls (updating, deleting, reading...)
		*/
		var column_controls = document.createElement("td");

		/*
			if the user has authorization, create controls for save changes and show controls to edit permises of the rol
		*/
			if(Section.permises["update"]){
				if(App.FORMAT_EDIT_ITEMS == "inline"){
					var button_save_changes = App.createButtonSaveChanges(data, function(statuses){
						return {
							name:input_name.value.trim(),
							description:$(input_description).val().trim(),
							status:statuses,
							lng:configs.lng,
							code:input_code.value.trim()
						};
					}, function(d){
						data.name = input_name.value.trim();
						data.description = $(input_description).val().trim();
						data.code = input_code.value.trim();
					}, select_statuses, data.row_selector);

					column_controls.appendChild(button_save_changes);
				}else{
					var button_get_edit_view = document.createElement("button");
					button_get_edit_view.className = "btn btn-primary";
					button_get_edit_view.innerHTML = "<i class = 'fa fa-edit'></i>";
					button_get_edit_view.onclick = function(){
						App.getView("role", "edit", function(){
							var DOM_form_code = $("#modal_role_edit").find("input[name='code']");
							DOM_form_code.val(data.code);

							var DOM_form_name = $("#modal_role_edit").find("input[name='name']");
							DOM_form_name.val(data.name);

							var DOM_form_description = $("#modal_role_edit").find("textarea[name='description']");
							DOM_form_description.val(data.description);

							var DOM_form_statuses = $("#modal_role_edit").find("select[name='status']");
							DOM_form_statuses.find("option").each(function(){
								this.selected = data.status.indexOf(Number(this.value)) != -1;
							});

							DOM_form_statuses.select2();
							$(".select2-container").css("width", "100%");

							$("#form_role_edit").submit(function(e){
								e.preventDefault();

								var code = DOM_form_code.val().trim();
								var name = DOM_form_name.val().trim();
								var description = DOM_form_description.val().trim();
								var statuses = DOM_form_statuses.val();

								if(statuses == null){
									statuses = Array();
								}

								if(name.length == 0 || code.length == 0){
									return;
								}

								App.LockScreen();
								App.ShowLoading(App.__GENERAL__.str_saving_changes);
								App.DOM_Disabling("#modal_role_edit");

								App.HTTP.update({
									url : App.WEB_ROOT + "/role/" + data.id,
									data : {
										lng : configs.lng,
										code : code, 
										name : name, 
										description : description,
										status : statuses
									},
									success : function(){
										data.code = input_code.innerHTML = code;
										data.name = input_name.innerHTML = name;
										data.description = input_description.innerHTML = description;
										data.status = statuses;
										$.each(data.status, function(k, v){data.status[k]=Number(v);});

										select_statuses.innerHTML = App.stringify_statuses(statuses);

										$("#modal_role_edit").modal("hide");
									}, error : function(){
									}, after : function(){
										App.HideLoading();
										App.UnlockScreen();
										App.DOM_Enabling("#modal_role_edit");
									}
								});
							});
						});
					}

					column_controls.appendChild(button_get_edit_view);
				}
			}

			var button_edit_permises = document.createElement("button");
			button_edit_permises.className  = "btn btn-info";
			button_edit_permises.innerHTML = Section.permises["update"]?"<i class = 'fa fa-eye'></i>":"<i class = 'fa fa-eye'></i>";
			button_edit_permises.title = Section.permises["update"]?App.terms.str_edit_permises:App.terms.str_see_permises;

			var gettingPermises = false;

			button_edit_permises.onclick  = function(e){
				if(gettingPermises){
					return;
				}

				Section.IDROLEEDITINGPERMISES = data.id;
				App.LockScreen();
				App.DOM_Disabling(data.row_selector);
				gettingPermises = true;
				App.ShowLoading(App.terms.str_requesting_role_permises);

				App.HTTP.read({
					url:App.WEB_ROOT+"/role/"+data.id+"/permises",
					success:function(d){
						App.getView("role-permises", "edit", function(){
							var ni = d.data.items.length;
							$("#modal_role_permises_update").find("input[type='checkbox']").prop("checked", false);
							var cant_for_sections = {};

							for(var i = 0; i < ni; i++){
								var sec = d.data.items[i].section,
									row = $("#rows_permises_update").find("tr[data-id='"+sec+"']")[0],
									nr = d.data.items[i].actions.length;

								$($(row).find("input")[0]).prop("checked", $(row).find("input").length - 1 == nr);

								for(var j = 0; j < nr; j++){
									$(row).find("input[data-type-checkbox='"+d.data.items[i].actions[j]+"']").prop("checked", true);

									if(typeof cant_for_sections[String(d.data.items[i].actions[j])] != "undefined")
										cant_for_sections[String(d.data.items[i].actions[j])] += 1;
									else
										cant_for_sections[String(d.data.items[i].actions[j])] = 1;

								}
							}

							var nl = $("#rows_permises_update").children().length;
							var cond = true;
							$.each(cant_for_sections, function(k, v){
								$("#modal_role_permises_update").find("thead").find("tr").find("input[data-type-checkbox='"+k+"']").prop("checked", v == nl);
								cond = cond && v == nl;
							})

							if(cond){
								$("#modal_role_permises_update").find("input[data-type-checkbox='all']").prop("checked", cond);
							}

							$("#modal_role_permises_update").find("input[type='checkbox']").prop("disabled", !Section.permises["update"]);

							if(Section.permises["update"]){
								$("#modal_role_permises_update").find("input[type='submit']").show(App.TIME_FOR_SHOW).attr("disabled", false);
							}else{
								$("#modal_role_permises_update").find("input[type='submit']").hide(App.TIME_FOR_HIDE).attr("disabled", true);
							}

							$("#modal_role_permises_update_title").html(data.name);
							$("#modal_role_permises_update").modal("show");
						})
					},after:function(x, y, z){
						App.UnlockScreen();
						gettingPermises = false;
						App.DOM_Enabling(data.row_selector);;
						App.HideLoading();
					}
				});
			}

			column_controls.appendChild(button_edit_permises);

		/*
			if the user has authorization, create controls for delete item
		*/
			if(Section.permises["delete"]){
				App.controls_for_delete_item(data, data.row_selector, column_controls);
			}

		/*
			appending columns to row of item
		*/
			row.appendChild(column_id);
			row.appendChild(column_language);
			row.appendChild(column_code);
			row.appendChild(column_name);
			row.appendChild(column_description);
			row.appendChild(column_statuses);
			row.appendChild(column_controls);

		/*
			styling and setting needed metadata
		*/
			row.setAttribute("data-status", JSON.stringify(data.status));
			row.setAttribute("data-save", "0");

		App.finalConfigRow(row, data, select_statuses, watcher);
	}

	this.start = function(){
		Section.getItems();
	}
}