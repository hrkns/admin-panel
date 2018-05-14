function __action(){
	var copying_items_to = false;
	var directories = [];

	$("#list_items"+(Section.in_view=="main"?"":"_search_results")+" tr[data-type='dir']").each(function(){
		if($(this).attr("data-selected") == "1"){
			directories.push($(this).attr("data-id"));
		}
	});

	$("#copy_selected_items_to_select").html($("#directories").html());

	$("#form_directories-and-files_copying").submit(function(e){
		e.preventDefault();

		if(copying_items_to){
			return;
		}

		var data = {
			destiny : $("#copy_selected_items_to_select").val(),
			files : [],
			directories : directories
		};

		$("#list_items"+(Section.in_view=="main"?"":"_search_results")+" tr[data-type='file']").each(function(){
			if($(this).attr("data-selected") == "1"){
				data.files.push($(this).attr("data-id"));
			}
		});

		App.DOM_Disabling($("#form_directories-and-files_copying"));
		copying_items_to = true;
		App.LockScreen();
		App.ShowLoading(App.terms.str_copying_items_to);

		App.HTTP.post({
			url : App.WEB_ROOT + "/copy-items-to",
			data : data,
			success : function(d, e, f){
				$("#modal_directories-and-files_copying").modal("hide");
				Section.refreshDirectory();
			},error : function(x, y, z){
			},after : function(){
				App.DOM_Enabling($("#form_directories-and-files_copying"));
				copying_items_to = false;
				App.UnlockScreen();
				App.HideLoading();
			}
		});
	});
}