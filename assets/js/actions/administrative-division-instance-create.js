//"use strict";
function __action(){
/**/
	$("#modal_administrative-division-instance_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_administrative-division-instance_create").find("select").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("form_administrative-division-instance_create").onsubmit = function(e){
		e.preventDefault();

		var name = $(this).find("input[name='name']").val().trim(),
			code = $(this).find("input[name='code']").val().trim(),
			desc = $(this).find("textarea[name='description']").val().trim(),
			stat = $(this).find("select[name='status']").val();

		if(typeof stat != "object"){
			stat = [stat];
		}
		App.LockScreen();
		App.DOM_Disabling($("#modal_administrative-division-instance_create"));
		App.ShowLoading(App.terms.str_creating_instance);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/administrative-division-instance",
				data:{
					name:name, 
					description:desc,
					status:stat,
					code:code,
					parents:$("#modal_administrative-division-instance_create").find("select[name='parents']").val(),
					types:$("#modal_administrative-division-instance_create").find("select[name='types']").val()
				},success:function(d){
					Section.FLAGS.onCreation = true;
					App.addItemToDOM(d);
					Section.FLAGS.onCreation = false;

					$("#modal_administrative-division-instance_create").modal("hide");
					$("#modal_administrative-division-instance_create").find("input[name='name']").val("");
					$("#modal_administrative-division-instance_create").find("textarea[name='description']").val("");
					$("#modal_administrative-division-instance_create").find("input[name='code']").val("");

					if(addAnother){
						setTimeout(function(){
							$("#modal_administrative-division-instance_create").modal("show");
						}, 1000);	
					}

					addAnother=false;
					$(".select2-container").css("width", "100%");
				},error:function(x, y, z){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_administrative-division-instance_create"));
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