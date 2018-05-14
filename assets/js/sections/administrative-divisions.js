//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.ENDPOINT_ITEMS_INDEX = "/administrative-divisions";
	this.ENDPOINT_ITEMS_SEARCH = "/administrative-divisions-search";
	this.ENDPOINT_ITEM = "/administrative-division/";
	this.getItems = null;
	this.globalUpdateElements = [];
	this.arrayStatusForDelete = Array();
	this.ITEMS = {};

	/***********************************************************************************************************/

	this.add_item_form_to_dom = function(data, configs){
		var configs = App.cloneObject(Section.FLAGS.formConfigs);
		var row = App.startConfigRow(data, configs, function(){
			$.each(data.parents, function(k, v){data.parents[k]=Number(v);});
		});

		function watcher(tkn){
			if(typeof data != "undefined" && typeof Section.ITEMS[String(data.id)] != "undefined"){
				data = Section.ITEMS[String(data.id)];

				if(!($(row).attr('data-getting') == '1' || $(row).attr('data-updating') == '1' || $(row).attr('data-deleting') == '1')){
			    	if(	input_code.value.trim().length > 0 && input_name.value.trim().length > 0 && (input_code.value.trim() != data.code || input_name.value.trim() != data.name || $(input_description).val().trim() != data.description ||
			    		!App.flexible_equal_array($(parents).val(), data.parents) ||
			    		!App.flexible_equal_array($(select_statuses).val(), data.status))){
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
			var input_code = document.createElement("input");

			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
				input_code.className = "form-control";
				input_code.value = data.code;
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
			var input_description = document.createElement("textarea");

			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
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
			column and input for type parents of the current administrative division
		*/
			var column_parents = document.createElement("td");
			column_parents.align = "center";
			var parents;

			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
				parents = document.createElement("select");
				parents.multiple = "multiple";
				parents.setAttribute("data-select-administrative-divisions", "1");
				parents.setAttribute("data-select-type", "administrative-division");
				parents.disabled = !Section.permises["update"];
				var n = master_administrative_divisions.length;

				for(var i = 0; i < n; i++){
					var op = document.createElement("option");
					op.value = master_administrative_divisions[i].id;
					op.innerHTML = master_administrative_divisions[i].name;
					op.selected = data.parents.indexOf(Number(op.value)) != -1;
					parents.appendChild(op);
				}
			}else{
				parents = document.createElement("p");
				parents.innerHTML = App.stringify(data.parents, master_administrative_divisions);
			}

			column_parents.appendChild(parents);

		/*
			column and input for attribute 'status'
		*/
			var column_statuses = document.createElement("td");
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
			if the user has authorization, create controls for save changes
		*/
			if(Section.permises["update"]){
				if(App.FORMAT_EDIT_ITEMS == "inline"){
					var button_save_changes = App.createButtonSaveChanges(data, function(statuses){
					return {
						name:input_name.value.trim(),
						description:$(input_description).val().trim(),
						status:statuses,
						parents:$(parents).val(),
						lng:configs.lng,
						code:input_code.value.trim()
					};
					}, function(d){
						data.description = $(input_description).val().trim();
						data.name = input_name.value.trim();
						data.code = input_code.value.trim();
						$(parents).select2("destroy");

						$(parents).children().each(function(){
							$(this).prop("selected", d.data.parents_added.indexOf($(this).val()) != -1);
						});

						$(parents).select2();
						$(".select2-container").css("width", "100%");

						t = $(parents).val();

						if(t == null){
							t = Array();
						}

						for(x in t){
							t[x] = Number(t[x]);
						}

						data.parents = t;
						App.updateSelect2("administrative-division", "edit", data);
					}, select_statuses, data.row_selector);

					column_controls.appendChild(button_save_changes);
				}else{
					var button_get_edit_view = document.createElement("button");
					button_get_edit_view.className = "btn btn-primary";
					button_get_edit_view.innerHTML = "<i class = 'fa fa-edit'></i>";
					button_get_edit_view.onclick = function(){
						App.getView("administrative-division", "edit", function(){
							var DOM_form_code = $("#modal_administrative-division_edit").find("input[name='code']");
							DOM_form_code.val(data.code);

							var DOM_form_name = $("#modal_administrative-division_edit").find("input[name='name']");
							DOM_form_name.val(data.name);

							var DOM_form_description = $("#modal_administrative-division_edit").find("textarea[name='description']");
							DOM_form_description.val(data.description);

							var DOM_form_statuses = $("#modal_administrative-division_edit").find("select[name='status']");
							DOM_form_statuses.find("option").each(function(){
								this.selected = data.status.indexOf(Number(this.value)) != -1;
							});

							var DOM_form_parents = $("#modal_administrative-division_edit").find("select[name='parents']");
							DOM_form_parents.find("option").each(function(){
								this.selected = data.parents.indexOf(Number(this.value)) != -1;
							});

							DOM_form_statuses.select2();
							DOM_form_parents.select2();
							$(".select2-container").css("width", "100%");

							$("#form_administrative-division_edit").submit(function(e){
								e.preventDefault();

								var code = DOM_form_code.val().trim();
								var name = DOM_form_name.val().trim();
								var description = DOM_form_description.val().trim();
								var statuses = DOM_form_statuses.val();
								var parents_ = DOM_form_parents.val();

								if(statuses == null){
									statuses = Array();
								}

								if(parents_ == null){
									parents_ = Array();
								}

								if(code.length == 0 || name.length == 0){
									return;
								}

								App.LockScreen();
								App.ShowLoading(App.__GENERAL__.str_saving_changes);
								App.DOM_Disabling("#modal_administrative-division_edit");

								App.HTTP.update({
									url : App.WEB_ROOT + "/administrative-division/" + data.id,
									data : {
										lng : configs.lng,
										code : code, 
										name : name, 
										description : description,
										status : statuses,
										parents : parents_
									},
									success : function(d){
										data.code = input_code.innerHTML = code;
										data.name = input_name.innerHTML = name;
										data.description = input_description.innerHTML = description;
										data.status = statuses;
										$.each(data.status, function(k, v){data.status[k]=Number(v);});
										data.parents = d.data.parents_added;
										$.each(data.parents, function(k, v){data.parents[k]=Number(v);});

										select_statuses.innerHTML = App.stringify_statuses(statuses);
										parents.innerHTML = App.stringify(data.parents, master_administrative_divisions);

										$("#modal_administrative-division_edit").modal("hide");
									}, error : function(){
									}, after : function(){
										App.HideLoading();
										App.UnlockScreen();
										App.DOM_Enabling("#modal_administrative-division_edit");
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
			row.appendChild(column_parents);
			row.appendChild(column_statuses);
			row.appendChild(column_controls);

		/*
			styling and setting needed metadata
		*/
			row.setAttribute("data-status", JSON.stringify(data.status));
			row.setAttribute("data-save", "0");

		try{
			$(parents).select2();
		}catch(e){

		}

		App.finalConfigRow(row, data, select_statuses, watcher, function(){
			if(Section.FLAGS.onCreation){
				App.updateSelect2("administrative-division", "new", data);
			}
		});
	}

	var master_administrative_divisions = Array();

	this.start = function(){
		function getAdministrativeDivisions(){
			App.GetMasterData("administrative-divisions", function(d, e, f){
				master_administrative_divisions = d.data.items;
				App.HideLoading();
				Section.getItems(true);
			});
		}

		App.ShowLoading();
		getAdministrativeDivisions();
	}
}