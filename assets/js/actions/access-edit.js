function __action(){
	for(var i in Section.icons){
		$("#access_update_list_icons").append(Section.createIcon(Section.icons[i]));
	}

	$("#form_access_edit").submit(function(e){
		e.preventDefault();

		var route = $(this).find("input[name='route']").val().trim(),
			name = $(this).find("input[name='name']").val().trim(),
			coin = $("li[data-route='"+route+"']");

		if(route.length > 0 && name.length > 0 && (coin.length == 0 || Number($(coin[0]).attr("data-id")) == Number(Section.idnodediting))){
			$("li[data-id='"+Section.idnodediting+"']").attr("data-route", route);
			$("li[data-id='"+Section.idnodediting+"']").attr("data-text", name);
			$("li[data-id='"+Section.idnodediting+"']").attr("data-icon", $("#access_update_list_icons").find("div[data-selected='1']").find("i").attr("class"));

			document.getElementById("content_iframe").contentWindow.document.getElementById(Section.idnodediting+"_anchor").childNodes[2].nodeValue = name;
			$("#modal_access_edit").modal("hide");
			document.getElementById("content_iframe").contentWindow.$("#upd_menu").show();
		}else{
		}
	});
}