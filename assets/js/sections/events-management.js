//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.ENDPOINT_ITEMS_INDEX = "/events";
	this.ENDPOINT_ITEMS_SEARCH = "/events-search";
	this.ENDPOINT_ITEM = "/event/";
	this.getItems = null;
	this.globalUpdateElements = [];
	this.arrayStatusForDelete = Array();
	this.ITEMS = {};

	/***********************************************************************************************************/

	this.add_item_form_to_dom = function(data){
		var configs = App.cloneObject(Section.FLAGS.formConfigs);
		var row = App.startConfigRow(data, configs);

		function watcher(tkn){
			if(!($(row).attr('data-getting') == '1' || $(row).attr('data-deleting') == '1' || $(row).attr('data-updating') == '1')){
		    	if(input_name.value.trim().length > 0 && (input_name.value.trim() != data.name || $(input_description).val().trim() != data.description || !App.flexible_equal_array($(select_statuses).val(), data.status))){
		    		$(button_save_changes).show(App.TIME_FOR_SHOW);
		    		$(row).attr("data-save", "1");
		    	}else{
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
			column and input/text for attribute 'name'
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
			column and input for attribute 'status'
		*/
			var column_statuses = document.createElement("td");
			column_statuses.align = "center";
			column_statuses.style.display = App.isTrue(Section.CONFIGURATION.statuses.use)?"":"none";
			var select_statuses;

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
			column_controls.align = "center";

		/*
			if the user has authorization, create controls for save changes
		*/
			if(Section.permises["update"]){
				if(App.FORMAT_EDIT_ITEMS == "inline"){
					var button_save_changes = App.createButtonSaveChanges(data, function(statuses){
						return {
							name:input_name.value.trim(),
							description:$(input_description).val().trim(),
							status:statuses,
							lng:configs.lng
						};
					}, function(d){
						data.description = $(input_description).val().trim();
						data.name = input_name.value.trim();
					}, select_statuses, data.row_selector);

					column_controls.appendChild(button_save_changes);
				}else{
					var button_get_edit_view = document.createElement("button");
					button_get_edit_view.className = "btn btn-primary";
					button_get_edit_view.innerHTML = "<i class = 'fa fa-edit'></i>";
					button_get_edit_view.onclick = function(){
						App.getView("event", "edit", function(){
							var DOM_form_name = $("#modal_event_edit").find("input[name='name']");
							DOM_form_name.val(data.name);

							var DOM_form_description = $("#modal_event_edit").find("textarea[name='description']");
							DOM_form_description.val(data.description);

							var DOM_form_statuses = $("#modal_event_edit").find("select[name='status']");
							DOM_form_statuses.find("option").each(function(){
								this.selected = data.status.indexOf(Number(this.value)) != -1;
							});

							DOM_form_statuses.select2();
							$(".select2-container").css("width", "100%");

							$("#modal_event_edit").submit(function(e){
								e.preventDefault();

								var name = DOM_form_name.val().trim();
								var description = DOM_form_description.val().trim();
								var statuses = DOM_form_statuses.val();

								if(statuses == null){
									statuses = Array();
								}

								if(name.length == 0){
									return;
								}

								App.LockScreen();
								App.ShowLoading(App.__GENERAL__.str_saving_changes);

								App.HTTP.update({
									url : App.WEB_ROOT + "/event/" + data.id,
									data : {
										lng : configs.lng,
										name : name, 
										description : description,
										status : statuses
									},
									success : function(){
										input_name.innerHTML = name;
										input_description.innerHTML = description;
										select_statuses.innerHTML = App.stringify_statuses(statuses);

										$("#modal_event_edit").modal("hide");
									}, error : function(){
									}, after : function(){
										App.HideLoading();
										App.UnlockScreen();
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
			row.appendChild(column_name);
			row.appendChild(column_description);
			row.appendChild(column_statuses);
			row.appendChild(column_controls);

		/*
			styling and setting needed metadata
		*/
			row.style.marginBottom = "5%";
			row.setAttribute("data-status", JSON.stringify(data.status));
			row.setAttribute("data-save", "0");

		App.finalConfigRow(row, data, select_statuses, watcher);
	}

	this.start = function(){
		Section.getItems();
	}
}