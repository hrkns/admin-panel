function __action(){
	var updating_admin_permises = false;

	/*
		process the form subit to change the permises over thread of an admin
	*/
	$("#form_thread-admin-permises_edit").submit(function(e){
		e.preventDefault();

		if(updating_admin_permises){
			return;
		}

		var permises = {
			delete_thread 				: $("#modal_thread-admin-permises_edit").find("input[data-name='delete_thread']"					).prop("checked"),
			edit_title 					: $("#modal_thread-admin-permises_edit").find("input[data-name='edit_title']"					).prop("checked"),
			edit_description 			: $("#modal_thread-admin-permises_edit").find("input[data-name='edit_description']"				).prop("checked"),
			add_admin 					: $("#modal_thread-admin-permises_edit").find("input[data-name='add_admin']"						).prop("checked"),
			no_remove_admin 			: $("#modal_thread-admin-permises_edit").find("input[data-name='no_remove_admin']"				).prop("checked"),
			remove_any_admin 			: $("#modal_thread-admin-permises_edit").find("input[data-name='remove_any_admin']"				).prop("checked"),
			remove_specific_admin 		: $("#modal_thread-admin-permises_edit").find("input[data-name='remove_specific_admin']"			).prop("checked"),
			no_set_permises_admin 		: $("#modal_thread-admin-permises_edit").find("input[data-name='no_set_permises_admin']"			).prop("checked"),
			set_permises_any_admin 		: $("#modal_thread-admin-permises_edit").find("input[data-name='set_permises_any_admin']"		).prop("checked"),
			set_permises_specific_admin : $("#modal_thread-admin-permises_edit").find("input[data-name='set_permises_specific_admin']"	).prop("checked"),
			set_privacy 				: $("#modal_thread-admin-permises_edit").find("input[data-name='set_privacy']"					).prop("checked"),
			accept_join_requests 		: $("#modal_thread-admin-permises_edit").find("input[data-name='accept_join_requests']"			).prop("checked"),
			reject_join_requests 		: $("#modal_thread-admin-permises_edit").find("input[data-name='reject_join_requests']"			).prop("checked")	
		};

		App.LockScreen();
		App.DOM_Disabling($("#modal_thread-admin-permises_edit"));
		updating_admin_permises = true;
		App.ShowLoading(App.terms.str_updating_admin_permises);

		App.HTTP.update({
			url : App.WEB_ROOT + "/thread/" + Section.IN_THREAD_WITH_ID + "/admin/"+Section.ID_ADMIN_UPDATE_PERMISES+"/permises",
			data : {
				permises : permises
			},success : function(d, e, f){
				var pos = 0,
					n = Section.threadsData[Section.IN_THREAD_WITH_ID].admins.length;

				while(pos < n && Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].id != Section.ID_ADMIN_UPDATE_PERMISES){
					pos++;
				}

				Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises = permises;
				$("#modal_thread-admin-permises_edit").modal("hide");
			},error : function(x, y, z){
			},after : function(){
				App.UnlockScreen();
				App.DOM_Enabling($("#modal_thread-admin-permises_edit"));
				updating_admin_permises = false;
				App.HideLoading();
			}
		});
	});

	var pos = 0;
	var n = Section.threadsData[Section.IN_THREAD_WITH_ID].admins.length;

	while(pos < n && Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].id != Section.ID_ADMIN_UPDATE_PERMISES){
		pos++;
	}

	$("#modal_thread-admin-permises_edit").find("input[data-name='accept_join_requests']"						).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.accept_join_requests 			));
	$("#modal_thread-admin-permises_edit").find("input[data-name='add_admin']"									).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.add_admin 						));
	$("#modal_thread-admin-permises_edit").find("input[data-name='delete_thread']"								).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.delete_thread 					));
	$("#modal_thread-admin-permises_edit").find("input[data-name='edit_description']"							).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.edit_description 				));
	$("#modal_thread-admin-permises_edit").find("input[data-name='edit_title']"									).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.edit_title 					));
	$("#modal_thread-admin-permises_edit").find("input[data-name='no_remove_admin']"								).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.no_remove_admin 				));
	$("#modal_thread-admin-permises_edit").find("input[data-name='no_set_permises_admin']"						).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.no_set_permises_admin 			));
	$("#modal_thread-admin-permises_edit").find("input[data-name='reject_join_requests']"						).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.reject_join_requests 			));
	$("#modal_thread-admin-permises_edit").find("input[data-name='remove_any_admin']"							).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.remove_any_admin 				));
	$("#modal_thread-admin-permises_edit").find("input[data-name='remove_specific_admin']"						).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.remove_specific_admin 			));
	$("#modal_thread-admin-permises_edit").find("input[data-name='set_permises_any_admin']"						).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.set_permises_any_admin 		));
	$("#modal_thread-admin-permises_edit").find("input[data-name='set_permises_specific_admin']"					).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.set_permises_specific_admin 	));
	$("#modal_thread-admin-permises_edit").find("input[data-name='set_privacy']"									).prop("checked", App.isTrue(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[pos].permises.set_privacy 					));

	$("#modal_thread-admin-permises_edit").modal("show");
}