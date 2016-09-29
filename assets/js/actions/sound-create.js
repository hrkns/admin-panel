//"use strict";
function __action(){
/**/
	$("#modal_sound_create").find("select[name='status']").prop("multiple", App.isTrue(Section.CONFIGURATION.statuses.multiple))

	$("#modal_sound_create").find("select").select2();
	$(".select2-container").css("width", "100%");

	document.getElementById("form_sound_create").onsubmit = function(e){
		e.preventDefault();

		var code = $(this).find("input[name='code']").val().trim(),
			name = $(this).find("input[name='name']").val().trim(),
			description = $(this).find("textarea[name='description']").val().trim(),
			statuses = $(this).find("select").val(),
			file = document.getElementById("sound_create_file").files[0];

		if(typeof statuses != "object"){
			statuses = [statuses];
		}

		var formFile = new FormData();
		formFile.append("audio", file);

		App.LockScreen();
		App.DOM_Disabling($("#modal_sound_create"));
		App.ShowLoading(App.terms.str_creating_audio);

		setTimeout(function(){
			App.HTTP.create({
				url:App.WEB_ROOT+"/sound",
				data:{
					code : code,
					name : name,
					description : description,
					status : statuses
				},success:function(d, e, f){
					$.ajax({
						url: App.WEB_ROOT+"/sound/"+d.data.item.id+"/file",
						type: 'POST',
						data: formFile,
						processData: false, 
						contentType: false,
						dataType : "JSON",
						success: function(file){
							d.data.item["file"] = file.filename;
							App.addItemToDOM(d);

							$("#modal_sound_create").modal("hide");
							$("#form_sound_create").find("	input[name='code'],\
															input[name='name'],\
															textarea[name='description']").val("");
							if(addAnother){
								setTimeout(function(){
									$("#modal_sound_create").modal("show");
								}, 1000);	
							}

							addAnother=false;
							$(".select2-container").css("width", "100%");
							$("#sound_create_file").val("");
							App.UnlockScreen();
							App.DOM_Enabling($("#modal_sound_create"));
							App.HideLoading();
						},error:function(x, y, z){
							App.UnlockScreen();
							App.DOM_Enabling($("#modal_sound_create"));
							App.HideLoading();
						}
					});
				},error:function(x, y, z){
					App.UnlockScreen();
					App.DOM_Enabling($("#modal_sound_create"));
				}
			});
		}, App.RETARD_MULTIPLE_LOAD);
	}

	var addAnother = false;

	document.getElementById("load_and_another").onclick = function(e){
		addAnother=true;
	}
}