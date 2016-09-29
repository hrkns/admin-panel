//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.ENDPOINT_ITEMS_INDEX = "/sounds";
	this.ENDPOINT_ITEMS_SEARCH = "/sounds-search";
	this.ENDPOINT_ITEM = "/sound/";
	this.getItems = null;
	this.globalUpdateElements = [];
	this.arrayStatusForDelete = Array();
	this.ITEMS = {};

	/***********************************************************************************************************/

	this.add_item_form_to_dom = function(data, configs){
		var configs = App.cloneObject(Section.FLAGS.formConfigs);
		var row = App.startConfigRow(data, configs);

		function watcher(tkn){
			if(!($(row).attr('data-getting') == '1' || $(row).attr('data-deleting') == '1' || $(row).attr('data-updating') == '1')){
		    	if(input_code.value.trim().length > 0 && input_name.value.trim().length > 0 && (input_code.value.trim() != data.code || input_name.value.trim() != data.name || $(input_description).val().trim() != data.description || file_audio.files.length > 0 || !App.flexible_equal_array($(select_statuses).val(), data.status))){
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
			column and input for attribute 'code'
		*/
			var column_code = document.createElement("td");
			column_code.align = "center";
			var input_code;

			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
				input_code = document.createElement("input");
				input_code.value = data.code;
				input_code.className = "form-control";
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
			column and input for attribute 'file' (the audio element)
		*/
			var column_audio = document.createElement("td");
			column_audio.align = "center";
			var audio_element = document.createElement("audio");
			audio_element.setAttribute("controls", "");

			var source_audio_element = document.createElement("source");
			source_audio_element.src = App.WEB_ROOT + "/assets/audio/notifications/" + data.file;
			source_audio_element.id = "audio_"+data.id;

			var message_not_supported_audio_element = document.createElement("span");
			message_not_supported_audio_element.innerHTML = App.terms.str_not_supported;
			audio_element.appendChild(source_audio_element);
			audio_element.appendChild(message_not_supported_audio_element);

			var file_audio = document.createElement("input");
			file_audio.type = "file";
			file_audio.className = "form-control";
			file_audio.onchange = function(){
				$(button_remove_file_audio).show(App.TIME_FOR_SHOW);
			}

			var button_remove_file_audio = document.createElement("button");
			button_remove_file_audio.className = "btn btn-danger";
			button_remove_file_audio.innerHTML = App.terms.str_remove;
			button_remove_file_audio.style.display = "none";
			button_remove_file_audio.onclick = function(){
				$(file_audio).val("");
				$(button_remove_file_audio).hide(App.TIME_FOR_HIDE);
			}

			column_audio.appendChild(audio_element);

			if(Section.permises.update && App.FORMAT_EDIT_ITEMS == "inline"){
				column_audio.appendChild(file_audio);
				column_audio.appendChild(button_remove_file_audio);
			}

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
							code:input_code.value.trim(),
							lng:configs.lng
						};
					}, function(d){
						data.description = $(input_description).val().trim();
						data.name = input_name.value.trim();
						data.code = input_code.value.trim();

						if(file_audio.files.length > 0){
							var formFile = new FormData();
							formFile.append("audio", file_audio.files[0]);
							formFile.append("updating", true);
							$.ajax({
								url: App.WEB_ROOT+"/sound/"+data.id+"/file",
								type: 'POST',
								data: formFile,
								processData: false, 
								contentType: false,
								dataType : "JSON",
								success: function(file){
									updating = button_save_changes.disabled = ($('[data-button-delete='+data.id+']')[0]).disabled = false;
									App.DOM_Enabling(data.row_selector);;

									source_audio_element.src = App.WEB_ROOT + "/assets/audio/notifications/" + file.filename;
									audio_element.load();
									$(button_remove_file_audio).hide(App.TIME_FOR_HIDE);
									$(button_save_changes).hide(App.TIME_FOR_HIDE);
									$(file_audio).val("");
								},
								error:function(x, y, z){
									updating = button_save_changes.disabled = ($('[data-button-delete='+data.id+']')[0]).disabled = false;
									App.DOM_Enabling(data.row_selector);;
									$("#update_all_search,#update_all").attr("disabled", false);
								}
							});
						}else{
							$(button_save_changes).hide(App.TIME_FOR_HIDE);
							updating = button_save_changes.disabled = ($('[data-button-delete='+data.id+']')[0]).disabled = false;
						}
					}, select_statuses, data.row_selector);

					column_controls.appendChild(button_save_changes);
				}else{
					var button_get_edit_view = document.createElement("button");
					button_get_edit_view.className = "btn btn-primary";
					button_get_edit_view.innerHTML = "<i class = 'fa fa-edit'></i>";
					button_get_edit_view.onclick = function(){
						App.getView("sound", "edit", function(){
							var DOM_form_code = $("#modal_sound_edit").find("input[name='code']");
							DOM_form_code.val(data.code);

							var DOM_form_name = $("#modal_sound_edit").find("input[name='name']");
							DOM_form_name.val(data.name);

							var DOM_form_description = $("#modal_sound_edit").find("textarea[name='description']");
							DOM_form_description.val(data.description);

							var DOM_form_statuses = $("#modal_sound_edit").find("select[name='status']");
							DOM_form_statuses.find("option").each(function(){
								this.selected = data.status.indexOf(Number(this.value)) != -1;
							});

							DOM_form_statuses.select2();
							$(".select2-container").css("width", "100%");

							$("#form_sound_edit").submit(function(e){
								e.preventDefault();

								var code = DOM_form_code.val().trim();
								var name = DOM_form_name.val().trim();
								var description = DOM_form_description.val().trim();
								var statuses = DOM_form_statuses.val();
								var fileaudio = $("#modal_sound_edit").find("input[name='file']")[0].files[0];

								if(statuses == null){
									statuses = Array();
								}

								if(code.length == 0 || name.length == 0){
									return;
								}

								App.LockScreen();
								App.ShowLoading(App.__GENERAL__.str_saving_changes);
								App.DOM_Disabling("#modal_sound_edit");

								App.HTTP.update({
									url : App.WEB_ROOT + "/sound/" + data.id,
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

										if(fileaudio){
											var formFile = new FormData();
											formFile.append("audio", fileaudio);
											formFile.append("updating", true);
											$.ajax({
												url: App.WEB_ROOT+"/sound/"+data.id+"/file",
												type: 'POST',
												data: formFile,
												processData: false, 
												contentType: false,
												dataType : "JSON",
												success: function(file){
													App.HideLoading();
													App.UnlockScreen();
													App.DOM_Enabling("#modal_sound_edit");
													source_audio_element.src = App.WEB_ROOT + "/assets/audio/notifications/" + file.filename;
													audio_element.load();
													$(fileaudio).val("");
													$("#modal_sound_edit").modal("hide");
												},
												error:function(x, y, z){
													App.HideLoading();
													App.UnlockScreen();
													App.DOM_Enabling("#modal_sound_edit");
													$("#modal_sound_edit").modal("hide");
												}
											});
										}else{
											$("#modal_sound_edit").modal("hide");
											App.HideLoading();
											App.UnlockScreen();
											App.DOM_Enabling("#modal_sound_edit");
										}

									}, error : function(){
										App.HideLoading();
										App.UnlockScreen();
										App.DOM_Enabling("#modal_sound_edit");
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
			row.appendChild(column_audio);
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