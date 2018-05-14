function __action(){
	$("#modal_organization_edit").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_organization_edit").find("select[name='status']").select2();

	$("#file_img_edit_organization").change(function(){
		if(this.files && this.files[0]){
			$("#remove_img_edit_organization").show(App.TIME_FOR_SHOW);
			var reader = new FileReader();

			reader.onload = function(e){
				$("#img_edit_organization").fadeOut(500, function() {
					$("#img_edit_organization").attr('src', e.target.result);
			    }).fadeIn(500);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});

	$("#change_img_edit_organization").click(function(e){
		e.preventDefault();
		$("#file_img_edit_organization").trigger("click");
	});

	$("#remove_img_edit_organization").click(function(e){
		e.preventDefault();
		$("#img_edit_organization").attr("src", "assets/images/organization/default.jpg");
		$(this).hide(App.TIME_FOR_HIDE);
	});

	var updatingOrg = false;

	$("#form_organization_edit").submit(function(e){
		e.preventDefault();

		if(updatingOrg){
			return;
		}

		var name = $("#modal_organization_edit").find("input[name='name']").val().trim();
		var lstatus = $("#modal_organization_edit").find("select[name='status']").val();

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

		$("#list_communication_routes_edit_organization").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lcoms.push(x);
		});

		var laddresses = [];

		$("#list_addresses_edit_organization").children().each(function(){
			laddresses.push($(this).find("textarea").val().trim());
		});

		var lids = [];

		$("#list_real_ids_edit_organization").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lids.push(x);
		});

		data["media"] = lcoms;
		data["addresses"] = laddresses;
		data["documentation"] = lids;
		data["img"] = $("#img_edit_organization").attr("src");
		data["payment_methods"] = {
			"banks":Array(),
			"e-payments":Array(),
			"credit-cards":Array()
		};

		$("#list_payment_methods_edit_organization").children().each(function(){
			var x = $(this).find("select").val();
			var key;

			if(x.indexOf("bank") != -1){
				key = "banks";
			}else if(x.indexOf("payment") != -1){
				key = "e-payments";
			}else if(x.indexOf("credit") != -1){
				key = "credit-cards";
			}

			data["payment_methods"][key].push({
				info : $(this).find("textarea").val().trim(),
				id_method : $(this).find("select").val().substr($(this).find("select").val().indexOf("-")+1)
			});
		});

		App.LockScreen();
		App.DOM_Disabling($("#modal_organization_edit"));
		updatingOrg = true;
		App.ShowLoading(App.terms.str_saving_changes);

		App.HTTP.update({
			url:App.WEB_ROOT+Section.ENDPOINT_ITEM+Section.IDORGANIZATIONEDITING,
			data:data,
			success:function(d, e, f){
				$("#preview_organization_"+Section.IDORGANIZATIONEDITING+"_name").html("<strong>"+data.name+"</strong>");
				$("#preview_organization_"+Section.IDORGANIZATIONEDITING+"_img").attr("src", data.img);
				$("#modal_organization_edit").modal("hide");
				$("#list_payment_methods_edit_organization").empty();

				if(App.CommonsElements(Section.arrayStatusForDelete, data.status)){
					$("#delete_item__"+Section.IDORGANIZATIONEDITING).show(App.TIME_FOR_SHOW);
					$("#delete_item__search_"+Section.IDORGANIZATIONEDITING).show(App.TIME_FOR_SHOW);
				}else{
					$("#delete_item__"+Section.IDORGANIZATIONEDITING).hide(App.TIME_FOR_HIDE);
					$("#delete_item__search_"+Section.IDORGANIZATIONEDITING).hide(App.TIME_FOR_HIDE);
				}

				$("#row_organization__"+Section.IDORGANIZATIONEDITING+", #row_organization_search_"+Section.IDORGANIZATIONEDITING).attr("data-status", JSON.stringify(lstatus));
				$("#statuses_"+Section.IDORGANIZATIONEDITING+", #statuses_search_"+Section.IDORGANIZATIONEDITING).html(App.stringify_statuses(lstatus));
			},error:function(x, y, z){
			},after:function(){
				updatingOrg = false;
				App.UnlockScreen();
				App.DOM_Enabling($("#modal_organization_edit"));
				App.HideLoading();
			}
		});
	});

	$("#add_communication_route_edit_organization").click(function(e){
		e.preventDefault();

		if(Section.permises["update"]){
			Section.add_row_communication_route("edit");
		}
	});

	$("#add_address_edit_organization").click(function(e){
		e.preventDefault();

		if(Section.permises["update"]){
			Section.add_row_address("edit");
		}
	});

	$("#add_real_id_edit_organization").click(function(e){
		e.preventDefault();

		if(Section.permises["update"]){
			Section.add_row_real_id("edit");
		}
	});

	$("#add_payment_method_edit_organization").click(function(e){
		e.preventDefault();

		if(Section.permises["update"]){
			Section.add_row_payment_method("edit");
		}
	});
}