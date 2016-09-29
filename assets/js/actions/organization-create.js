//"use strict";
function __action(){
/**/
	$("#modal_organization_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_organization_create").find("select[name='status']").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("change_img_new_organization").onclick = function(e){
		e.preventDefault();
		$("#file_img_new_organization").trigger("click");
	}

	document.getElementById("remove_img_new_organization").onclick = function(e){
		e.preventDefault();
		$("#img_new_organization").attr("src", App.WEB_ROOT+"/assets/images/organization/default.jpg");
		$(this).hide(App.TIME_FOR_HIDE);
	}

	$("#file_img_new_organization").change(function(){
		if(this.files && this.files[0]){
			$("#remove_img_new_organization").show(App.TIME_FOR_SHOW);
			var reader = new FileReader();

			reader.onload = function(e){
				$("#img_new_organization").fadeOut(500, function() {
					$("#img_new_organization").attr('src', e.target.result);
			    }).fadeIn(500);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});

	document.getElementById("add_communication_route_new_organization").onclick = function(e){
		e.preventDefault();
		Section.add_row_communication_route("new");
	}

	document.getElementById("add_address_new_organization").onclick = function(e){
		e.preventDefault();
		Section.add_row_address("new");
	}

	document.getElementById("add_real_id_new_organization").onclick = function(e){
		e.preventDefault();
		Section.add_row_real_id("new");
	}

	document.getElementById("add_payment_method_new_organization").onclick = function(e){
		e.preventDefault();
		Section.add_row_payment_method("new");
	}

	document.getElementById("form_organization_create").onsubmit = function(e){
		e.preventDefault();

		var name = $("#modal_organization_create").find("input[name='name']").val().trim(),
			lstatus = $("#modal_organization_create").find("select[name='status']").val();

		if(typeof lstatus != "object"){
			lstatus = [lstatus];
		}

		var	data = {
				name:name,
				status:lstatus
			},
			lcoms = [];

		$("#list_communication_routes_new_organization").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lcoms.push(x);
		});

		var laddresses = [];

		$("#list_addresses_new_organization").children().each(function(){
			laddresses.push($(this).find("textarea").val().trim());
		});

		var lids = [];

		$("#list_real_ids_new_organization").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lids.push(x);
		});

		data["payment_methods"] = {
			"banks":Array(),
			"e-payments":Array(),
			"credit-cards":Array()
		};

		$("#list_payment_methods_new_organization").children().each(function(){
			var x = $(this).find("select").val(),
				key;

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

		data["media"] = lcoms;
		data["addresses"] = laddresses;
		data["documentation"] = lids;
		data["img"] = $("#img_new_organization").attr("src");

		App.LockScreen();
		App.DOM_Disabling($("#modal_organization_create"));
		App.ShowLoading(App.terms.str_creating_organization);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/organization",
				data:data,
				success:function(d, e, f){
					App.addItemToDOM(d);

					$("#modal_organization_create").find("input[type='text'],input[type='password'],textarea").val("");
					$("#list_communication_routes_new_organization").empty();
					$("#list_addresses_new_organization").empty();
					$("#list_real_ids_new_organization").empty();
					$("#list_payment_methods_new_organization").empty();
					$("#remove_img_new_organization").trigger("click");
					$("#modal_organization_create").modal("hide");

					if(addAnother){
						setTimeout(function(){
							$("#modal_organization_create").modal("show");
						}, 500);	
					}

					addAnother=false;
				},error:function(x, y, z){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_organization_create"));
					App.HideLoading();
				}
			});
		}, App.RETARD_MULTIPLE_LOAD);
	}

	var addAnother = false;

	document.getElementById("load_and_another").onclick = function(e){
		addAnother=true;
	}
}