"use strict";
function __action(){
/**/
	$("#modal_media_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_media_create").find("select").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("form_media_create").onsubmit = function(e){
		e.preventDefault();

		var name = $(this).find("input[name='name']").val().trim(),
			code = $(this).find("input[name='code']").val().trim(),
			desc = $(this).find("textarea[name='description']").val().trim(),
			stat = $(this).find("select[name='status']").val();

		if(typeof stat != "object"){
			stat = [stat];
		}
		App.LockScreen();
		App.DOM_Disabling($("#modal_media_create"));
		App.ShowLoading(App.terms.str_creating_media);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/media",
				data:{
					name:name, 
					description:desc,
					status:stat,
					code:code
				},success:function(d){
					App.addItemToDOM(d);

					$("#modal_media_create").modal("hide");
					$("#modal_media_create").find("input[name='name']").val("");
					$("#modal_media_create").find("input[name='code']").val("");
					$("#modal_media_create").find("textarea[name='description']").val("");

					if(addAnother){
						setTimeout(function(){
							$("#modal_media_create").modal("show");
						}, 1000);	
					}

					addAnother=false;
					$(".select2-container").css("width", "100%");
				},error:function(x, y, z){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_media_create"));
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