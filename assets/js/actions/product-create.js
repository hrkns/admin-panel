//"use strict";
function __action(){
/**/
	$("#modal_product_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_product_create").find("select").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("form_product_create").onsubmit = function(e){
		e.preventDefault();

		var code = $(this).find("input[name='code']").val().trim(),
			name = $(this).find("input[name='name']").val().trim(),
			description = $(this).find("textarea[name='description']").val().trim(),
			statuses = $(this).find("select").val(),
			fields = [],
			instances = [],
			cond = false;

		if(typeof statuses != "object"){
			statuses = [statuses];
		}
		$("#list_fields_new_product").children().each(function(){
			if(cond){
				fields.push({
					code:$(this).find("input[name='code']").val().trim(),
					name:$(this).find("input[name='name']").val().trim(),
					description:$(this).find("textarea").val().trim()
				});
			}

			cond=true;
		});

		$("#list_instances_new_product").children().each(function(){
			var cond = false,
				vals = [];

			$(this).children().each(function(){
				if(cond){
					vals.push($(this).find("input").val().trim());
				}

				cond=true;
			});

			instances.push(vals);
		});

		App.LockScreen();
		App.DOM_Disabling($("#modal_product_create"));
		App.ShowLoading(App.terms.str_creating_product_service);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/product",
				data:{
					code:code,
					name:name,
					description:description,
					status:statuses,
					fields:fields,
					instances:instances
				},success:function(d, e, f){
					App.addItemToDOM(d);

					$("#modal_product_create").modal("hide");
					$("#form_product_create").find("	input[name='code'],\
														input[name='name'],\
														textarea[name='description']").val("");
					$(".select2-container").css("width", "100%");

					if(addAnother){
						setTimeout(function(){
							$("#modal_product_create").modal("show");
						}, 1000);	
					}

					$("#list_fields_new_product").empty();
					addAnother=false;
				},error:function(x, y, z){
				},after:function(){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_product_create"));
					App.HideLoading();
				}
			});
		}, App.RETARD_MULTIPLE_LOAD);
	}

	var addAnother = false;

	document.getElementById("load_and_another").onclick = function(e){
		addAnother=true;
	}

	document.getElementById("add_field_new_product").onclick = function(e){
		e.preventDefault();
		Section.addField("new");
	}

	document.getElementById("add_instance_new_product").onclick = function(e){
		e.preventDefault();
		Section.addInstance("new");
	}
}