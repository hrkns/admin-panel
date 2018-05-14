function __action(){
	App.initSectionValues(Section.CONFIGURATION)

	$("#create_term_modal_button").click(function(){
		App.getView("term", "create");
	});

	App.inputTextMonitor("input_text_search", function(){
		var str = $("#input_text_search").val().trim();
		str = str.split(" ");
		var values = [];
		var length = 0;

		for(var g in str){
			var h = str[g].trim().toLowerCase();

			if(h.length > 0 && values.indexOf(h) == -1){
				values.push(h);
				length++;
			}
		}

		$("#list_items").children().each(function(){
			if(length){
				var cond = false;

				$(this).find("input, textarea, p").each(function(){
					for(var x in values){
						cond = 	cond || 
								$(this).val().trim().toLowerCase().indexOf(values[x]) != -1 || 
								$(this).html().trim().toLowerCase().indexOf(values[x]) != -1;
					}
				});

				var statuses = JSON.parse($(this).attr("data-status"));

				if(cond){
					if(statuses.indexOf(-1) == -1){
						statuses.push(-1);
					}

					if(statuses.indexOf(-2) != -1){
						statuses = statuses.slice(0, statuses.indexOf(-2)).concat(statuses.slice(statuses.indexOf(-2) + 1));
					}
				}else{
					if(statuses.indexOf(-2) == -1){
						statuses.push(-2);
					}

					if(statuses.indexOf(-1) != -1){
						statuses = statuses.slice(0, statuses.indexOf(-1)).concat(statuses.slice(statuses.indexOf(-1) + 1));
					}
				}

				$(this).attr("data-status", JSON.stringify(statuses));
			}else{
				var statuses = JSON.parse($(this).attr("data-status"));

				if(statuses.indexOf(-2) != -1){
					statuses = statuses.slice(0, statuses.indexOf(-2)).concat(statuses.slice(statuses.indexOf(-2) + 1));
				}

				if(statuses.indexOf(-1) != -1){
					statuses = statuses.slice(0, statuses.indexOf(-1)).concat(statuses.slice(statuses.indexOf(-1) + 1));
				}

				$(this).attr("data-status", JSON.stringify(statuses));
			}

			App.showOrHideRow(this, $("#see_with_status").val());
		});
	}, 300);

	$("#set_items_language").change(function(){
		$("#list_items").empty();
		Section.FLAGS.formConfigs.lng = $(this).val();
		getItems(true);
	});

	var cloning = false;

	$("#clone_terms").click(function(){
		if($("#sections_for_clone_terms").val().length > 0){
			if(cloning){
				return;
			}

			cloning = true;
			$("#modal_terms_read").modal("hide");
			App.LockScreen();
			App.ShowLoading(App.terms.str_cloning_terms);

			App.HTTP.post({
				url:App.WEB_ROOT+"/section/"+Section.idSection+"/terms-cloning",
				data:{
					sections:$("#sections_for_clone_terms").val(),
					option:$("#clone_terms_ignore").prop("checked")?"ignore":($("#clone_terms_overwrite").prop("checked")?"overwrite":"append_sufix")
				},success:function(d, e, f){
					$("#clone_terms_append_sufix").prop("checked", true);
					$("#tree_sections").find("*[data-id='"+Section.idSection+"']").trigger("click");
				},after:function(x, y, z){
					App.UnlockScreen();
					cloning = false;
					App.HideLoading();
				}
			});
		}
	});

	$("#show_managing_controls").click(function(){
		$("#show_cloning_controls").removeClass("btn-primary");
		$("#show_managing_controls").addClass("btn-primary");
		$("#managing_controls").show(App.TIME_FOR_SHOW);
		$("#cloning_controls").hide(App.TIME_FOR_HIDE);
	});

	$("#show_cloning_controls").click(function(){
		$("#show_cloning_controls").addClass("btn-primary");
		$("#show_managing_controls").removeClass("btn-primary");
		$("#cloning_controls").show(App.TIME_FOR_SHOW);
		$("#managing_controls").hide(App.TIME_FOR_HIDE);
	});

	$("#form_terms_read").submit(function(){
		e.preventDefault();
	});
}