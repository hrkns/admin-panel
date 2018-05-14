function __action(){
	$("#form_access_delete").submit(function(e){
		e.preventDefault();

		$("li[data-id='"+Section.idnodedelete+"']").remove();
		document.getElementById("content_iframe").contentWindow.$('#data').jstree().delete_node(Section.idnodedelete);
		$("#modal_access_delete").modal("hide");
		document.getElementById("content_iframe").contentWindow.$("#upd_menu").show();
	});
}