//"use strict";
function __action(){
/**/
	$("#modal_role_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_role_create").find("select").select2();
	$(".select2-container").css("width", "100%");

	$("input[data-type-ubic='parent']").each(function(){
		$(this).change(function(){
			var val = $(this).prop("checked");

			$("input[data-type-checkbox='"+$(this).attr("data-type-checkbox")+"']").each(function(){
				$(this).prop("checked", val);

				if($(this).attr("data-type-checkbox") == 'all'){
					p = $(this).parent().parent();

					$(p).find("input[type='checkbox']").each(function(){
						$(this).prop("checked", val);
					});
				}
			});
		});
	});

	$("input[data-type-checkbox='all']").each(function(){
		if($(this).attr("data-type-ubic") != "parent"){
			$(this).change(function(){
				p = $(this).parent().parent();
				var val = $(this).prop("checked");

				$(p).find("input[type='checkbox']").each(function(){
					$(this).prop("checked", val);
				});
			});
		}
	});

	$("#modal_role_create").find("tbody").find("input[data-type-check]").change(function(){
		var cond = $("#modal_role_create").find("tbody").find("input[data-type-checkbox='"+$(this).attr("data-type-checkbox")+"']:checked").length == $("#modal_role_create").find("tbody").children().length;
		$("#modal_role_create").find("thead").find("input[data-type-checkbox='"+$(this).attr("data-type-checkbox")+"']").prop("checked", cond);

		cond = $(this).parents("tr").find("input[data-type-check]:checked").length == $("#modal_role_create").find("thead").find("input").length - 1;
		$(this).parents("tr").find("input[data-type-checkbox='all']").prop("checked", cond);
	});

	document.getElementById("form_role_create").onsubmit = function(e){
		e.preventDefault();
		var name = $(this).find("input[name='name']").val().trim(),
			code = $(this).find("input[name='code']").val().trim(),
			desc = $(this).find("textarea[name='description']").val().trim();

		if(name.length == 0){
			return 0;
		}

		var t = $("#rows_permises").children(),
			nt = t.length,
			permisos = [];

		for(var i = 0; i < nt; i++){
			var d = {};
			d["section"] = $(t[i]).attr("data-id");
			d["actions"] =  [];

			$(t[i]).find("input[data-type-check='action']").each(function(){
				if($(this).is(":checked"))
					d["actions"].push($(this).attr("data-type-checkbox"));
			});

			permisos.push(d);
		}

		var stat = $("#modal_role_create").find("select").val();
		if(typeof stat != "object"){
			stat = [stat];
		}
		var data = {
			name:name,
			code:code,
			description:desc,
			permises:permisos,
			status:stat
		};

		App.LockScreen();
		App.DOM_Disabling($("#modal_role_create"));
		App.ShowLoading(App.terms.str_creating_role);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/role",
				data:data,
				success:function(d){
					App.addItemToDOM(d);

					$("#modal_role_create").modal("hide");

					$("#modal_role_create").find("input[name='name']").each(function(){
						$(this).val("");
					});

					$("#modal_role_create").find("input[type='checkbox']").each(function(){
						$(this).prop("checked", false);
					});

					$("#modal_role_create").find("textarea").each(function(){
						$(this).val("");
					});

					if(addAnother){
						setTimeout(function(){
							$("#modal_role_create").modal("show");
						}, 1000);	
					}

					addAnother=false;
					$(".select2-container").css("width", "100%");
				},error:function(x, y, z){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_role_create"));
					App.HideLoading();
				}
			});
		}, App.RETARD_MULTIPLE_LOAD);
	};

	var addAnother = false;

	document.getElementById("load_and_another").onclick = function(e){
		addAnother=true;
	}
}