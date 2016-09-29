//"use strict";
function __action(){
/**/
	$("#modal_action_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))
	$("#modal_action_create").find("select").select2();
	$(".select2-container").css("width", "100%");

	var alph = 'abcdefghijklmnopqrstuvwxyz0123456789_';

	function check_valid_action_name(){
		var val = $("#form_action_create").find("input[name='code']").val().trim(),
			tuval = val,//val.substr(val.indexOf("ACTION_") == 0?7:10000).trim()
			s = "",
			n = tuval.length;

		for(var i = 0; i < n; i++){
			if(alph.indexOf(tuval[i].toLowerCase()) != -1){
				s += tuval[i];	
			}
		}

		$("#form_action_create").find("input[name='code']").val(s);
	}

	$("#form_action_create").find("input[name='code']").keydown(function(){
		check_valid_action_name();
	});

	$("#form_action_create").find("input[name='code']").keyup(function(){
		check_valid_action_name();
	});

	$("#form_action_create").find("input[name='code']").change(function(){
		check_valid_action_name();
	});

	$("#form_action_create").find("input[name='code']").blur(function(){
		check_valid_action_name();
	});

	$("#form_action_create").find("input[name='code']").focus(function(){
		check_valid_action_name();
	});

	document.getElementById("form_action_create").onsubmit = function(e){
		e.preventDefault();

		var code = $(this).find("input[name='code']").val().trim(),
			name = $(this).find("input[name='name']").val().trim(),
			description = $(this).find("textarea[name='description']").val().trim();

		for(var i = 7; i < code.length; i++){
			if(alph.indexOf(code[i]) == -1){
				return;	
			}
		}

		var statuses = $(this).find("select").val();

		if(typeof statuses != "object"){
			statuses = [statuses];
		}

		App.LockScreen();
		App.DOM_Disabling($("#modal_action_create"));
		App.ShowLoading(App.terms.str_creating_action);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/action",
				data:{
					code:code,
					name:name,
					description:description,
					status:statuses
				},success:function(d){
					App.addItemToDOM(d);

					$("#modal_action_create").modal("hide");
					$("#form_action_create").find("	input[name='code'],\
													input[name='name'],\
													textarea[name='description']").val("");

					if(addAnother){
						setTimeout(function(){
							$("#modal_action_create").modal("show");
						}, 1000);	
					}

					addAnother=false;
					$(".select2-container").css("width", "100%");
				},error:function(d, e, f){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_action_create"));
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