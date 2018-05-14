function __action(){
	var removing_file = false;

	$("#form_file_delete").submit(function(e){
		e.preventDefault();

		if(removing_file){
			return;
		}

		App.DOM_Disabling($("#modal_file_delete"));
		removing_file = true;
		App.ShowLoading(App.terms.str_removing_file);
		App.LockScreen();

		App.HTTP.delete({
			url : App.WEB_ROOT + "/file/"+Section.id_file_to_delete_or_update,
			success : function(d, e, f){
				Section.refreshDirectory();
				$("#modal_file_delete").modal("hide");
			},
			after : function(x, y, z){
				App.DOM_Enabling($("#modal_file_delete"));
				removing_file = false;
				App.HideLoading();
				App.UnlockScreen();
			}
		});
	});
}