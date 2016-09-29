//"use strict";
function __action(){
/**/
	$("#modal_user_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_user_create").find("select[name='status']").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("change_profile_img_new_user").onclick = function(e){
		e.preventDefault();
		$("#file_profile_img_new_user").trigger("click");
	}

	document.getElementById("remove_profile_img_new_user").onclick = function(e){
		e.preventDefault();
		$("#profile_img_new_user").attr("src", "assets/images/profile/default.jpg");
		$(this).hide(App.TIME_FOR_HIDE);
	}

	$("#file_profile_img_new_user").change(function(){
		if(this.files && this.files[0]){
			$("#remove_profile_img_new_user").show(App.TIME_FOR_SHOW);
			var reader = new FileReader();

			reader.onload = function(e){
				$("#profile_img_new_user").fadeOut(500, function() {
					$("#profile_img_new_user").attr('src', e.target.result);
			    }).fadeIn(500);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});

	document.getElementById("add_communication_route_new_user").onclick = function(e){
		e.preventDefault();
		Section.add_row_communication_route("new");
	}

	document.getElementById("form_user_create").onsubmit = function(e){
		e.preventDefault();

		var fullname = $("#modal_user_create").find("input[name='fullname']").val().trim(),
			nick = $("#modal_user_create").find("input[name='nick']").val().trim(),
			email = $("#modal_user_create").find("input[name='email']").val().trim(),
			pass = $("#modal_user_create").find("input[name='pass']").val().trim(),
			lstatus = $("#modal_user_create").find("select[name='status']").val(),
			data = {
				fullname:fullname,
				nick:nick,
				email:email,
				pass:pass,
				status:lstatus
			},
			lcoms = [];

		if(typeof lstatus != "object"){
			lstatus = [lstatus];
		}

		$("#list_communication_routes_new_user").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lcoms.push(x);
		});

		data["media"] = lcoms;
		data["profile_img"] = $("#profile_img_new_user").attr("src");
		data["role"] = $("#form_user_create").find("select[name='role']").val();

		App.LockScreen();
		App.DOM_Disabling($("#modal_user_create"));
		App.ShowLoading(App.terms.str_creating_user);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/user",
				data:data,
				success:function(d, e, f){
					App.addItemToDOM(d);

					$("#modal_user_create").find("input[type='text'],input[type='password']").val("");
					$("#list_roles_in_organization_new_user").empty();
					$("#list_communication_routes_new_user").empty();
					$("#remove_profile_img_new_user").trigger("click");
					$("#modal_user_create").modal("hide");

					if(addAnother){
						setTimeout(function(){
							$("#modal_user_create").modal("show");
						}, 500);
					}

					addAnother=false;
				},error:function(x, y, z){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_user_create"));
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