function __action(){
	var id = Section.id_directory_to_delete_or_update;

	$("#modal_directory_edit").find("input[name='name']").val(Section.list_directories[String(id)].name);
	$("#modal_directory_edit").find("textarea[name='description']").val(Section.list_directories[String(id)].description);
	var p = Section.list_directories[String(id)].parent != null?Section.list_directories[String(id)].parent:"root";
	$("#modal_directory_edit").find("select[name='parent']").val(p);
	$("#modal_directory_edit").modal("show");

	$("#edit_dir_select_parent").html($("#directories").html());//.select2();
	$("#edit_dir_select_parent").find("option[value='"+id+"']").remove();

	var editing_dir = false;

	$("#form_directory_edit").submit(function(e){
		e.preventDefault();
		var name = $(this).find("input[name='name']").val().trim(),
			description = $(this).find("textarea[name='description']").val().trim(),
			parent = $(this).find("select[name='parent']").val();

		if(name.length > 0 && !editing_dir){
			if(parent == "root"){
				parent = null;
			}

			App.DOM_Disabling($("#modal_directory_edit"));
			App.LockScreen();
			editing_dir = true;
			App.ShowLoading(App.terms.str_saving_changes);

			App.HTTP.update({
				data : {
					name : name,
					description : description,
					parent : parent
				},
				url : App.WEB_ROOT + "/directory/"+Section.id_directory_to_delete_or_update,
				success : function(d, e, f){
					$(Section.str_dirs_selects).find("option[value='"+Section.id_directory_to_delete_or_update+"']").html(name);
					$("#modal_directory_edit").modal("hide");
					Section.refreshDirectory();
				},
				after : function(x, y, z){
					App.DOM_Enabling($("#modal_directory_edit"));
					App.UnlockScreen();
					editing_dir = false;
					App.HideLoading();
				}
			});
		}
	});
}