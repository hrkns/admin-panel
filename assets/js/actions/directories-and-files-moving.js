function __action(){
	var directories = [];

	$("#list_items"+(Section.in_view=="main"?"":"_search_results")+" tr[data-type='dir']").each(function(){
		if($(this).attr("data-selected") == "1"){
			directories.push($(this).attr("data-id"));
		}
	});

	var moving_items_to = false;

	$("#move_selected_items_to_select").html($("#directories").html());

	for(var i = 0; i < directories.length; i++){
		$("#move_selected_items_to_select").find("option[value='"+directories[i]+"']").remove();
	}

	$("#move_selected_items_to_select").find("option[value='"+Section.currentDirectory+"']").remove();

	$("#form_directories-and-files_moving").submit(function(e){
		e.preventDefault();

		if(moving_items_to){
			return;
		}

		var data = {
			new_parent : $("#move_selected_items_to_select").val(),
			files : [],
			directories : directories
		};

		$("#list_items"+(Section.in_view=="main"?"":"_search_results")+" tr[data-type='file']").each(function(){
			if($(this).attr("data-selected") == "1"){
				data.files.push($(this).attr("data-id"));
			}
		});

		App.DOM_Disabling($("#form_directories-and-files_moving"));
		App.LockScreen();
		moving_items_to = true;
		App.ShowLoading(App.terms.str_moving_items_to);

		App.HTTP.post({
			url : App.WEB_ROOT + "/set-selected-items-parent",
			data : data,
			success : function(d, e, f){
				$("#modal_directories-and-files_moving").modal("hide");
				Section.refreshDirectory();
			},error : function(x, y, z){
			},after : function(){
				App.DOM_Enabling($("#form_directories-and-files_moving"));
				moving_items_to = false;
				App.UnlockScreen();
				App.HideLoading();
			}
		});
	});
}