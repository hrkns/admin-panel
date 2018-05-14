//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.ENDPOINT_ITEMS_INDEX = "/statuses";
	this.ENDPOINT_ITEMS_SEARCH = "/statuses-search";
	this.ENDPOINT_ITEM = "/status/";
	this.getItems = null;
	this.globalUpdateElements = [];
	this.arrayStatusForDelete = Array();
	this.ITEMS = {};

	/***********************************************************************************************************/

	this.add_item_form_to_dom = function(data, configs){
		var configs = App.cloneObject(Section.FLAGS.formConfigs);
		var row = App.startConfigRow(data, configs);

		/***************************************************************/

		function watcher(tkn){
			if(typeof data != "undefined" && typeof Section != "undefined" && typeof Section.ITEMS[String(data.id)] != "undefined"){
				data = Section.ITEMS[String(data.id)];
				if(!($(row).attr('data-getting') == '1' || $(row).attr('data-deleting') == '1' || $(row).attr('data-updating') == '1')){
			    	if 	((input_name.value.trim().length > 0 && input_code.value.trim().length > 0 && (input_name.value.trim() != data.name || input_code.value.trim() != data.code || $(input_description).val().trim() != data.description)) ||
			    	 	(checkbox_status_for_show_by_default.checked && String(data.show_default) == "0") || (!checkbox_status_for_show_by_default.checked && String(data.show_default) == "1") ||
			    	 	(checkbox_status_for_show_item.checked && String(data.show_item) == "0") || (!checkbox_status_for_show_item.checked && String(data.show_item) == "1") ||
			    	 	(checkbox_status_for_delete.checked && String(data.for_delete) == "0") || (!checkbox_status_for_delete.checked && String(data.for_delete) == "1") ||
			    	 	!App.flexible_equal_array($(select_statuses).val(), data.status)) {

			    		$(button_save_changes).show(App.TIME_FOR_SHOW);
			    		$(row).attr("data-save", "1");
			    	}
			    	else{
			    		$(button_save_changes).hide(App.TIME_FOR_HIDE);
			    		$(row).attr("data-save", "0");
			    	}
				}

				if(typeof Section != "undefined" && typeof Section.FLAGS.TOKEN != "undefined" && tkn == Section.FLAGS.TOKEN){
					setTimeout(function(){
						watcher(tkn);
					}, (Section.FLAGS.amountItems)*10);
				}
			}
		}

		/***************************************************************/

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
				input_description.value = data.description;
				input_description.readOnly = !Section.permises["update"];
			}else{
				input_description = document.createElement("p");
				input_description.innerHTML = data.description;
			}

			column_description.appendChild(input_description);

		/*
			column and input for attribute 'status', and checkboxes flags
		*/
			var column_statuses = document.createElement("td");
			column_statuses.align = "center";
			column_statuses.style.display = App.isTrue(Section.CONFIGURATION.statuses.use)?"":"none";
			var select_statuses;

			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
				select_statuses = App.createSelectStatuses(data, column_statuses);
				select_statuses.setAttribute("data-select-type", "status");
			}else{
				select_statuses = document.createElement("p");
				select_statuses.innerHTML = App.stringify_statuses(data.status);
			}

			column_statuses.appendChild(select_statuses);
		/*
			column and controls por status configurations
		*/
			var column_configurations = document.createElement("td");
			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
				/*
					checkbox and text to set/unset the status like "show_by_default"
				*/
					var checkbox_status_for_show_by_default = document.createElement("input");
					checkbox_status_for_show_by_default.type = "checkbox";
					checkbox_status_for_show_by_default.checked = data.show_default == "1";
					checkbox_status_for_show_by_default.disabled = !Section.permises["update"];

					var text_checkbox_status_for_show_by_default = document.createElement("span");
					text_checkbox_status_for_show_by_default.innerHTML = "&nbsp;"+App.terms.str_text_checkbox_1+"<br>";

				/*
					checkbox and text to set/unset the status like "show_item"
				*/
					var checkbox_status_for_show_item = document.createElement("input");
					checkbox_status_for_show_item.type = "checkbox";
					checkbox_status_for_show_item.checked = data.show_item == "1";
					checkbox_status_for_show_item.disabled = !Section.permises["update"];

					var text_checkbox_status_for_show_item = document.createElement("span");
					text_checkbox_status_for_show_item.innerHTML = "&nbsp;"+App.terms.str_text_checkbox_2+"<br>";

				/*
					checkbox and text to set/unset the status like "for_delete"
				*/
					var checkbox_status_for_delete = document.createElement("input");
					checkbox_status_for_delete.type = "checkbox";
					checkbox_status_for_delete.checked = data.for_delete == "1";
					checkbox_status_for_delete.disabled = !Section.permises["update"];

					var text_checkbox_status_for_delete = document.createElement("span");
					text_checkbox_status_for_delete.innerHTML = "&nbsp;"+App.terms.str_text_checkbox_3+"<br>";

					column_configurations.appendChild(document.createElement("br"));
					column_configurations.appendChild(document.createElement("br"));
					column_configurations.appendChild(checkbox_status_for_show_by_default);
					column_configurations.appendChild(text_checkbox_status_for_show_by_default);
					column_configurations.appendChild(document.createElement("br"));
					column_configurations.appendChild(checkbox_status_for_show_item);
					column_configurations.appendChild(text_checkbox_status_for_show_item);
					column_configurations.appendChild(document.createElement("br"));
					column_configurations.appendChild(checkbox_status_for_delete);
					column_configurations.appendChild(text_checkbox_status_for_delete);
					column_configurations.appendChild(document.createElement("br"));
					column_configurations.appendChild(document.createElement("br"));
			}else{
				var text_checkbox_status_for_show_by_default = document.createElement("span");
				text_checkbox_status_for_show_by_default.innerHTML = "-&nbsp;"+App.terms.str_text_checkbox_1+"<br><br>";
				text_checkbox_status_for_show_by_default.style.display = App.isTrue(data.show_default)?"":"none";

				var text_checkbox_status_for_show_item = document.createElement("span");
				text_checkbox_status_for_show_item.innerHTML = "-&nbsp;"+App.terms.str_text_checkbox_2+"<br><br>";
				text_checkbox_status_for_show_item.style.display = App.isTrue(data.show_item)?"":"none";

				var text_checkbox_status_for_delete = document.createElement("span");
				text_checkbox_status_for_delete.innerHTML = "-&nbsp;"+App.terms.str_text_checkbox_3+"<br><br>";
				text_checkbox_status_for_delete.style.display = App.isTrue(data.for_delete)?"":"none";

				column_configurations.appendChild(text_checkbox_status_for_show_by_default);
				column_configurations.appendChild(text_checkbox_status_for_show_item);
				column_configurations.appendChild(text_checkbox_status_for_delete);
			}

		/*
			column for item controls (updating, deleting, reading...)
		*/
		var column_controls = document.createElement("td");

		/*
			if the user has authorization, create controls for save changes
		*/
			if(Section.permises["update"]){
				if(App.FORMAT_EDIT_ITEMS == "inline"){
					var button_save_changes = App.createButtonSaveChanges(data, function(statuses){
						return {
							code:input_code.value.trim(),
							name:input_name.value.trim(),
							description:$(input_description).val().trim(),
							status:statuses,
							for_delete:$(checkbox_status_for_delete).is(":checked")?"1":"0",
							show_default:$(checkbox_status_for_show_by_default).is(":checked")?"1":"0",
							show_item:$(checkbox_status_for_show_item).is(":checked")?"1":"0",
							lng:configs.lng
						};
					}, function(d){
						data.description = $(input_description).val().trim();
						data.name = input_name.value.trim();
						data.code = input_code.value.trim();
						data.show_item = $(checkbox_status_for_show_item).is(":checked")?"1":"0";
						data.show_default = $(checkbox_status_for_show_by_default).is(":checked")?"1":"0";
						data.for_delete = $(checkbox_status_for_delete).is(":checked")?"1":"0";
						App.updateSelect2("status", "edit", data);
					}, select_statuses, data.row_selector);

					column_controls.appendChild(button_save_changes);
				}else{
					var button_get_edit_view = document.createElement("button");
					button_get_edit_view.className = "btn btn-primary";
					button_get_edit_view.innerHTML = "<i class = 'fa fa-edit'></i>";
					button_get_edit_view.onclick = function(){
						App.getView("status", "edit", function(){
							var DOM_form_code = $("#modal_status_edit").find("input[name='code']");
							DOM_form_code.val(data.code);

							var DOM_form_name = $("#modal_status_edit").find("input[name='name']");
							DOM_form_name.val(data.name);

							var DOM_form_description = $("#modal_status_edit").find("textarea[name='description']");
							DOM_form_description.val(data.description);

							var DOM_form_statuses = $("#modal_status_edit").find("select[name='status']");
							DOM_form_statuses.find("option").each(function(){
								this.selected = data.status.indexOf(Number(this.value)) != -1;
							});

							DOM_form_statuses.select2();
							$(".select2-container").css("width", "100%");

							var DOM_form_show_by_default = $("#modal_status_edit").find("input[name='show_default']");
							DOM_form_show_by_default.prop("checked", App.isTrue(data.show_default));

							var DOM_form_show_item = $("#modal_status_edit").find("input[name='show_item']");
							DOM_form_show_item.prop("checked", App.isTrue(data.show_item));

							var DOM_form_for_delete = $("#modal_status_edit").find("input[name='for_delete']");
							DOM_form_for_delete.prop("checked", App.isTrue(data.for_delete));

							$("#form_status_edit").submit(function(e){
								e.preventDefault();

								var code = DOM_form_code.val().trim();
								var name = DOM_form_name.val().trim();
								var description = DOM_form_description.val().trim();
								var statuses = DOM_form_statuses.val();
								var show_default = DOM_form_show_by_default.prop("checked");
								var for_delete = DOM_form_for_delete.prop("checked");
								var show_item = DOM_form_show_item.prop("checked");

								if(statuses == null){
									statuses = Array();
								}

								if(code.length == 0 || name.length == 0){
									return;
								}

								App.LockScreen();
								App.ShowLoading(App.__GENERAL__.str_saving_changes);
								App.DOM_Disabling("#modal_status_edit");

								App.HTTP.update({
									url : App.WEB_ROOT + "/status/" + data.id,
									data : {
										lng : configs.lng,
										code : code, 
										name : name, 
										description : description,
										status : statuses,
										for_delete : for_delete?1:0,
										show_default : show_default?1:0,
										show_item : show_item?1:0
									},
									success : function(){
										data.code = input_code.innerHTML = code;
										data.name = input_name.innerHTML = name;
										data.description = input_description.innerHTML = description;
										data.status = statuses;
										$.each(data.status, function(k, v){data.status[k]=Number(v);});
										select_statuses.innerHTML = App.stringify_statuses(statuses);

										data.show_default = show_default;
										data.for_delete = for_delete;
										data.show_item = show_item;

										text_checkbox_status_for_show_by_default.style.display = show_default?"":"none";
										text_checkbox_status_for_show_item.style.display = show_item?"":"none";
										text_checkbox_status_for_delete.style.display = for_delete?"":"none";

										$("#modal_status_edit").modal("hide");
									}, error : function(){
									}, after : function(){
										App.HideLoading();
										App.UnlockScreen();
										App.DOM_Enabling("#modal_status_edit");
									}
								});
							});
						});
					}

					column_controls.appendChild(button_get_edit_view);
				}
			}

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
			row.appendChild(column_configurations);
			row.appendChild(column_controls);

		/*
			styling and setting needed metadata
		*/
			row.setAttribute("data-status", JSON.stringify(data.status));
			row.setAttribute("data-save", "0");

		App.finalConfigRow(row, data, select_statuses, watcher, function(){
			if(Section.FLAGS.onCreation){
				App.updateSelect2("status", "new", data);
			}
		});
	}

	this.start = function(){
		Section.getItems();
	}
}