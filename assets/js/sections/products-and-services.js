//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.ENDPOINT_ITEMS_INDEX = "/products";
	this.ENDPOINT_ITEMS_SEARCH = "/products-search";
	this.ENDPOINT_ITEM = "/product/";
	this.getItems = null;
	this.globalUpdateElements = [];
	this.arrayStatusForDelete = Array();
	this.ITEMS = {};
	this.ID_PRODUCT_EDITING;
	var dataProducts = {};
	var cantFields = 0;

	/***********************************************************************************************************/

	this.add_item_form_to_dom = function(data, configs){
		var configs = App.cloneObject(Section.FLAGS.formConfigs);
		var row = App.startConfigRow(data, configs, function(){
			dataProducts[String(data.id)] = data;
		});

		function watcher(tkn){
			if(!($(row).attr('data-getting') == '1' || $(row).attr('data-deleting') == '1' || $(row).attr('data-updating') == '1')){
		    	if(input_code.value.trim().length > 0 && input_name.value.trim().length > 0 && (input_code.value.trim() != data.code || input_name.value.trim() != data.name || $(input_description).val().trim() != data.description || !App.flexible_equal_array($(select_statuses).val(), data.status))){
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
			if the user has authorization, create controls for save changes and change structure of product/service
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
					}, select_statuses, data.row_selector);

					column_controls.appendChild(button_save_changes);
				}else{
					var button_get_edit_view = document.createElement("button");
					button_get_edit_view.className = "btn btn-primary";
					button_get_edit_view.innerHTML = "<i class = 'fa fa-edit'></i>";
					button_get_edit_view.onclick = function(){
						App.getView("product", "edit", function(){
							var DOM_form_code = $("#modal_product_edit").find("input[name='code']");
							DOM_form_code.val(data.code);

							var DOM_form_name = $("#modal_product_edit").find("input[name='name']");
							DOM_form_name.val(data.name);

							var DOM_form_description = $("#modal_product_edit").find("textarea[name='description']");
							DOM_form_description.val(data.description);

							var DOM_form_statuses = $("#modal_product_edit").find("select[name='status']");
							DOM_form_statuses.find("option").each(function(){
								this.selected = data.status.indexOf(Number(this.value)) != -1;
							});

							DOM_form_statuses.select2();
							$(".select2-container").css("width", "100%");

							$("#form_product_edit").submit(function(e){
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
								App.DOM_Disabling("#modal_product_edit");

								App.HTTP.update({
									url : App.WEB_ROOT + "/product/" + data.id,
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

										$("#modal_product_edit").modal("hide");
									}, error : function(){
									}, after : function(){
										App.HideLoading();
										App.UnlockScreen();
										App.DOM_Enabling("#modal_product_edit");
									}
								});
							});
						});
					}

					column_controls.appendChild(button_get_edit_view);
				}

				var button_edit_structure = document.createElement("button");
				button_edit_structure.className = "btn btn-warning";
				button_edit_structure.innerHTML = "<i class = 'ion-android-menu'></i>";
				button_edit_structure.title = Section.permises["update"]?App.terms.str_edit_structure:App.terms.str_see_structure;

				var getting_estructure = false;

				button_edit_structure.onclick = function(e){
					e.preventDefault();

					if(getting_estructure){
						return;
					}

					getting_estructure = button_edit_structure.disabled = ($('[data-button-delete='+data.id+']')[0]).disabled = true;
					App.DOM_Disabling(data.row_selector);;
					App.ShowLoading(App.terms.str_getting_product_structure);

					App.HTTP.read({
						url : App.WEB_ROOT + "/product/" + data.id + "/structure",
						success : function(d, e, f){
							App.getView("product-structure", "edit", function(){
								$("#list_fields_edit_product").empty();
								Section.ID_PRODUCT_EDITING = data.id;
								$("#modal_product_update_title").html(data.name);
								$("#modal_product-structure_edit").modal("show");
								$.each(d.data.items, function(k, v){
									Section.addField("edit", v);
								});
							});
						}, error: function(x, y, z){
						}, after : function(){
							getting_estructure = button_edit_structure.disabled = ($('[data-button-delete='+data.id+']')[0]).disabled = false;
							App.DOM_Enabling(data.row_selector);;
							App.HideLoading();
						}
					});
				}

				column_controls.appendChild(button_edit_structure);
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
			row.appendChild(column_controls);

		/*
			styling and setting needed metadata
		*/
			row.style.marginBottom = "5%";
			row.setAttribute("data-status", JSON.stringify(data.status));
			row.setAttribute("data-save", "0");

		App.finalConfigRow(row, data, select_statuses, watcher);
	}

	this.addField = function(mode, data){
		var col = document.createElement("td");
		var code = document.createElement("input");

		code.className = "form-control";
		code.placeholder = App.terms.str_code;
		code.readOnly = mode == "edit" && !Section.permises["update"];
		code.name = "code";

		if(typeof data != "undefined"){
			code.value = data.code;
		}

		var name = document.createElement("input");

		name.className = "form-control";
		name.placeholder = App.terms.str_name;
		name.readOnly = mode == "edit" && !Section.permises["update"];
		name.name = "name";

		if(typeof data != "undefined"){
			name.value = data.name;
		}

		var description = document.createElement("textarea");

		description.className = "form-control";
		description.placeholder = App.terms.str_description;
		description.readOnly = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			description.value = data.description;
		}

		var btnremove = document.createElement("button")

		btnremove.className = "btn btn-danger";
		btnremove.innerHTML = App.terms.str_remove;

		btnremove.onclick = function(e){
			e.preventDefault();
			$("#list_instances_"+mode+"_product td[data-pos='"+col.getAttribute("data-pos")+"']").remove();
			$(col).hide(App.TIME_FOR_HIDE, function(){ $(col).remove(); });
		}

		var inputid = document.createElement("input");
		inputid.type = "hidden";
		inputid.value = typeof data != "undefined" && data.id?data.id:-1;
		inputid.name = "id";

		col.appendChild(inputid);
		col.appendChild(code);
		col.appendChild(name);
		col.appendChild(description);

		if((mode == "edit" && Section.permises["update"]) || mode != "edit"){
			col.appendChild(btnremove);
		}

		col.setAttribute("data-pos", cantFields++);

		col.style.display = "none";
		$("#list_fields_"+mode+"_product").append(col);
		$(col).show(App.TIME_FOR_SHOW);

		$("#list_instances_"+mode+"_product").children().each(function(){
			var colval = document.createElement("td");
			colval.setAttribute("data-pos", $(col).attr("data-pos"));
			var val = document.createElement("input");
			val.className = "form-control";
			colval.appendChild(val);
			$(this).append(colval);
		});
	}

	this.addInstance = function(mode, data){
		var tr = document.createElement("tr");
		var colrm = document.createElement("td");
		var rm = document.createElement("button");

		rm.className = "btn btn-danger";
		rm.innerHTML = App.terms.str_remove;

		rm.onclick = function(e){
			e.preventDefault();
			$(tr).remove();
		}

		if((mode == "edit" && Section.permises["update"]) || mode != "edit"){
			colrm.appendChild(rm);
		}

		tr.appendChild(colrm);

		var cond = false,
			pos = 0;

		$("#list_fields_"+mode+"_product").children().each(function(){
			if(cond || mode == "edit"){
				var colval = document.createElement("td");
				var val = document.createElement("input");

				colval.setAttribute("data-pos", $(this).attr("data-pos"));
				val.className = "form-control";
				val.readOnly = mode == "edit" && !Section.permises["update"];

				if(typeof data != "undefined"){
					val.value = data[pos++];
				}

				colval.appendChild(val);
				tr.appendChild(colval);
			}

			cond=true;
		})

		$("#list_instances_"+mode+"_product").append(tr);
	}

	this.start = function(){
		Section.getItems();
	}
}