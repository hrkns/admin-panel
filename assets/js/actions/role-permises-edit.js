//"use strict";
function __action(){
/**/
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

	$("#modal_role-permises_edit").find("tbody").find("input[data-type-check]").change(function(){
		var cond = $("#modal_role-permises_edit").find("tbody").find("input[data-type-checkbox='"+$(this).attr("data-type-checkbox")+"']:checked").length == $("#modal_role-permises_edit").find("tbody").children().length;
		$("#modal_role-permises_edit").find("thead").find("input[data-type-checkbox='"+$(this).attr("data-type-checkbox")+"']").prop("checked", cond);

		cond = $(this).parents("tr").find("input[data-type-check]:checked").length == $("#modal_role-permises_edit").find("thead").find("input").length - 1;
		$(this).parents("tr").find("input[data-type-checkbox='all']").prop("checked", cond);
	});

	document.getElementById("form_role-permises_edit").onsubmit = function(e){
		e.preventDefault();
		var t = $("#rows_permises_update").children(),
			nt = t.length,
			permisos = [];

		for(var i = 0; i < nt; i++){
			var d = {};
			d["section"] = $(t[i]).attr("data-id");
			d["actions"] =  [];

			$(t[i]).find("input[data-type-check='action']").each(function(){
				if($(this).is(":checked")){
					d["actions"].push($(this).attr("data-type-checkbox"));
				}
			});

			permisos.push(d);
		}

		App.LockScreen();
		App.DOM_Disabling($("#modal_role-permises_edit"));
		App.ShowLoading(App.terms.str_updating_permises);

		App.HTTP.update({
			url:App.WEB_ROOT+"/role/"+Section.IDROLEEDITINGPERMISES+"/permises",
			data:{
				permises:permisos
			},
			success:function(d){
				$("#modal_role-permises_edit").modal("hide");
			},error:function(x, y, z){
			},after:function(){
				App.UnlockScreen();
				App.DOM_Enabling($("#modal_role-permises_edit"));
				App.HideLoading();
			}
		});
	}
}