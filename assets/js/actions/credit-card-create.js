//"use strict";
function __action(){
/**/
	$("#modal_credit-card_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_credit-card_create").find("select").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("form_credit-card_create").onsubmit = function(e){
		e.preventDefault();

		var name = $(this).find("input[name='name']").val().trim(),
			code = $(this).find("input[name='code']").val().trim(),
			desc = $(this).find("textarea[name='description']").val().trim(),
			stat = $(this).find("select[name='status']").val();

		if(typeof stat != "object"){
			stat = [stat];
		}
		App.LockScreen();
		App.DOM_Disabling($("#modal_credit-card_create"));
		App.ShowLoading(App.terms.str_creating_credit_card);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/credit-card",
				data:{
					name:name, 
					description:desc,
					status:stat,
					code:code
				},success:function(d){
					App.addItemToDOM(d);

					$("#modal_credit-card_create").modal("hide");
					$("#modal_credit-card_create").find("input[name='name']").val("");
					$("#modal_credit-card_create").find("input[name='code']").val("");
					$("#modal_credit-card_create").find("textarea[name='description']").val("");

					if(addAnother){
						setTimeout(function(){
							$("#modal_credit-card_create").modal("show");
						}, 1000);	
					}

					addAnother=false;
					$(".select2-container").css("width", "100%");
				},error:function(x, y, z){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_credit-card_create"));
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