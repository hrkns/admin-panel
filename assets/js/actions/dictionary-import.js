function __action(){
	$("#form_dictionary_import").submit(function(e){
		e.preventDefault();

		if($("#modal_dictionary_import").find("input[name='file']")[0].files.length == 0){
			return;
		}

		var file = $("#modal_dictionary_import").find("input[name='file']")[0].files[0];

		App.DOM_Disabling($("#modal_dictionary_import"))
		App.LockScreen();
		App.ShowLoading(App.terms.str_importing_dictionary);

		var formdata = new FormData();
		formdata.append("file", file);

		$.ajax({
			url : App.WEB_ROOT + "/dictionary-importing",
			data : formdata,
			processData: false,
			contentType: false,
			type : "POST",
			success : function(d){
				$("#modal_dictionary_import").modal("hide");
				App.DOM_Enabling($("#modal_dictionary_import"))
				App.UnlockScreen();
				App.HideLoading();
			},
			error : function(x, y, z){
				App.DOM_Enabling($("#modal_dictionary_import"))
				App.UnlockScreen();
				App.HideLoading();
			},
			after : function(){
				App.DOM_Enabling($("#modal_dictionary_import"))
				App.UnlockScreen();
				App.HideLoading();
			}
		});
	});
}