function __action(){
	document.getElementById("add_field_edit_product").onclick = function(e){
		e.preventDefault();

		if(Section.permises["update"]){
			Section.addField("edit");
		}
	}

	var updatingEst = false;

	document.getElementById("form_product-structure_edit").onsubmit = function(e){
		e.preventDefault();

		if(updatingEst){
			return;
		}

		var fields = [];

		$("#list_fields_edit_product").children().each(function(){
			fields.push({
				id:$(this).find("input[name='id']").val().trim(),
				code:$(this).find("input[name='code']").val().trim(),
				name:$(this).find("input[name='name']").val().trim(),
				description:$(this).find("textarea").val().trim()
			});
		});

		App.LockScreen();
		App.DOM_Disabling($("#modal_product-structure_edit"));
		updatingEst = true;
		App.ShowLoading(App.terms.saving_product_structure);

		App.HTTP.update({
			url:App.WEB_ROOT+"/product/"+Section.ID_PRODUCT_EDITING+"/structure",
			data:{
				fields:fields
			},success:function(d, e, f){
				$("#modal_product-structure_edit").modal("hide");
			},error:function(x, y, z){
			},after:function(){
				App.UnlockScreen();
				App.DOM_Enabling($("#modal_product-structure_edit"));
				updatingEst = false;
				App.HideLoading();
			}
		});
	}
}