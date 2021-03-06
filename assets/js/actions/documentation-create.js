//"use strict";
function __action(){
/**/
	$("#modal_documentation_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_documentation_create").find("select").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("form_documentation_create").onsubmit = function(e){
		e.preventDefault();

		var code = $(this).find("input[name='code']").val().trim(),
			name = $(this).find("input[name='name']").val().trim(),
			description = $(this).find("textarea[name='description']").val().trim(),
			statuses = $(this).find("select").val();

		if(typeof statuses != "object"){
			statuses = [statuses];
		}
		App.LockScreen();
		App.DOM_Disabling($("#modal_documentation_create"));
		App.ShowLoading(App.terms.str_creating_documentation);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/documentation",
				data:{
					code:code,
					name:name,
					description:description,
					status:statuses
				},success:function(d){
					App.addItemToDOM(d);

					$("#modal_documentation_create").modal("hide");
					$("#form_documentation_create").find("	input[name='code'],\
														input[name='name'],\
														textarea[name='description']").val("");
					$(".select2-container").css("width", "100%");

					if(addAnother){
						setTimeout(function(){
							$("#modal_documentation_create").modal("show");
						}, 1000);	
					}

					addAnother=false;
				},error:function(x, y, z){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_documentation_create"));
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