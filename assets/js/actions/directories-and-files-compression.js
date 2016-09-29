function __action(){
	$("#save_compressed_file_in_folder").html($("#directories").html());

	var compressing_selected_items = false;

	$("#form_directories-and-files_compression").submit(function(e){
		e.preventDefault();

		if(compressing_selected_items){
			return;
		}

		var data = {
			directories : [],
			files : [],
			save_compressed_in : $("#save_compressed_file_in").prop("checked")?$("#save_compressed_file_in_folder").val():undefined,
			name : $(this).find("input[name='name']").val().trim(),
			description : $(this).find("textarea[name='description']").val().trim()
		};

		$("#list_items"+(Section.in_view=="main"?"":"_search_results")+" tr[data-type='file']").each(function(){
			if($(this).attr("data-selected") == "1"){
				data.files.push($(this).attr("data-id"));
			}
		});

		$("#list_items"+(Section.in_view=="main"?"":"_search_results")+" tr[data-type='dir']").each(function(){
			if($(this).attr("data-selected") == "1"){
				data.directories.push($(this).attr("data-id"));
			}
		});

		if(data.name.length == 0 || (data.directories.length + data.files.length == 0)){
			return;
		}

		App.DOM_Disabling($("#modal_directories-and-files_compression"));
		compressing_selected_items = true;
		App.ShowLoading(App.terms.str_compressing_selected_items);

		App.HTTP.post({
			url : App.WEB_ROOT + "/files-and-directories-compression",
			data : data,
			success : function(d, e, f){
				setTimeout(function(){
					window.location.href = App.WEB_ROOT + "/file/"+d.data.item.id+"/download";
				}, 100);
				$("#form_directories-and-files_compression input[name='name']").val("");
				$("#form_directories-and-files_compression textarea[name='description']").val("");
				$("#form_directories-and-files_compression input[type='checkbox']").prop("checked", false);
				$("#modal_directories-and-files_compression").modal("hide");
			},error : function(x, y, z){	
			},after : function(){
				App.DOM_Enabling($("#modal_directories-and-files_compression"));
				compressing_selected_items = false;
				App.HideLoading();
			}
		});
	});

	$("#save_compressed_file_in").change(function(){
		if(this.checked){
			$("#save_compressed_file_in_folder").show(App.TIME_FOR_SHOW);
		}else{
			$("#save_compressed_file_in_folder").hide(App.TIME_FOR_HIDE);
		}
	});
}

var Action = new __action();