function __action(){
	for(var i in Section.icons){
		$("#access_create_list_icons").append(Section.createIcon(Section.icons[i]));
	}

	$("#form_access_create").submit(function(e){
		e.preventDefault();

		var route = $(this).find("input[name='route']").val().trim(),
			name = $(this).find("input[name='name']").val().trim(),
			coin = $("li[data-route='"+App.WEB_ROOT + "/" +route+"']");

		if(route.length > 0 && name.length > 0 && coin.length == 0){
			var rands = String(Math.random()),
				dli = document.createElement("li");

			dli.setAttribute("data-text", name);
			dli.setAttribute("data-route", App.WEB_ROOT + "/" + route);
			dli.setAttribute("data-id", rands);
			dli.setAttribute("data-new", "new");
			dli.setAttribute("data-icon", $("#access_create_list_icons").find("div[data-selected='1']").find("i").attr("class"));

			if(Section.idnodeparent != "#"){
				var ul = $("li[data-id='"+Section.idnodeparent+"']").children();//.find("ul");

				if(ul.length == 0){
					ul = document.createElement("ul");
					ul.id = "node_ul_"+Section.idnodeparent;
					$("li[data-id='"+Section.idnodeparent+"']").append(ul);
				}

				$(ul).append(dli);
			}else{
				$("#dirs_str").append(dli);
			}

			document.getElementById("content_iframe").contentWindow.rands = rands;
			document.getElementById("content_iframe").contentWindow.idnodeparent = Section.idnodeparent;
			document.getElementById("content_iframe").contentWindow.name = name;
			document.getElementById("content_iframe").contentWindow.$('#data').jstree().create_node(
				String(Section.idnodeparent)+(Section.idnodeparent!="#"?"_anchor":""),  
				{ 
					"id" : rands, 
					"text" : name 
				}, 
				"last", function(){
			});

			$("#modal_access_create").modal("hide");
			$(this).find("input[name='route']").val("");
			$(this).find("input[name='name']").val("");
			document.getElementById("content_iframe").contentWindow.$("#upd_menu").show();
		}else{
		}
	});
}