function __action(){
	$("#remove_dir_move_content_to").change(function(){
		if(this.checked){
			$("#move_content_to").show(App.TIME_FOR_SHOW);
		}else{
			$("#move_content_to").hide(App.TIME_FOR_HIDE);
		}
	});

	$("#move_content_to").html($("#directories").html());//.select2();
	$("#move_content_to").find("option[value='"+Section.id_directory_to_delete_or_update+"']").remove();

	var removing_dir = false;

	$("#form_directory_delete").submit(function(e){
		e.preventDefault();

		if(removing_dir){
			return;
		}

		var move_to =  $("#remove_dir_move_content_to").prop("checked")?$("#move_content_to").val():undefined;

		App.DOM_Disabling($("#modal_directory_delete"));
		removing_dir = true;
		App.ShowLoading(App.terms.str_removing_dir);

		App.HTTP.delete({
			url : App.WEB_ROOT + "/directory/"+Section.id_directory_to_delete_or_update,
			data : {
				move_content_to : move_to
			},
			success : function(d, e, f){
				Section.refreshDirectory();
				$(Section.str_dirs_selects).find("option[value='"+Section.id_directory_to_delete_or_update+"']").remove();
				$("#modal_directory_delete").modal("hide");

				var v = $("#lineal_tree button[data-id="+Section.id_directory_to_delete_or_update+"]"), tmp;

				while(v.length){
					tmp = v.next();
					v.remove();
					v = tmp;
				}
			},
			after : function(x, y, z){
				App.DOM_Enabling($("#modal_directory_delete"));
				removing_dir = false;
				App.HideLoading();
			}
		});
	});
}