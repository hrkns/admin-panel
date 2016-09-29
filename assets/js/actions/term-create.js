//"use strict";
function __action(){
/**/
	$("#modal_term_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_term_create").find("select").select2();
	$(".select2-container").css("width", "100%");
	document.getElementById("form_term_create").onsubmit = function(e){
		e.preventDefault();

		var name = $(this).find("input[name='name']").val().trim(),
			desc = $(this).find("textarea[name='description']").val().trim(),
			code = $(this).find("input[name='code']").val().trim(),
			vale = $(this).find("input[name='value']").val().trim(),
			stat = $(this).find("select[name='status']").val();

		if(typeof stat != "object"){
			stat = [stat];
		}
		App.LockScreen();
		App.DOM_Disabling($("#modal_term_create"));
		App.ShowLoading(App.terms.str_creating_term);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/term",
				data:{
					name:name, 
					code:code,
					description:desc,
					status:stat,
					value:vale,
					idsection:Section.idSection
				},success:function(d){
					App.addItemToDOM(d);
					$("#modal_term_create").modal("hide");
					$("#modal_term_create").find("input[name='name']").val("");
					$("#modal_term_create").find("textarea[name='description']").val("");
					$("#modal_term_create").find("input[name='code']").val("");
					$("#modal_term_create").find("input[name='value']").val("");

					if(addAnother){
						setTimeout(function(){
							$("#modal_term_create").modal("show");
						}, 1000);
					}

					addAnother=false;
					$(".select2-container").css("width", "100%");
				},error:function(x, y, z){
				},after:function(){
					App.DOM_Enabling($("#modal_term_create"));
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