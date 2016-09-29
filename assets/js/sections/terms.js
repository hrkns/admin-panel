//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.avoid_input_text_search = true;
	this.idSection;
	this.ENDPOINT_ITEM = "/term/";
	this.globalUpdateElements = [];
	this.arrayStatusForDelete = [];
	this.avoid_get_items_changed_language = true;
	this.ITEMS = {};

	/***********************************************************************************************************/

	this.getTerms = function(t){
		Section.idSection = t.getAttribute("data-id");
		getItems();
	}

	function getItems(flag){
		if(Section.FLAGS.GETTING_ITEMS){
			return;
		}

		Section.FLAGS.GETTING_ITEMS = true;
		App.LockScreen();
		App.ShowLoading(App.terms.str_requesting_terms);

		App.HTTP.read({
			url:App.WEB_ROOT+"/section/"+Section.idSection+"/terms",
			data:{
				language:Section.FLAGS.formConfigs.lng
			},
			success:function(d, e, f){
				App.getView("terms", "read", function(){
					$("#show_managing_controls").trigger("click");
					$("#list_items").empty();

					$.each(d.data.items, function(key, val){
						Section.add_item_form_to_dom(val);
						Section.FLAGS.amountItems++;
					});

					if(typeof flag == "undefined"){
						try{
							$("#sections_for_clone_terms").select2("destroy");
						}catch(e){
						}

						$("#sections_for_clone_terms").empty();
						$("#tree_sections").find("*[data-id]").each(function(){
							$("#sections_for_clone_terms").append("<option value = '"+$(this).attr("data-id")+"'>"+$(this).html().trim()+"</option>");
						});
						$("#sections_for_clone_terms").select2();
						$(".select2-container").css("width", "100%");
						setTimeout(function(){
							$("#modal_terms_read_title").html($("#title_section_"+Section.idSection).html().trim());
						}, 2000);
						$("#modal_terms_read").modal("show");
					}
				})
			},error:function(x, y, z){
			},after:function(){
				setTimeout(function(){
					App.UnlockScreen();
					Section.FLAGS.GETTING_ITEMS = false;
				}, 1000);
				App.HideLoading();
			},log_ui_msg : false
		});
	}

	/***********************************************************************************************************/

	this.add_item_form_to_dom = function(data, configs){
		var configs = App.cloneObject(Section.FLAGS.formConfigs);
		var row = App.startConfigRow(data, configs);

		function watcher(tkn){
			if(!($(row).attr('data-getting') == '1' || $(row).attr('data-deleting') == '1' || $(row).attr('data-updating') == '1')){
		    	if(input_code.value.trim().length > 0 && $(input_value).val().trim().length > 0 && ($(input_value).val().trim() != data.value || input_code.value.trim() != data.code || input_name.value.trim() != data.name || $(input_description).val().trim() != data.description || !App.flexible_equal_array($(select_statuses).val(), data.status))){
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
				}, (Section.FLAGS.amountItems)*App.CHECK_FOR_SAVING);
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

				$(input_description)[modifier].val(data.description);
				$(input_name)[modifier](data.name);
				$(input_value)[modifier](data.value);
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
			column and input for attribute 'value'
		*/
			var column_value = document.createElement("td");
			column_value.align = "center";
			var input_value;

			if(App.FORMAT_EDIT_ITEMS == "inline" && Section.permises["update"]){
				input_value = document.createElement("textarea");
				input_value.className = "form-control";
				input_value.innerHTML = data.value;
				input_value.readOnly = !Section.permises["update"];
			}else{
				input_value = document.createElement("p");
				input_value.innerHTML = data.value;
			}

			column_value.appendChild(input_value);

		/*
			column and input for attribute 'status'
		*/
			var column_statuses = document.createElement("td");
			column_statuses.align = "center";
			var select_statuse;

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
							code:input_code.value.trim(),
							lng:configs.lng,
							value:$(input_value).val().trim()
						}
					}, function(d){
						data.description = $(input_description).val().trim();
						data.value = $(input_value).val().trim();
						data.name = input_name.value.trim();
						data.code = input_code.value.trim();
					}, select_statuses, data.row_selector);

					column_controls.appendChild(button_save_changes);
				}else{
					var button_get_edit_view = document.createElement("button");
					button_get_edit_view.className = "btn btn-primary";
					button_get_edit_view.innerHTML = "<i class = 'fa fa-edit'></i>";
					button_get_edit_view.onclick = function(){
						App.getView("term", "edit", function(){
							var DOM_form_code = $("#modal_term_edit").find("input[name='code']");
							DOM_form_code.val(data.code);

							var DOM_form_name = $("#modal_term_edit").find("input[name='name']");
							DOM_form_name.val(data.name);

							var DOM_form_description = $("#modal_term_edit").find("textarea[name='description']");
							DOM_form_description.val(data.description);

							var DOM_form_value = $("#modal_term_edit").find("input[name='value']");
							DOM_form_value.val(data.value);

							var DOM_form_statuses = $("#modal_term_edit").find("select[name='status']");
							DOM_form_statuses.find("option").each(function(){
								this.selected = data.status.indexOf(Number(this.value)) != -1;
							});

							DOM_form_statuses.select2();
							$(".select2-container").css("width", "100%");

							$("#form_term_edit").submit(function(e){
								e.preventDefault();

								var code = DOM_form_code.val().trim();
								var name = DOM_form_name.val().trim();
								var description = DOM_form_description.val().trim();
								var statuses = DOM_form_statuses.val();
								var value = DOM_form_value.val().trim();

								if(statuses == null){
									statuses = Array();
								}

								if(code.length == 0){
									return;
								}

								App.LockScreen();
								App.ShowLoading(App.__GENERAL__.str_saving_changes);
								App.DOM_Disabling("#modal_term_edit");

								App.HTTP.update({
									url : App.WEB_ROOT + "/term/" + data.id,
									data : {
										lng : configs.lng,
										code : code, 
										name : name, 
										description : description,
										value : value, 
										status : statuses
									},
									success : function(){
										data.code = input_code.innerHTML = code;
										data.name = input_name.innerHTML = name;
										data.description = input_description.innerHTML = description;
										data.value = input_value.innerHTML = value;
										data.status = statuses;
										$.each(data.status, function(k, v){data.status[k]=Number(v);});

										select_statuses.innerHTML = App.stringify_statuses(statuses);

										$("#modal_term_edit").modal("hide");
									}, error : function(){
									}, after : function(){
										App.HideLoading();
										App.UnlockScreen();
										App.DOM_Enabling("#modal_term_edit");
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
			row.appendChild(column_value);
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

	/***********************************************************************************************************/

	var cond_to_export_dictionary = false,
		getting_dictionary = false,
		previous_text_red_button;

	$("#show_checkboxes_export_dictionary").click(function(){
		if(cond_to_export_dictionary){
			if(getting_dictionary){
				return;
			}

			var sections = [];

			$("[data-type-checkbox]:checked").each(function(){
				sections.push($(this).attr("data-id-section"));
			});

			getting_dictionary = sections.length > 0;

			if(getting_dictionary){
				App.LockScreen();
				App.ShowLoading(App.terms.str_exporting_dictionary);

				App.HTTP.get({
					url : App.WEB_ROOT + "/dictionary",
					data : {
						sections : sections
					},
					success : function(d, e, f){
						$("#show_checkboxes_export_dictionary").html(previous_text_red_button);
						$("[data-type-checkbox]").hide(App.TIME_FOR_HIDE);
						$("[data-type-checkbox]").prop("checked", true);
						cond_to_export_dictionary = false;
						window.location = App.WEB_ROOT + "/dictionary/"+d.data.hash;
					},error : function(x, y, z){

					},after : function(){
						App.UnlockScreen();
						App.HideLoading();
						getting_dictionary = false;
					}
				});
			}
		}else{
			$("[data-type-checkbox]").show(App.TIME_FOR_SHOW);
			previous_text_red_button = $(this).html();
			$(this).html(App.terms.str_select_sections_and_click_again_to_download);
			cond_to_export_dictionary = true;
		}
	});

	$("#btn_import_dict").click(function(){
		App.getView("dictionary", "import");
	});
}