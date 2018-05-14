//"use strict";
function __action(){
/**/
	$("#modal_customer_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_customer_create").find("select[name='status']").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("change_img_new_client").onclick = function(e){
		e.preventDefault();
		$("#file_img_new_client").trigger("click");
	}
	document.getElementById("remove_img_new_client").onclick = function(e){
		e.preventDefault();
		$("#img_new_client").attr("src", App.WEB_ROOT + "/assets/images/client/default.jpg");
		$(this).hide(App.TIME_FOR_HIDE);
	}

	$("#file_img_new_client").change(function(){
		if(this.files && this.files[0]){
			$("#remove_img_new_client").show(App.TIME_FOR_SHOW);
			var reader = new FileReader();
			reader.onload = function(e){
				$("#img_new_client").fadeOut(500, function() {
					$("#img_new_client").attr('src', e.target.result);
			    }).fadeIn(500);
			};
			reader.readAsDataURL(this.files[0]);
		}
	});

	document.getElementById("add_communication_route_new_client").onclick = function(e){
		e.preventDefault();
		Section.add_row_communication_route("new");
	}
	document.getElementById("add_address_new_client").onclick = function(e){
		e.preventDefault();
		Section.add_row_address("new");
	}
	document.getElementById("add_real_id_new_client").onclick = function(e){
		e.preventDefault();
		Section.add_row_real_id("new");
	}

	document.getElementById("form_customer_create").onsubmit = function(e){
		e.preventDefault();
		var name = $("#modal_customer_create").find("input[name='name']").val().trim();
		var lstatus = $("#modal_customer_create").find("select[name='status']").val();
		if(typeof lstatus != "object"){
			lstatus = [lstatus];
		}
		var data = {
			name:name,
			status:lstatus
		};
		var lcoms = [];
		$("#list_communication_routes_new_client").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lcoms.push(x);
		});
		var laddresses = [];
		$("#list_addresses_new_client").children().each(function(){
			laddresses.push($(this).find("textarea").val().trim());
		});
		var lids = [];
		$("#list_real_ids_new_client").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lids.push(x);
		});
		data["media"] = lcoms;
		data["addresses"] = laddresses;
		data["documentation"] = lids;
		data["img"] = $("#img_new_client").attr("src");

		App.LockScreen();
		App.DOM_Disabling($("#modal_customer_create"));
		App.ShowLoading(App.terms.str_creating_customer);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/customer",
				data:data,
				success:function(d, e, f){
					App.addItemToDOM(d);

					$("#modal_customer_create").find("input[type='text'],input[type='password'],textarea").val("");
					$("#list_communication_routes_new_client").empty();
					$("#list_addresses_new_client").empty();
					$("#list_real_ids_new_client").empty();
					$("#remove_img_new_client").trigger("click");
					$("#modal_customer_create").modal("hide");

					if(addAnother){
						setTimeout(function(){
							$("#modal_customer_create").modal("show");
						}, 500);
					}

					addAnother=false;
				},error:function(x, y, z){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_customer_create"));
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