//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.ENDPOINT_ITEMS_INDEX = "/customers";
	this.ENDPOINT_ITEMS_SEARCH = "/customers-search";
	this.ENDPOINT_ITEM = "/customer/";
	this.getItems = null;
	this.arrayStatusForDelete = Array();
	this.ID_CUSTOMER_EDITING;
	this.ITEMS = {};

	/***********************************************************************************************************/

	this.add_item_form_to_dom = function(data, configs){
		var configs = App.cloneObject(Section.FLAGS.formConfigs);
		var row = App.startConfigRow(data, configs);

		/*
			column and input for attribute 'id'
		*/
			var column_id = document.createElement("td");
			column_id.align = "center";
			column_id.innerHTML = "<strong>"+data.id+"</strong>";

		/*
			column and input for attribute 'status'
		*/
			var column_statuses = document.createElement("td");
			column_statuses.style.display = App.isTrue(Section.CONFIGURATION.statuses.use)?"":"none";
			column_statuses.align = "center";
			column_statuses.innerHTML = App.stringify_statuses(data.status);
			column_statuses.id = "statuses_"+(Section.FLAGS.onSearch?"search_":"")+data.id;

		/*
			column and input for attribute 'image'
		*/
			var column_customer_img = document.createElement("td");
			column_customer_img.align = "center";
			var customer_image = document.createElement("img");
			customer_image.src = App.IMG_CLIENT_FOLDER_ROUTE+data.img;
			customer_image.style.width = "50px";
			customer_image.style.height = "50px";
			customer_image.id = "preview_client_"+data.id+"_img";
			column_customer_img.appendChild(customer_image);

		/*
			column and input for attribute 'name'
		*/
			var column_name = document.createElement("td");
			column_name.align = "center";
			var text_name = document.createElement("p");
			text_name.innerHTML = "<strong>"+data.name+"</strong>";
			text_name.id = "preview_client_"+data.id+"_name";
			column_name.appendChild(text_name);

		/*
			column for item controls (updating, deleting, reading...)
		*/
			var column_controls = document.createElement("td");
			column_controls.align = "center";

		/*
			button to get the complete info of the customer, just to read it or to edit it, it depends of the user permises
		*/
			var button_get_info = document.createElement("button");
			button_get_info.className = "btn btn-info";
			button_get_info.innerHTML = Section.permises["update"]?"<i class = 'fa fa-edit'></i>":"<i class = 'fa fa-eye'></i>";
			button_get_info.title = Section.permises["update"]?App.terms.str_edit:App.terms.str_see_information;

			var gettingInfo = false;

			button_get_info.onclick = function(e){
				e.preventDefault();

				if(gettingInfo){
					return;
				}

				gettingInfo = true;
				App.DOM_Disabling(data.row_selector);;
				App.LockScreen();
				App.ShowLoading(App.terms.str_requesting_customer_info);

				App.HTTP.read({
					url:App.WEB_ROOT+"/customer/"+data.id+"/info",
					success:function(d, e, f){
						App.getView("customer", "edit", function(){
							$("#modal_customer_edit").find("input[name='name']").val(d.data.item.name);
							$("#modal_customer_edit").find("input[name='nick']").val(d.data.item.nick);

							if(d.data.item.img.indexOf("default") != -1){
								$("#img_edit_client").attr("src", "assets/images/client/default.jpg");
								$("#remove_img_edit_client").hide(App.TIME_FOR_HIDE);
							}else{
								$("#img_edit_client").attr("src", App.IMG_CLIENT_FOLDER_ROUTE+d.data.item.img);
								$("#remove_img_edit_client").show(App.TIME_FOR_SHOW);
							}

							try{
								$("#modal_customer_edit").find("select[name='status']").select2("destroy");
							}catch(e){
							}

							$("#modal_customer_edit").find("select[name='status']").html($("#see_with_status").html());

							$("#modal_customer_edit").find("select[name='status']").children().each(function(){
								$(this).prop("selected", false);
							});

							$.each(d.data.item.status, function(k, v){
								$("#modal_customer_edit").find("select[name='status']").find("option[value='"+v+"']").prop("selected", true);
							});

							$("#modal_customer_edit").find("select[name='status']").select2();
							$(".select2-container").css("width", "100%");
							$("#list_communication_routes_edit_client").empty();
							$("#list_addresses_edit_client").empty();
							$("#list_real_ids_edit_client").empty();

							$.each(d.data.item.media, function(k, v){
								Section.add_row_communication_route("edit", v);
							});

							$.each(d.data.item.documentation, function(k, v){
								Section.add_row_real_id("edit", v);
							});

							$.each(d.data.item.addresses, function(k, v){
								Section.add_row_address("edit", v);
							});

							$("#modal_customer_edit").modal("show");
							Section.ID_CUSTOMER_EDITING =data.id;
						});
					},after:function(x, y, z){
						gettingInfo = false;
						App.UnlockScreen();
						App.DOM_Enabling(data.row_selector);;
						App.HideLoading();
					}
				});
			}

			column_controls.appendChild(button_get_info);

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
			row.appendChild(column_statuses);
			row.appendChild(column_customer_img);
			row.appendChild(column_name);
			row.appendChild(column_controls);

		/*
			styling and setting needed metadata
		*/
			row.setAttribute("data-status", JSON.stringify(data.status));
			row.id = "row_client_"+(Section.FLAGS.onSearch?"_search":"")+"_"+data.id;

		App.finalConfigRow(row, data);
	}

	this.add_row_communication_route = function(mode, data){
		var tr = document.createElement("tr");
		var column_customer_img = document.createElement("td");
		var select = document.createElement("select");

		select.className = "form-control";
		select.innerHTML = Section.generateMediaSelect();
		select.disabled = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			select.value = data.code;
		}

		column_customer_img.appendChild(select);

		var column_name = document.createElement("td");
		var value = document.createElement("input");

		value.className = "form-control";
		value.readOnly = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			value.value = data.value;
		}

		column_name.appendChild(value);

		var td3 = document.createElement("td");
		var btnrm = document.createElement("button");

		btnrm.innerHTML = App.terms.str_delete;
		btnrm.className = "btn btn-danger";
		btnrm.onclick = function(e){
			e.preventDefault();
			$(tr).hide(App.TIME_FOR_HIDE, function(){ $(tr).remove(); });
		}

		if((mode == "edit" && Section.permises["update"]) || mode != "edit"){
			td3.appendChild(btnrm);
		}

		tr.appendChild(column_customer_img);
		tr.appendChild(column_name);
		tr.appendChild(td3);
		tr.style.display = "none";
		$("#list_communication_routes_"+mode+"_client").append(tr);
		$(tr).show(App.TIME_FOR_SHOW);
	}

	this.add_row_address = function(mode, data){
		var tr = document.createElement("tr");
		var column_name = document.createElement("td");
		var address = document.createElement("textarea");

		address.className = "form-control";
		address.readOnly = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			address.value = data;
		}

		column_name.appendChild(address);

		var td3 = document.createElement("td");
		var btnrm = document.createElement("button");

		btnrm.innerHTML = App.terms.str_delete;
		btnrm.className = "btn btn-danger";
		btnrm.onclick = function(e){
			e.preventDefault();
			$(tr).hide(App.TIME_FOR_HIDE, function(){ $(tr).remove(); });
		}

		if((mode == "edit" && Section.permises["update"]) || mode != "edit"){
			td3.appendChild(btnrm);
		}

		tr.appendChild(column_name);
		tr.appendChild(td3);
		tr.style.display = "none";
		$("#list_addresses_"+mode+"_client").append(tr);
		$(tr).show(App.TIME_FOR_SHOW);
	}

	this.add_row_real_id = function(mode, data){
		var tr = document.createElement("tr");
		var column_customer_img = document.createElement("td");
		var select = document.createElement("select");

		select.className = "form-control";
		select.innerHTML = Section.generateMediaDocs();
		select.disabled = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			select.value = data.code;
		}

		column_customer_img.appendChild(select);

		var column_name = document.createElement("td");
		var value = document.createElement("input");

		value.className = "form-control";
		value.readOnly = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			value.value = data.value;
		}

		column_name.appendChild(value);

		var td3 = document.createElement("td");
		var btnrm = document.createElement("button");

		btnrm.innerHTML = App.terms.str_delete;
		btnrm.className = "btn btn-danger";
		btnrm.onclick = function(e){
			e.preventDefault();
			$(tr).hide(App.TIME_FOR_HIDE, function(){ $(tr).remove(); });
		}

		if((mode == "edit" && Section.permises["update"]) || mode != "edit"){
			td3.appendChild(btnrm);
		}

		tr.appendChild(column_customer_img);
		tr.appendChild(column_name);
		tr.appendChild(td3);
		tr.style.display = "none";
		$("#list_real_ids_"+mode+"_client").append(tr);
		$(tr).show(App.TIME_FOR_SHOW)
	}

	this.generateMediaSelect = function(){
		var str = "";

		$.each(master_media, function(k, media){
			str += '<option value = "'+media.id+'" data-code = "'+media.code+'">'+media.name+'</option>';
		});

		return str;
	}
	this.generateMediaDocs = function(){
		var str = "";

		$.each(master_docs, function(k, doc){
			str += '<option value = "'+doc.id+'" data-code = "'+doc.code+'">'+doc.name+'</option>';
		});

		return str;
	}

	this.start = function(){
		function getMedia(){
			App.GetMasterData("media", function(d, e, f){
				master_media = d.data.items;
				getDocs();
			});
		}

		function getDocs(){
			App.GetMasterData("documentation", function(d, e, f){
				master_docs = d.data.items;
				App.HideLoading();
				Section.getItems(true);
			});
		}

		getMedia();
	}
}