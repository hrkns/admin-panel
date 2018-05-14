//"use strict";
function __action(){
/**/
	$("#modal_event_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_event_create").find("select").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("form_event_create").onsubmit = function(e){
		e.preventDefault();

		var name = $(this).find("input[name='name']").val().trim(),
			description = $(this).find("textarea[name='description']").val().trim(),
			statuses = $(this).find("select").val();

		if(typeof statuses != "object"){
			statuses = [statuses];
		}
		setTimeout(function(){
			if(	name.trim().length > 0){
				App.LockScreen();
				App.DOM_Disabling($("#modal_event_create"));
				App.ShowLoading(App.terms.str_creating_event);

				App.HTTP.create({
					url:App.WEB_ROOT+"/event",
					data:{
						name:name,
						description:description,
						status:statuses
					},success:function(d, e, f){
						App.addItemToDOM(d);

						$("#modal_event_create").modal("hide");
						$("#form_event_create").find("	input[name='name'],\
														textarea[name='description']").val("");
						if(addAnother){
							setTimeout(function(){
								$("#modal_event_create").modal("show");
							}, 1000);	
						}

						addAnother=false;
						$(".select2-container").css("width", "100%");
					},error:function(x, y, z){
					},after: function(){
						App.UnlockScreen();
						App.DOM_Enabling($("#modal_event_create"));
						App.HideLoading();
					}
				});
			}
		}, App.RETARD_MULTIPLE_LOAD);
	}

	var addAnother = false;

	document.getElementById("load_and_another").onclick = function(e){
		addAnother=true;
	}
}