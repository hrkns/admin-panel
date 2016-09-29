function __action(){
	var directories_to_delete = [];

	$("#list_items"+(Section.in_view=="main"?"":"_search_results")+" tr[data-type='dir']").each(function(){
		if($(this).attr("data-selected") == "1"){
			directories_to_delete.push($(this).attr("data-id"));
		}
	});

	$("#remove_selected_items_move_content_to").empty();

	$("#list_items"+(Section.in_view=="search_results"?"_search_results":"")+" tr[data-type='dir']").each(function(){
		if($(this).attr("data-selected") == "1"){
			$("#remove_selected_items_move_content_to").append('<div data-row="1">'+
				'<input type = "checkbox" style = "width:25px;height:25px;" data-toggle-select = "'+$(this).attr("data-id")+'">&nbsp;'+
				App.terms.str_remove_dir_move_content +"&nbsp;<strong>"+$(this).attr("data-name")+"</strong>&nbsp;&nbsp;<span style = 'display:none;' data-select-destiny-span= '"+$(this).attr("data-id")+"'>--> "+App.terms.str_destiny+": "+
				'&nbsp;&nbsp;<select class = "form-control" style = "width:75%;display:inline;" data-select-destiny = "'+$(this).attr("data-id")+'">'+
					$("#directories").html()+
				'</select></span>'+
			'</div>');
		}
	});

	var nd = directories_to_delete.length;

	$("#remove_selected_items_move_content_to").find("select").each(function(){
		for(var i = 0; i < nd; i++){
			$(this).find("option[value='"+directories_to_delete[i]+"']").remove();
		}
	});

	$("#modal_directories-and-files_delete").modal("show");

	$("[data-toggle-select]").change(function(){
		if(this.checked){
			$("[data-select-destiny-span='"+$(this).attr("data-toggle-select")+"']").show(App.TIME_FOR_SHOW);
		}else{
			$("[data-select-destiny-span='"+$(this).attr("data-toggle-select")+"']").hide(App.TIME_FOR_HIDE);
		}
	});

	var removing_selected_items = false;

	$("#form_directories-and-files_delete").submit(function(e){
		e.preventDefault();

		if(removing_selected_items){
			return;
		}

		removing_selected_items = true;

		var data = {
			directories : [],
			files : []
		};

		$("#list_items"+(Section.in_view=="main"?"":"_search_results")+" tr[data-type='file']").each(function(){
			if($(this).attr("data-selected") == "1"){
				data.files.push($(this).attr("data-id"));
			}
		});

		$("#list_items"+(Section.in_view=="main"?"":"_search_results")+" tr[data-type='dir']").each(function(){
			if($(this).attr("data-selected") == "1"){
				data.directories.push({
					id : $(this).attr("data-id"),
					move_to : $("[data-toggle-select='"+$(this).attr("data-id")+"']").prop("checked")?$("[data-select-destiny='"+$(this).attr("data-id")+"']").val():undefined
				});
			}
		});

		removing_selected_items = true;
		App.DOM_Disabling($("#modal_directories-and-files_delete"));
		App.ShowLoading(App.terms.str_removing_selected_items);

		App.HTTP.delete({
			url : App.WEB_ROOT + "/files",
			data : data.files,
			success : function(d, e, f){
				App.HTTP.delete({
					url : App.WEB_ROOT + "/directories",
					data : data.directories,
					success : function(d, e, f){
						$("#modal_directories-and-files_delete").modal("hide");
						Section.refreshDirectory();

						$.each(data.directories, function(k, v){
							$(Section.str_dirs_selects).find("option[value='"+v.id+"']").remove();

							var v = $("#lineal_tree button[data-id="+v.id+"]"), tmp;

							while(v.length){
								tmp = v.next();
								v.remove();
								v = tmp;
							}
						});
					},
					after : function(x, y , z){
						removing_selected_items = false;
						App.DOM_Enabling($("#modal_directories-and-files_delete"));
						App.HideLoading();
					}
				});
			}, error : function(x, y, z){
				removing_selected_items = false;
				App.DOM_Enabling($("#modal_directories-and-files_delete"));
				App.HideLoading();
			}
		});
	});
}