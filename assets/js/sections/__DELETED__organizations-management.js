//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.ENDPOINT_ITEMS_INDEX = "/organizations";
	this.ENDPOINT_ITEMS_SEARCH = "/organizations-search";
	this.ENDPOINT_ITEM = "/organization/";
	this.getItems = null;
	this.arrayStatusForDelete = Array();
	var tmp = {};
	this.IDORGANIZATIONEDITING;
	this.ITEMS = {};

	/***********************************************************************************************************/

	this.add_item_form_to_dom = function(data, configs){
		var configs = App.cloneObject(Section.FLAGS.formConfigs);
		var row = App.startConfigRow(data, configs);

		/*
			column for attribute 'id'
		*/
			var column_id = document.createElement("td");
			column_id.align = "center";
			column_id.innerHTML = "<strong>"+data.id+"</strong>";

		/*
			column for attribute 'status'
		*/
			var column_statuses = document.createElement("td");
			column_statuses.style.display = App.isTrue(Section.CONFIGURATION.statuses.use)?"":"none";
			column_statuses.align = "center";
			column_statuses.innerHTML = App.stringify_statuses(data.status);
			column_statuses.id = "statuses_"+(Section.FLAGS.onSearch?"search_":"")+data.id;

		/*
			column for attribute 'logo'
		*/
			var column_logo = document.createElement("td");
			column_logo.align = "center";

			var image_logo = document.createElement("img");
			image_logo.src = App.IMG_ORGANIZATION_FOLDER_ROUTE+data.img;
			image_logo.style.width = "50px";
			image_logo.style.height = "50px";
			image_logo.id = "preview_organization_"+data.id+"_img";
			column_logo.appendChild(image_logo);

		/*
			column for attribute 'name'
		*/
			var column_name = document.createElement("td");
			column_name.align = "center";

			var text_name = document.createElement("p");
			text_name.innerHTML = "<strong>"+data.name+"</strong>";
			text_name.id = "preview_organization_"+data.id+"_name";
			column_name.appendChild(text_name);

		/*
			column for item controls (updating, deleting, reading...)
		*/
			var column_controls = document.createElement("td");
			column_controls.align = "center";

		/*
			button to get the info and show it in a modal
		*/
			var button_get_info = document.createElement("button");
			button_get_info.innerHTML = Section.permises["update"]?"<i class = 'fa fa-edit'></i>":"<i class = 'fa fa-eye'></i>";
			button_get_info.title = Section.permises["update"]?App.terms.str_edit:App.terms.str_see_information;
			button_get_info.className = "btn btn-info";

			var getting_info = false;

			button_get_info.onclick = function(e){
				e.preventDefault();

				if(getting_info){
					return;
				}

				getting_info = true;
				App.LockScreen();
				App.DOM_Disabling(data.row_selector);;
				App.ShowLoading(App.terms.str_requesting_org_info);

				App.HTTP.read({
					url:App.WEB_ROOT+"/organization/"+data.id+"/info",
					success:function(d, e, f){
						App.getView("organization", "edit", function(){
							$("#modal_organization_update").find("input[name='name']").val(d.data.item.name);
							$("#modal_organization_update").find("input[name='nick']").val(d.data.item.nick);

							if(d.data.item.img.indexOf("default") != -1){
								$("#img_edit_organization").attr("src", "assets/images/organization/default.jpg");
								$("#remove_img_edit_organization").hide(App.TIME_FOR_HIDE);
							}else{
								$("#img_edit_organization").attr("src", App.IMG_ORGANIZATION_FOLDER_ROUTE+d.data.item.img);
								$("#remove_img_edit_organization").show(App.TIME_FOR_SHOW);
							}

							try{
								$("#modal_organization_update").find("select[name='status']").select2("destroy");
							}catch(e){
							}

							$("#modal_organization_update").find("select[name='status']").html($("#see_with_status").html());

							$("#modal_organization_update").find("select[name='status']").children().each(function(){
								$(this).prop("selected", false);
							});

							$.each(d.data.item.status, function(k, v){
								$("#modal_organization_update").find("select[name='status']").find("option[value='"+v+"']").prop("selected", true);
							});

							$("#modal_organization_update").find("select[name='status']").select2();
							$(".select2-container").css("width", "100%");
							$("#list_communication_routes_edit_organization").empty();
							$("#list_addresses_edit_organization").empty();
							$("#list_real_ids_edit_organization").empty();
							$("#list_payment_methods_edit_organization").empty();

							$.each(d.data.item.media, function(k, v){
								Section.add_row_communication_route("edit", v);
							});

							$.each(d.data.item.documentation, function(k, v){
								Section.add_row_real_id("edit", v);
							});

							$.each(d.data.item.addresses, function(k, v){
								Section.add_row_address("edit", v);
							});

							$.each(d.data.item.payment_methods.banks, function(k, v){
								Section.add_row_payment_method("edit", v, "bank");
							});

							$.each(d.data.item.payment_methods["credit-cards"], function(k, v){
								Section.add_row_payment_method("edit", v, "creditcard");
							});

							$.each(d.data.item.payment_methods["e-payments"], function(k, v){
								Section.add_row_payment_method("edit", v, "epayment");
							});

							$("#modal_organization_update").modal("show");
							Section.IDORGANIZATIONEDITING =data.id;
						})
					},after:function(x, y, z){
						getting_info = false;
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
			row.appendChild(column_logo);
			row.appendChild(column_name);
			row.appendChild(column_controls);

		/*
			styling and setting needed metadata
		*/
			row.setAttribute("data-status", JSON.stringify(data.status));
			row.id = "row_organization_"+(Section.FLAGS.onSearch?"_search":"")+"_"+data.id;

		App.finalConfigRow(row, data);
	}

	this.add_row_communication_route = function(mode, data){
		var tr = document.createElement("tr");
		var column_logo = document.createElement("td");
		var select = document.createElement("select");

		select.className = "form-control";
		select.innerHTML = Section.generateMediaSelect();
		select.disabled = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			select.value = data.code;
		}

		column_logo.appendChild(select);

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

		btnrm.innerHTML = App.terms.str_remove;
		btnrm.className = "btn btn-danger";

		btnrm.onclick = function(e){
			e.preventDefault();
			$(tr).hide(App.TIME_FOR_HIDE, function(){ $(tr).remove(); });
		}

		if((mode == "edit" && Section.permises["update"]) || mode != "edit"){
			td3.appendChild(btnrm);
		}

		tr.appendChild(column_logo);
		tr.appendChild(column_name);
		tr.appendChild(td3);

		tr.style.display= "none";
		$("#list_communication_routes_"+mode+"_organization").append(tr);
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

		btnrm.innerHTML = App.terms.str_remove;
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
		$("#list_addresses_"+mode+"_organization").append(tr);
		$(tr).show(App.TIME_FOR_SHOW);
	}

	this.add_row_real_id = function(mode, data){
		var tr = document.createElement("tr");
		var column_logo = document.createElement("td");
		var select = document.createElement("select");

		select.className = "form-control";
		select.innerHTML = Section.generateDocSelect();
		select.disabled = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			select.value = data.code;
		}

		column_logo.appendChild(select);

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

		btnrm.innerHTML = App.terms.str_remove;
		btnrm.className = "btn btn-danger";
		btnrm.onclick = function(e){
			e.preventDefault();
			$(tr).hide(App.TIME_FOR_HIDE, function(){ $(tr).remove(); });
		}

		if(Section.permises["delete"]){
			td3.appendChild(btnrm);
		}

		tr.appendChild(column_logo);
		tr.appendChild(column_name);
		tr.appendChild(td3);
		tr.style.display = "none";
		$("#list_real_ids_"+mode+"_organization").append(tr);
		$(tr).show(App.TIME_FOR_SHOW);
	}

	this.add_row_payment_method = function(mode, data, method){
		var tr = document.createElement("tr");
		var column_logo = document.createElement("td");
		var select = document.createElement("select");

		select.className = "form-control";
		select.innerHTML = Section.generatePaymentSelect();
		select.disabled = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			select.value = method+"-"+data.id_method;
		}

		column_logo.appendChild(select);

		var column_name = document.createElement("td");
		var value = document.createElement("textarea");

		value.className = "form-control";
		value.readOnly = mode == "edit" && !Section.permises["update"];

		if(typeof data != "undefined"){
			value.value = data.info;
		}

		column_name.appendChild(value);

		var td3 = document.createElement("td");
		var btnrm = document.createElement("button");

		btnrm.innerHTML = App.terms.str_remove;
		btnrm.className = "btn btn-danger";
		btnrm.onclick = function(e){
			e.preventDefault();
			$(tr).hide(App.TIME_FOR_HIDE, function(){ $(tr).remove(); });
		}

		if((mode == "edit" && Section.permises["update"]) || mode != "edit"){
			td3.appendChild(btnrm);
		}

		tr.appendChild(column_logo);
		tr.appendChild(column_name);
		tr.appendChild(td3);
		tr.style.display = "none";
		$("#list_payment_methods_"+mode+"_organization").append(tr);
		$(tr).show(App.TIME_FOR_SHOW);
	}


	this.generateMediaSelect = function(){
		var str = "";

		$.each(master_media, function(k, item){
			str += '<option value = "'+item.id+'" data-code = "'+item.code+'">'+item.name+'</option>';
		});

		return str;
	}

	this.generatePaymentSelect = function(){
		var str = "";

		$.each(master_payments, function(k, item){
			str += '<option value = "'+k+'" data-code = "'+item.code+'">'+item.name+'</option>';
		});

		return str;
	}

	this.generateDocSelect = function(){
		var str = "";

		$.each(master_docs, function(k, item){
			str += '<option value = "'+item.id+'" data-code = "'+item.code+'">'+item.name+'</option>';
		});

		return str;
	}

	var master_media = {};
	var master_payments = {};
	var master_docs = {};

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
				getBanks();
			});
		}
		function getBanks(){
			App.GetMasterData("banks", function(d, e, f){
				$.each(d.data.items, function(k, v){
					master_payments["bank-"+v.id] = v;
				})
				getEpayment();
			});
		}
		function getEpayment(){
			App.GetMasterData("e-payment-methods", function(d, e, f){
				$.each(d.data.items, function(k, v){
					master_payments["epayment-"+v.id] = v;
				})
				getCreditCards();
			});
		}
		function getCreditCards(){
			App.GetMasterData("credit-cards", function(d, e, f){
				$.each(d.data.items, function(k, v){
					master_payments["creditcard-"+v.id] = v;
				})
				App.HideLoading();
				Section.getItems(true);
			});
		}

		App.ShowLoading();
		getMedia();
	}
}