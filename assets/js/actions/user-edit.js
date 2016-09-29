function __action(){
	$("#modal_user_edit").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))


	var updating_user = false;

	$("#form_user_edit").submit(function(e){
		e.preventDefault();

		if(updating_user){
			return;
		}

		var fullname = $("#modal_user_edit").find("input[name='fullname']").val().trim();
		var nick = $("#modal_user_edit").find("input[name='nick']").val().trim();
		var email = $("#modal_user_edit").find("input[name='email']").val().trim();
		var pass = $("#modal_user_edit").find("input[name='password']").val().trim();
		var lstatus = $("#modal_user_edit").find("select[name='status']").val();

		if(typeof lstatus != "object"){
			lstatus = [lstatus];
		}

		for(c in lstatus){
			lstatus[c] = Number(lstatus[c]);
		}

		var data = {
			fullname:fullname,
			nick:nick,
			email:email,
			pass:pass,
			status:lstatus
		};

		var lcoms = [];

		$("#list_communication_routes_edit_user").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lcoms.push(x);
		});

		data["media"] = lcoms;
		data["profile_img"] = $("#profile_img_edit_user").attr("src");
		data["role"] = $("#form_user_edit").find("select[name='role']").val();
		App.LockScreen();
		App.DOM_Disabling($("#modal_user_edit"));
		updating_user = true;
		App.ShowLoading(App.terms.str_saving);

		App.HTTP.update({
			url:App.WEB_ROOT+"/user/"+Section.ID_USER_EDITING,
			data:data,
			success:function(d, e, f){
				$("#preview_user_"+Section.ID_USER_EDITING+"_fullname").html("<strong>"+data.fullname+"</strong>");
				$("#preview_user_"+Section.ID_USER_EDITING+"_nick").html("<strong>"+data.nick+"</strong>");
				$("#preview_user_"+Section.ID_USER_EDITING+"_email").html("<strong>"+data.email+"</strong>");
				$("#preview_user_"+Section.ID_USER_EDITING+"_profile_img").attr("src", data.profile_img);
				$("#modal_user_edit").modal("hide");
				$("#row_user__"+Section.ID_USER_EDITING+", #row_user_search__"+Section.ID_USER_EDITING).attr("data-status", JSON.stringify(lstatus));
				$("#statuses_"+Section.ID_USER_EDITING+", #statuses_search_"+Section.ID_USER_EDITING).html(App.stringify_statuses(lstatus));

				if(App.CommonsElements(Section.arrayStatusForDelete, data.status)){
					$("#delete_item__"+Section.ID_USER_EDITING).show(App.TIME_FOR_SHOW);
					$("#delete_item__search_"+Section.ID_USER_EDITING).show(App.TIME_FOR_SHOW);
				}else{
					$("#delete_item__"+Section.ID_USER_EDITING).hide(App.TIME_FOR_HIDE);
					$("#delete_item__search_"+Section.ID_USER_EDITING).hide(App.TIME_FOR_HIDE);
				}

				$("#user_fullname_dropodown_navbar_"+Section.ID_USER_EDITING).html(data.fullname);

				if(data.profile_img.indexOf("data:image") != -1){
					$("#user_profile_img_dropdown_navbar_"+Section.ID_USER_EDITING).attr("src", data.profile_img);
				}
			},error:function(x, y, z){
			},after:function(){
				App.UnlockScreen();
				App.DOM_Enabling($("#modal_user_edit"));
				updating_user = false;
				App.HideLoading();
			}
		});
	});

	$("#add_communication_route_edit_user").click(function(e){
		e.preventDefault();

		if(Section.permises["update"]){
			Section.add_row_communication_route("edit");
		}
	});

	$("#add_role_in_organization_edit_user").click(function(e){
		e.preventDefault();

		if(Section.permises["update"]){
			Section.add_row_role_in_organization("edit");
		}
	});

	$("#change_profile_img_edit_user").click(function(e){
		e.preventDefault();

		if(Section.permises["update"]){
			$("#file_profile_img_edit_user").trigger("click");
		}
	});

	$("#remove_profile_img_edit_user").click(function(e){
		e.preventDefault();
		$("#profile_img_edit_user").attr("src", "assets/images/profile/default.jpg");
		$(this).hide(App.TIME_FOR_HIDE);
	});

	$("#file_profile_img_edit_user").change(function(e){
		if(this.files && this.files[0]){
			$("#remove_profile_img_edit_user").show(App.TIME_FOR_SHOW);
			var reader = new FileReader();

			reader.onload = function(e){
				$("#profile_img_edit_user").fadeOut(500, function() {
					$("#profile_img_edit_user").attr('src', e.target.result);
			    }).fadeIn(500);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});
}