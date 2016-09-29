function __action(){
	$("#modal_customer_edit").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#change_img_edit_client").click(function(e){
		e.preventDefault();

		if(Section.permises["update"]){
			$("#file_img_edit_client").trigger("click");
		}
	});

	$("#remove_img_edit_client").click(function(e){
		e.preventDefault();
		$("#img_edit_client").attr("src", App.WEB_ROOT+"/assets/images/client/default.jpg");
		$(this).hide(App.TIME_FOR_HIDE);
	});

	$("#file_img_edit_client").change(function(){
		if(this.files && this.files[0]){
			$("#remove_img_edit_client").show(App.TIME_FOR_SHOW);
			var reader = new FileReader();

			reader.onload = function(e){
				$("#img_edit_client").fadeOut(500, function() {
					$("#img_edit_client").attr('src', e.target.result);
			    }).fadeIn(500);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});

	$("#form_customer_edit").submit(function(e){
		e.preventDefault();
		var name = $("#modal_customer_edit").find("input[name='name']").val().trim();
		var lstatus = $("#modal_customer_edit").find("select[name='status']").val();

		if(typeof lstatus != "object"){
			lstatus = [lstatus];
		}
		for(c in lstatus){
			lstatus[c] = Number(lstatus[c]);
		}

		var data = {
			name:name,
			status:lstatus
		};

		var lcoms = [];

		$("#list_communication_routes_edit_client").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lcoms.push(x);
		});

		var laddresses = [];

		$("#list_addresses_edit_client").children().each(function(){
			laddresses.push($(this).find("textarea").val().trim());
		});

		var lids = [];

		$("#list_real_ids_edit_client").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lids.push(x);
		});

		data["media"] = lcoms;
		data["addresses"] = laddresses;
		data["documentation"] = lids;
		data["img"] = $("#img_edit_client").attr("src");

		App.LockScreen();
		App.DOM_Disabling($("#modal_customer_edit"));
		App.ShowLoading(App.terms.str_saving_changes);

		App.HTTP.update({
			url:App.WEB_ROOT+"/customer/"+Section.ID_CUSTOMER_EDITING,
			data:data,
			success:function(d, e, f){
				$("#preview_client_"+Section.ID_CUSTOMER_EDITING+"_name").html("<strong>"+data.name+"</strong>");
				$("#preview_client_"+Section.ID_CUSTOMER_EDITING+"_img").attr("src", data.img);
				$("#modal_customer_edit").modal("hide");

				if(App.CommonsElements(Section.arrayStatusForDelete, data.status)){
					$("#delete_item__"+Section.ID_CUSTOMER_EDITING).show(App.TIME_FOR_SHOW);
					$("#delete_item__search_"+Section.ID_CUSTOMER_EDITING).show(App.TIME_FOR_SHOW);
				}else{
					$("#delete_item__"+Section.ID_CUSTOMER_EDITING).hide(App.TIME_FOR_HIDE);
					$("#delete_item__search_"+Section.ID_CUSTOMER_EDITING).hide(App.TIME_FOR_HIDE);
				}

				$("#row_client__"+Section.ID_CUSTOMER_EDITING+", #row_client_search_"+Section.ID_CUSTOMER_EDITING).attr("data-status", JSON.stringify(lstatus));
				$("#statuses_"+Section.ID_CUSTOMER_EDITING+", #statuses_search_"+Section.ID_CUSTOMER_EDITING).html(App.stringify_statuses(lstatus));
			},error:function(x, y, z){
			},after:function(){
				App.UnlockScreen();
				App.DOM_Enabling($("#modal_customer_edit"));
				App.HideLoading();
			}
		});
	});

	$("#add_communication_route_edit_client").click(function(e){
		e.preventDefault();
		if(Section.permises["update"]){
			Section.add_row_communication_route("edit");
		}
	});

	$("#add_address_edit_client").click(function(e){
		e.preventDefault();
		if(Section.permises["update"]){
			Section.add_row_address("edit");
		}
	});

	$("#add_real_id_edit_client").click(function(e){
		e.preventDefault();

		if(Section.permises["update"]){
			Section.add_row_real_id("edit");
		}
	});
}