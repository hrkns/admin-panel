//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.list_directories = {};
	this.list_directories_on_search = {};
	this.id_directory_to_delete_or_update = null;
	this.id_file_to_delete_or_update = null;
	this.list_files = {};
	this.list_files_on_search = {};
	this.in_view = "main";

	var	str_dirs_selects = "#directories, #edit_file_select_parent, #edit_dir_select_parent, #move_content_to, #move_selected_items_to_select, #copy_selected_items_to_select",
		clicked = 0,
		cant_selected = 0,
		cant_selected_in_search = 0,
		counter_change_keywords = 0,
		last_keywords = "";

	this.str_dirs_selects = str_dirs_selects;
	this.mark = true;

	function row_content(v, is_dir){
		var location_column = ((typeof v.location != "undefined")?('<td><button class = "btn btn-primary btn-block" style = "padding:0px 10px 0px 10px;text-align:left;" onclick = "Section.accessRoute('+(is_dir?v.parent:v.id_directory)+');"><strong>'+v.location+'</strong></button></td>'):(""));
		var flag_toggle_selected = typeof v.location != "undefined";

		return ''+
		'<tr style = "cursor:pointer;" data-name = "'+v.name+'" data-id = "'+v.id+'" data-type = "'+(is_dir?"dir":"file")+'" onclick = "Section.toggleSelected(this, '+flag_toggle_selected+')" title = "'+v.description+'">'+
			'<td align = "center">'+
			'</td>'+
			'<td>'+
				'<i class = "fa fa-'+(is_dir?"folder":"file")+'" style = "color:'+(is_dir?"#0099e6":"gray")+';"></i>'+
				'<span>&nbsp;&nbsp;&nbsp;'+v.name+'</span>'+
			'</td>'+
			'<td>'+(v.type?v.type:App.terms.str_directory)+'</td>'+
			'<td>'+(!isNaN(v.size)?v.size:"")+'</td>'+
			location_column+
			'<td>'+
			'	<a href = "javascript:;" style = "text-decoration:none !important;" onclick = "Section.removeItem('+v.id+', '+(is_dir?true:false)+')"><i class = "fa fa-remove" style = "color:red;"></i></a>&nbsp;&nbsp;'+
			'	<a href = "javascript:;" style = "text-decoration:none !important;" onclick = "Section.editItem('+v.id+', '+(is_dir?true:false)+')"><i class = "fa fa-edit" style = "color:blue;"></i></a>&nbsp;&nbsp;'+
			'	<a href = "'+(is_dir?App.WEB_ROOT+"/directory/"+v.id+"/download":App.WEB_ROOT+"/file/"+v.id+"/download")+'" onclick = "Section.mark=false;" style = "text-decoration:none !important;" target = "_blank"><i class = "fa fa-download" style = "color:green;"></i></a>&nbsp;&nbsp;'+
			(is_dir?'<a href = "javascript:;" style = "text-decoration:none !important;" onclick = "Section.enterDirectory('+v.id+')"><i class = "fa fa-arrow-right" style = "color:black;"></i></a>&nbsp;&nbsp;':"")+
			'</td>'+
		'</tr>'+
		'';
	}

	var refreshingDirectory = false;

	this.enterDirectory = function(id_dir){
		Section.mark = false;
		Section.accessRoute(id_dir, true);
	}

	this.enterFromLinealTree = function(id_dir){
		if(id_dir == Section.currentDirectory){
			return;
		}

		Section.currentDirectory=id_dir;
		Section.refreshDirectory(true);
	}

	this.refreshDirectory = function(flag, fafter){
		if(refreshingDirectory){
			return;
		}

		refreshingDirectory = true;

		$("#list_items").children().each(function(){
			if($(this).attr("data-pre") != "4" && $(this).attr("data-pre") != "5"){
				$(this).remove();
			}
		});

		$("#lineal_tree").find("button").removeClass("btn-primary");
		$("#lineal_tree button[data-id='"+Section.currentDirectory+"']").addClass("btn-primary");
		$("#loading_items").show(App.TIME_FOR_SHOW);
		$("#group_options").hide(App.TIME_FOR_HIDE);
		$("#show_modal_create").hide(App.TIME_FOR_HIDE);
		cant_selected = 0;

		if(!flag){
			search_with_keywords(true);
		}

		$("#select_all").prop("checked", false);

		App.HTTP.read({
			url : App.WEB_ROOT + "/directory/"+Section.currentDirectory+"/content",
			success : function(d, e, f){
				$.each(d.data.directories, function(k, v){
					$(row_content(v, true)).insertBefore("#list_items tr[data-pre='5']");
					Section.list_directories[String(v.id)] = v;
				});

				$.each(d.data.files, function(k, v){
					$(row_content(v)).insertBefore("#list_items tr[data-pre='5']");
					Section.list_files[String(v.id)] = v;
				});

				if(typeof fafter != "undefined"){
					fafter();
				}
			},
			after : function(x, y, z){
				$("#loading_items").hide(App.TIME_FOR_HIDE);
				refreshingDirectory = false;
				$("#show_modal_create").show(App.TIME_FOR_SHOW);
			}
		});
	}

	this.currentDirectory = "root";

	this.removeItem = function(id, type){
		Section.mark = false;

		if(type){
			Section.id_directory_to_delete_or_update = id;
			App.getView("directory", "delete");
		}else{
			Section.id_file_to_delete_or_update = id;
			App.getView("file", "delete");
		}
	}

	this.editItem = function(id, type){
		Section.mark = false;

		if(type){
			Section.id_directory_to_delete_or_update = id;
			App.getView("directory", "edit");
		}else{
			Section.id_file_to_delete_or_update = id;
			App.getView("file", "edit");
		}
	}

	var accessingRoute = false;

	this.accessRoute = function(id_directory, flag){
		if(accessingRoute){
			return;
		}

		if(id_directory == null){
			id_directory = "root";
		}

		accessingRoute = true;

		App.HTTP.read({
			url : App.WEB_ROOT + "/directory/"+id_directory+"/parents-line",
			success : function(d, e, f){
				//construir linea de elementos
				$("#lineal_tree").empty();
				var items = [{
					id : "root",
					name : App.terms.str_root
				}].concat(d.data.items);

				$.each(items, function(k, v){
					$("#lineal_tree").append(''+
						'<button class = "btn" data-id = "'+v.id+'" onclick = "Section.enterFromLinealTree('+v.id+')">'+
							v.name+'&nbsp;<i class = "fa fa-chevron-right"></i>'+
						'</button>'+
					'');
				});
				/****/
				Section.currentDirectory = id_directory;

				if(flag){
					Section.refreshDirectory(true);
				}else{
					Section.refreshDirectory(true, function(){
						$("#go_to_main_view").trigger("click");
					});
				}
			},after : function(x, y, z){
				accessingRoute = false;
			}
		});
	}

	this.toggleSelected = function(t, flag){
		clicked++;

		if(clicked >= 2){
			if(flag){
				Section.accessRoute(t.getAttribute("data-id"));
			}else{
				if(t.getAttribute("data-type") == "dir"){

					if($("#lineal_tree button[data-id='"+t.getAttribute("data-id")+"']").length == 0){
						var cond = false;

						$("#lineal_tree").children().each(function(){
							if(cond){
								$(this).remove();
							}

							cond = cond || this.getAttribute("data-id") == Section.currentDirectory;
						});

						$("#lineal_tree").append('<button data-id = "'+t.getAttribute("data-id")+'" onclick = "Section.enterFromLinealTree('+t.getAttribute("data-id")+')" class = "btn btn-primary">'+t.getAttribute("data-name")+'&nbsp;<i class = "fa fa-chevron-right"></i></button>');
					}

					Section.currentDirectory = t.getAttribute("data-id");
					Section.refreshDirectory(true);
				}
			}
		}

		setTimeout(function(){
			if(Section.mark){
				if(t.className.indexOf("success") != -1){
					$(t).removeClass("success").attr("data-selected", "0");
					if(flag){
						cant_selected_in_search--;
					}else{
						cant_selected--;
					}
				}else{
					$(t).addClass("success").attr("data-selected", "1");
					if(flag){
						cant_selected_in_search++;
					}else{
						cant_selected++;
					}
				}

				if(flag){
					if(cant_selected_in_search==1){
						$("#group_options").fadeIn(App.TIME_FOR_SHOW);
					}else if(cant_selected_in_search == 0){
						$("#group_options").hide(App.TIME_FOR_HIDE);
					}
				}else{
					if(cant_selected==1){
						$("#group_options").fadeIn(App.TIME_FOR_SHOW);
					}else if(cant_selected == 0){
						$("#group_options").hide(App.TIME_FOR_HIDE);
					}
				}
			}else{
				Section.mark = true;
			}
			clicked = 0;
		}, 200);
	}

	$("#select_all").change(function(){
		$("#list_items").find("tr[data-pre!='4']").attr("class", this.checked?"success":"").attr("data-selected", this.checked?"1":"0");

		if($("#list_items").find("tr[data-pre!='4']").attr("data-selected") == "1"){
			cant_selected = $("#list_items").find("tr[data-pre!='4']").length;
			$("#group_options").show(App.TIME_FOR_SHOW);
		}else{
			cant_selected = 0;
			$("#group_options").hide(App.TIME_FOR_HIDE);
		}
	});

	$("#select_all_search_results").change(function(){
		$("#list_items_search_results").find("tr[data-pre!='4']").attr("class", this.checked?"success":"").attr("data-selected", this.checked?"1":"0");

		if($("#list_items_search_results").find("tr[data-pre!='4']").attr("data-selected") == "1"){
			cant_selected_in_search = $("#list_items_search_results").find("tr[data-pre!='4']").length;
			$("#group_options").show(App.TIME_FOR_SHOW);
		}else{
			cant_selected_in_search = 0;
			$("#group_options").hide(App.TIME_FOR_HIDE);
		}
	});

	$("#remove_selected_items").click(function(){
		App.getView("directories-and-files", "delete");
	});

	var searching = false;

	function search_with_keywords(flag){
		if(searching){
			return;
		}

		$("#list_items_search_results").children().each(function(){
			if($(this).attr("data-pre") != "4" && $(this).attr("data-pre") != "5"){
				$(this).remove();
			}
		});

		if($("#keywords").val().trim().length > 0){
			$("#no_results_msg").hide(App.TIME_FOR_HIDE);
			$("#loading_items_search_results").show(App.TIME_FOR_SHOW);

			if(!flag){
				Section.in_view = "search_results";
				$("#keywords").prop("disabled", true);
				$("#table_main_view").hide(App.TIME_FOR_HIDE);
				$("#table_search_results").show(App.TIME_FOR_SHOW);
				$("#go_to_main_view").fadeIn(App.TIME_FOR_SHOW);
				$("#go_to_search_results").fadeOut(App.TIME_FOR_HIDE);
				$("#group_options").hide(App.TIME_FOR_HIDE);
			}

			searching = true;

			App.HTTP.read({
				url : App.WEB_ROOT + "/cloud-search",
				data : {
					keywords : $("#keywords").val().trim(),
					from_level : $("#search_keywords_from_current_directory").prop("checked")?Section.currentDirectory:null
				},
				success : function(d, e, f){
					last_keywords = $("#keywords").val().trim();

					$.each(d.data.directories, function(k, v){
						$(row_content(v, true)).insertBefore("#list_items_search_results tr[data-pre='5']");
						Section.list_directories_on_search[String(v.id)] = v;
					});

					$.each(d.data.files, function(k, v){
						$(row_content(v)).insertBefore("#list_items_search_results tr[data-pre='5']");
						Section.list_files_on_search[String(v.id)] = v;
					});

					if(d.data.directories.length + d.data.files.length == 0){
						$("#no_results_msg").fadeIn(App.TIME_FOR_SHOW);
					}

					if(!flag){
						$("#keywords").focus();
					}
				},
				error : function(x, y, z){
				},
				after : function(){
					$("#keywords").prop("disabled", false);
					$("#loading_items_search_results").hide(App.TIME_FOR_HIDE);
					searching = false;
				}
			});
		}else{
			$("#go_to_main_view").fadeOut(App.TIME_FOR_HIDE);
			$("#go_to_search_results").fadeOut(App.TIME_FOR_HIDE);
			$("#table_main_view").fadeIn(App.TIME_FOR_SHOW);
			$("#table_search_results").fadeOut(App.TIME_FOR_HIDE);
			last_keywords = "";
		}
	}

	$("#go_to_main_view").click(function(){
		$("#go_to_main_view").fadeOut(App.TIME_FOR_HIDE);
		$("#go_to_search_results").fadeIn(App.TIME_FOR_SHOW);
		$("#table_main_view").fadeIn(App.TIME_FOR_SHOW);
		$("#table_search_results").fadeOut(App.TIME_FOR_HIDE);
		Section.in_view = "main";

		if(cant_selected > 0){
			$("#group_options").show(App.TIME_FOR_SHOW);
		}else{
			$("#group_options").hide(App.TIME_FOR_HIDE);
		}
	});

	$("#go_to_search_results").click(function(){
		$("#go_to_main_view").fadeIn(App.TIME_FOR_SHOW);
		$("#go_to_search_results").fadeOut(App.TIME_FOR_HIDE);
		$("#table_main_view").fadeOut(App.TIME_FOR_HIDE);
		$("#table_search_results").fadeIn(App.TIME_FOR_SHOW);
		Section.in_view = "search_results";

		if(cant_selected_in_search > 0){
			$("#group_options").show(App.TIME_FOR_SHOW);
		}else{
			$("#group_options").hide(App.TIME_FOR_HIDE);
		}
	});

	function change_keywords(){
		counter_change_keywords++;

		setTimeout(function(){
			counter_change_keywords--;

			if(counter_change_keywords == 0 && last_keywords != $("#keywords").val().trim()){
				search_with_keywords();
			}
		}, 1000);
	}

	$("#keywords").change(change_keywords).click(change_keywords).keydown(change_keywords).keyup(change_keywords).focus(change_keywords).blur(change_keywords);
	$("#search_keywords_from_current_directory").change(search_with_keywords);

	App.TimeInterval(function(){
		if(Section.in_view == "main"){
			$("#lineal_tree").show(App.TIME_FOR_SHOW);
		}else{
			$("#lineal_tree").hide(App.TIME_FOR_HIDE);
		}
	}, 1000);

	$("#compress_selected_items").click(function(){
		App.getView("directories-and-files", "compression");
	});

	$("#move_selected_items_to").click(function(){
		App.getView("directories-and-files", "moving");
	});

	$("#copy_selected_items_to").click(function(){
		App.getView("directories-and-files", "copying");
	});

	this.getItems = function(){
		$("#see_more_items").trigger("click");
	}

	this.start = function(){
		Section.refreshDirectory();
	}
}