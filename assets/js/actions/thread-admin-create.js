function __action(){
	var adding_admins = false;

	/*
		form submit to add admins
	*/
	$("#form_thread-admin_create").submit(function(e){
		e.preventDefault();

		if(adding_admins){
			return;
		}

		var admins = Section.build_admins("modal_thread-admin_create");
		App.LockScreen();
		App.DOM_Disabling($("#modal_thread-admin_create"));
		adding_admins = true;
		App.ShowLoading(App.terms.str_adding_admins);

		App.HTTP.post({
			url : App.WEB_ROOT + "/thread/" + Section.IN_THREAD_WITH_ID + "/admins",
			data: {
				admins : admins
			},success : function(d, e, f){
				var cond = Number(Section.threadsData[Section.IN_THREAD_WITH_ID].privacy) != 2;

				if(cond){
					$("#select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID).select2("destroy");
				}

				var n = admins.length, t;

				for(var i = 0; i < n; i++){
					t = $("#db_users").find("option[value='"+admins[i].id_user+"']");

					if(cond){
						t.prop("selected", true);
					}

					Section.add_li_to_list_admins({
						fullname : t.html().trim(),
						id : admins[i].id_user,
						added_by : Section.ID_USER
					});

					Section.threadsData[Section.IN_THREAD_WITH_ID].admins.push({
						fullname : t.html().trim(),
						id : admins[i].id_user,
						added_by : Section.ID_USER,
						permises : admins[i].permises
					});
				}

				if(cond){
					$("#select_multiple_of_participants_on_thread_"+Section.IN_THREAD_WITH_ID).select2();
				}

				$(".select2-container").css("width", "100%");
				$("#modal_thread-admin_create").modal("hide");
				$("#thread_update_list_admins").empty();
				$("#save_changes_in_thread").trigger("click");
			},error : function(x, y, z){
			},after : function(){
				App.UnlockScreen();
				App.DOM_Enabling($("#modal_thread-admin_create"));
				adding_admins = false;
				App.HideLoading();
			}
		});
	});

	document.getElementById("add_admin_edit_thread").onclick = function(){
		var list_admins = Array();
		$.each(Section.threadsData[Section.IN_THREAD_WITH_ID].admins, function(k, v){
			list_admins.push(Number(v.id));
		});
		Section.add_row_new_admin("thread_update_list_admins", list_admins);
	}

	$("#modal_thread-admin_create").modal("show");
}