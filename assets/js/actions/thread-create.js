//"use strict";
function __action(){
/**/
	$("#modal_thread_create").find("select[name='users']").select2();
	$(".select2-container").css("width", "100%");

	$("#thread_privacy_0, #thread_privacy_1").click(function(){
		$("#thread_create_participants").show(App.TIME_FOR_SHOW);
	});
	$("#thread_privacy_2").click(function(){
		$("#thread_create_participants").hide(App.TIME_FOR_HIDE);
	});

	$("#add_admin").click(function(){
		Section.add_row_new_admin("thread_create_list_admins");
	});

	document.getElementById("form_thread_create").onsubmit = function(e){
		e.preventDefault();
		var privacy = $("#thread_privacy_0").prop("checked")?0:($("#thread_privacy_1").prop("checked")?1:2);

		var data = {
			title:$("#modal_thread_create").find("input[name='title']").val().trim(),
			description:$("#modal_thread_create").find("textarea[name='description']").val().trim(),
			speakers:privacy!=2?$("#modal_thread_create").find("select[name='users']").val():Array(),
			privacy:privacy
		}

		data["admins"] = Section.build_admins("modal_thread_create");

		App.LockScreen();
		App.DOM_Disabling($("#modal_thread_create"));
		App.ShowLoading(App.terms.str_creating_thread);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/thread",
				data:data,
				success:function(d){
					App.addItemToDOM(d);

					$("#modal_thread_create").modal("hide");
					$("#form_thread_create").find("	input[name='title'], textarea[name='description']").val("");
					$(".select2-container").css("width", "100%");

					if(addAnother){
						setTimeout(function(){
							$("#modal_thread_create").modal("show");
						}, 1000);
					}

					addAnother=false;
				},error:function(x, y, z){
				},after:function(){
					App.DOM_Enabling($("#modal_thread_create"));
					App.UnlockScreen();
					App.HideLoading();
				}
			});
		}, App.RETARD_MULTIPLE_LOAD);
	}

	var addAnother = false;

	document.getElementById("load_and_another").onclick = function(e){
		addAnother=true;
	}
}