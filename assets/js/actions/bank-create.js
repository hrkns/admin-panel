//"use strict";
function __action(){
/**/
	$("#modal_bank_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_bank_create").find("select[name='status']").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("form_bank_create").onsubmit = function(e){
		e.preventDefault();

		var name = $(this).find("input[name='name']").val().trim(),
			code = $(this).find("input[name='code']").val().trim(),
			statuses = $(this).find("select[name='status']").val();

		if(typeof statuses != "object"){
			statuses = [statuses];
		}
		App.LockScreen();
		App.DOM_Disabling($("#modal_bank_create"));
		App.ShowLoading(App.terms.str_creating_bank);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/bank",
				data:{
					name:name,
					status:statuses,
					code:code
				},success:function(d){
					App.addItemToDOM(d);

					$("#modal_bank_create").modal("hide");
					$("#form_bank_create").find("	input[name='name']").val("");
					$("#form_bank_create").find("	input[name='code']").val("");
					$(".select2-container").css("width", "100%");

					if(addAnother){
						setTimeout(function(){
							$("#modal_bank_create").modal("show");
						}, 1000);	
					}

					addAnother=false;
				},error:function(x, y, z){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_bank_create"));
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