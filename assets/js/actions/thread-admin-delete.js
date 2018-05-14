function __action(){
	var removing_admin = false;

	$("#form_thread-admin_delete").submit(function(e){
		e.preventDefault();

		if(removing_admin){
			return;
		}

		App.LockScreen();
		App.DOM_Disabling($("#modal_thread-admin_delete"));
		removing_admin = true;
		App.ShowLoading(App.terms.str_removing_admins);

		App.HTTP.delete({
			url : App.WEB_ROOT + "/thread/" + Section.IN_THREAD_WITH_ID + "/admin/"+Section.ID_ADMIN_REMOVE,
			success : function(d, e, f){
				var n = Section.threadsData[Section.IN_THREAD_WITH_ID].admins.length;
				var i = 0;

				while(i < n && Number(Section.threadsData[Section.IN_THREAD_WITH_ID].admins[i].id) != Section.ID_ADMIN_REMOVE){
					i++;
				}

				if(i < n){
					Section.threadsData[Section.IN_THREAD_WITH_ID].admins = Section.threadsData[Section.IN_THREAD_WITH_ID].admins.slice(0, i).concat(Section.threadsData[Section.IN_THREAD_WITH_ID].admins.slice(i+1))
				}

				$("#li_admin_"+Section.ID_ADMIN_REMOVE).remove();
				$("#modal_thread-admin_delete").modal("hide");
			}, error : function(x, y, z){
			}, after : function(){
				App.UnlockScreen();
				App.DOM_Enabling($("#modal_thread-admin_delete"));
				removing_admin = false;
				App.HideLoading();
			}
		});
	});
}