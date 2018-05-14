function __action(){
	var id = Section.id_file_to_delete_or_update;

	$("#modal_file_edit").find("input[name='name']").val(Section.list_files[String(id)].name);
	$("#modal_file_edit").find("textarea[name='description']").val(Section.list_files[String(id)].description);
	var p = Section.list_files[String(id)].parent != null?Section.list_files[String(id)].parent:"root";
	$("#modal_file_edit").find("select[name='parent']").val(p);
	$("#modal_file_edit").modal("show");

	$("#edit_file_select_parent").html($("#directories").html());//.select2();

	var editing_file = false;

	$("#form_file_edit").submit(function(e){
		e.preventDefault();
		var name = $(this).find("input[name='name']").val().trim(),
			description = $(this).find("textarea[name='description']").val().trim(),
			parent = $(this).find("select[name='parent']").val();

		if(name.length > 0 && !editing_file){
			if(parent == "root"){
				parent = null;
			}

			App.DOM_Disabling($("#modal_file_edit"));
			App.LockScreen();
			editing_file = true;
			App.ShowLoading(App.terms.str_saving_changes);

			App.HTTP.update({
				data : {
					name : name,
					description : description,
					parent : parent
				},
				url : App.WEB_ROOT + "/file/"+Section.id_file_to_delete_or_update,
				success : function(d, e, f){
					$("#modal_file_edit").modal("hide");
					Section.refreshDirectory();
				},
				after : function(x, y, z){
					App.DOM_Enabling($("#modal_file_edit"));
					App.UnlockScreen();
					editing_file = false;
					App.HideLoading();
				}
			});
		}
	});
}