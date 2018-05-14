//"use strict";
function __action(){
/**/
	$("#modal_status_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))
	$("#modal_status_create").find("select").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("form_status_create").onsubmit = function(e){
		e.preventDefault();

		var name = $(this).find("input[name='name']").val().trim(),
			desc = $(this).find("textarea[name='description']").val().trim(),
			code = $(this).find("input[name='code']").val().trim(),
			stat = $(this).find("select[name='status']").val();

		if(typeof stat != "object"){
			stat = [stat];
		}

		App.LockScreen();
		App.DOM_Disabling($("#modal_status_create"));
		App.ShowLoading(App.terms.str_creating_status);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/status",
				data:{
					name:name, 
					code:code,
					description:desc,
					status:stat,
					show_default:($("#modal_status_create").find("input[name='show_default']").is(":checked")?"1":"0"),
					show_item:($("#modal_status_create").find("input[name='show_item']").is(":checked")?"1":"0"),
					for_delete:($("#modal_status_create").find("input[name='for_delete']").is(":checked")?"1":"0")
				},success:function(d){
					Section.FLAGS.onCreation = true;
					App.addItemToDOM(d);
					Section.FLAGS.onCreation = false;

					$("#modal_status_create").modal("hide");
					$("#modal_status_create").find("input[name='name']").val("");
					$("#modal_status_create").find("textarea[name='description']").val("");
					$("#modal_status_create").find("input[name='code']").val("");

					if(addAnother){
						setTimeout(function(){
							$("#modal_status_create").modal("show");
						}, 1000);	
					}

					addAnother=false;
					$(".select2-container").css("width", "100%");
				},error:function(x, y, z){
				},after:function(){
					App.DOM_Enabling($("#modal_status_create"));
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