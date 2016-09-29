//"use strict";
function __action(){
/**/
	$("button[data-toggle-div]").click(function(e){
		e.preventDefault();
		if($(this).attr("class").indexOf("primary") == -1){
			$(this).addClass("btn-primary");
		}else{
			$(this).removeClass("btn-primary");
		}

		$("div[data-div-toggle='"+$(this).attr("data-toggle-div")+"']").toggle(App.TIME_FOR_SHOW);
	});

	$("#add_dir").click(function(e){
		e.preventDefault();

		var rndstr = String(Math.random()).substr(2);

		$(''+
			'<tr data-row="dir_to_create">'+
			'	<td align = "center">'+
			'		<button class = "btn btn-danger" id = "'+rndstr+'"><i class = "fa fa-remove"></i></button>'+
			'	</td>'+
			'	<td>'+
			'		<input class = "form-control" data-name="name">'+
			'	</td>'+
			'	<td>'+
			'		<textarea class = "form-control" data-name="description"></textarea>'+
			'	</td>'+
			'	<td>'+
			'		<select id = "select_'+rndstr+'" data-name="parent">'+$("#directories").html()+'</select>'+
			'	</td>'+
			'</tr>'+
		'').insertBefore("tr[data-pre='1']");

		$("#"+rndstr).click(function(){
			$(this).parent().parent().remove();
		})
		$("#select_"+rndstr).find("option[value='"+Section.currentDirectory+"']").prop("selected", true);

		$("select[data-name='parent']").select2();
		$(".select2-container").css("width", "100%");
	});

	$("#add_file").click(function(e){
		e.preventDefault();

		var rndstr = String(Math.random()).substr(2);

		$(''+
			'<tr data-row="file_to_create">'+
			'	<td align = "center">'+
			'		<button class = "btn btn-danger" id = "'+rndstr+'"><i class = "fa fa-remove"></i></button>'+
			'	</td>'+
			'	<td>'+
			'		<input class = "form-control" data-name="name">'+
			'	</td>'+
			'	<td>'+
			'		<textarea class = "form-control" data-name="description"></textarea>'+
			'	</td>'+
			'	<td>'+
			'		<select id = "select_'+rndstr+'" data-name="parent">'+$("#directories").html()+'</select>'+
			'	</td>'+
			'</tr>'+
		'').insertBefore("tr[data-pre='2']");

		$("#"+rndstr).click(function(){
			$(this).parent().parent().remove();
		})
		$("#select_"+rndstr).find("option[value='"+Section.currentDirectory+"']").prop("selected", true);

		$("select[data-name='parent']").select2();
		$(".select2-container").css("width", "100%");
	});

	$("#add_upload_file").click(function(e){
		e.preventDefault();

		var rndstr = String(Math.random()).substr(2);

		$(''+
			'<tr data-row="file_to_upload">'+
			'	<td align = "center">'+
			'		<button class = "btn btn-danger" id = "'+rndstr+'"><i class = "fa fa-remove"></i></button>'+
			'	</td>'+
			'	<td>'+
			'		<input class = "form-control" data-name="name">'+
			'	</td>'+
			'	<td>'+
			'		<textarea class = "form-control" data-name="description"></textarea>'+
			'	</td>'+
			'	<td>'+
			'		<select id = "select_'+rndstr+'" data-name="parent">'+$("#directories").html()+'</select>'+
			'	</td>'+
			'	<td>'+
			'		<input type = "file" class = "form-control">'+
			'	</td>'+
			'</tr>'+
		'').insertBefore("tr[data-pre='3']");

		$("#"+rndstr).click(function(){
			$(this).parent().parent().remove();
		})
		$("#select_"+rndstr).find("option[value='"+Section.currentDirectory+"']").prop("selected", true);

		$("select[data-name='parent']").select2();
		$(".select2-container").css("width", "100%");
	});

	$("#form_cloud_create").submit(function(e){
		e.preventDefault();

		var directories = [],
			files_to_create = [],
			files_to_upload_info = [];
			files_to_upload_files = [];

		if($("[data-toggle-div='create_dir']").hasClass("btn-primary")){
			$("tr[data-row='dir_to_create']").each(function(){
				if($(this).find("[data-name='name']").val().trim().length > 0){
					directories.push({
						name : $(this).find("[data-name='name']").val().trim(),
						description : $(this).find("[data-name='description']").val().trim(),
						parent : $(this).find("[data-name='parent']").val()
					});
				}
			});
		}

		if($("[data-toggle-div='create_file']").hasClass("btn-primary")){
			$("tr[data-row='file_to_create']").each(function(){
				if($(this).find("[data-name='name']").val().trim().length > 0){
					files_to_create.push({
						name : $(this).find("[data-name='name']").val().trim(),
						description : $(this).find("[data-name='description']").val().trim(),
						directory : $(this).find("[data-name='parent']").val()
					});
				}
			});
		}

		var str_files_being_uploaded = [];

		if($("[data-toggle-div='upload_file']").hasClass("btn-primary")){
			$("tr[data-row='file_to_upload']").each(function(){
				if($(this).find("[data-name='name']").val().trim().length > 0){
					var FILE = $(this).find("input[type='file']");
					FILE = FILE[0].files[0];

					files_to_upload_info.push({
						name : $(this).find("[data-name='name']").val().trim(),
						description : $(this).find("[data-name='description']").val().trim(),
						parent : $(this).find("[data-name='parent']").val()
					});

					str_files_being_uploaded.push({
						filename : $(this).find("[data-name='name']").val().trim(),
						uploaded : 0
					});

					files_to_upload_files.push(FILE);
				}
			});
		}

		function create_directories(){
			if(directories.length){
				App.ShowLoading(App.terms.str_creating_directories);
				App.HTTP.create({
					url : App.WEB_ROOT + "/directories",
					data : {
						"directories" : directories
					},
					success : function(d){
						$.each(d.data.items, function(k, v){
							$(Section.str_dirs_selects).append(''+
								'<option value = "'+v.id+'">'+
									v.name+
								'</option>'+
							'');
						});
						create_files();
					},
					error : function(e){
					},
					log_ui_msg : false
				});
			}else{
				create_files();
			}
		}

		function create_files(){
			if(files_to_create.length){
				App.ShowLoading(App.terms.str_creating_files);
				App.HTTP.create({
					url : App.WEB_ROOT + "/files",
					data : {
						"files" : files_to_create 
					},
					success : function(d){
						upload_files();
					},
					error : function(e){
					},
					log_ui_msg : false
				});
			}else{
				upload_files();
			}
		}

		function files_uploading_indicator(){
			var str = "<br><br>";

			$.each(str_files_being_uploaded, function(k, v){
				str += (function(){
					switch(v.uploaded){
						case 0:
							return App.terms.str_uploading + " " + "<strong>"+v.filename+"<strong><br>";
						break;
						case 1:
							return "<strong>"+v.filename+"<strong>&nbsp;<i class = 'icon ion-checkmark'></i><br>";
						break;
						case 2:
							return "<strong>"+v.filename+"<strong>&nbsp;<i class = 'icon ion-alert-circled'></i><br>";
						break;
					}
				})();
			});

			return str;
		}

		function upload_files(){
			function minifunc(){
				Section.refreshDirectory();
				App.UnlockScreen();
				$("#modal_cloud_create").modal("hide");
				App.HideLoading();
			}

			if(files_to_upload_files.length){
				App.ShowLoading(App.terms.str_creating_files_to_upload_info);
				App.HTTP.create({
					url : App.WEB_ROOT + "/files-upload-info",
					data : {
						"info" : files_to_upload_info
					},
					success : function(d, e, f){
						var counter = 0;
						var n = d.data.items.length;
						App.ShowLoading(App.terms.str_uploading_files + files_uploading_indicator());

						for(var i = 0; i < n; i++){
							(function(index){
								var tmp = new FormData();
								tmp.append("file", files_to_upload_files[index]);
								$.ajax({
									url : App.WEB_ROOT + "/files-upload-file/"+d.data.items[index].id,
									data : tmp,
									processData: false,
									contentType: false,
									type : "POST",
									success : function(d){
										counter++;
										str_files_being_uploaded[index]["uploaded"] = 1;
										App.ShowLoading(App.terms.str_uploading_files + files_uploading_indicator());

										if(counter == n){
											minifunc();
										}
									},
									error : function(e){
										counter++;
										str_files_being_uploaded[index]["uploaded"] = 2;
										App.ShowLoading(App.terms.str_uploading_files + files_uploading_indicator());

										if(counter == n){
											minifunc();
										}
									}
								});
							})(i);
						}
					},
					error : function(x, y, z){
						minifunc();
					},
					log_ui_msg : false
				});
			}else{
				minifunc();
			}
		}

		if(directories.length + files_to_create.length + files_to_upload_files.length){
			App.DOM_Disabling($("#modal_cloud_create"));
			App.LockScreen();
			create_directories();
		}
	});
}