<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>jstree basic demos</title>
	<style>
	html { margin:0; padding:0; font-size:62.5%; }
	body { max-width:800px; min-width:300px; margin:0 auto; padding:20px 10px; font-size:14px; font-size:1.4em; }
	h1 { font-size:1.8em; }
	.demo { overflow:auto; border:1px solid silver; min-height:100px; }
	</style>
	<link rel="stylesheet" href="./../../dist/themes/default/style.min.css" />
</head>
<body>
	<div id="data" class="demo"></div><br>
	<a href = "javascript:;"><button id = "add_menu" style = "font-weight: bold; text-transform: uppercase; padding: 1%; border: medium none; background-color: rgb(51, 102, 153);">Agregar Menu Fuente</button></a>
	<a href = "javascript:;"><button id = "upd_menu" style = "display:none;font-weight: bold; text-transform: uppercase; padding: 1%; border: medium none; background-color: rgb(51, 102, 153);">Guardar Cambios</button></a><br><br>
	<strong><a href = "javascript:;" id = "reload_page" style = "display:none;">Recargar pagina ver cambios</a></strong>
    <script src="../../../../libs/jquery/jquery.js"></script>
	<script src="./../../dist/jstree.min.js"></script>
	<script src="./../../src/jstree.checkbox.js"></script>
	<script src="./../../src/jstree.dnd.js"></script>
	<script src="./../../src/jstree.contextmenu.js"></script>
	<script>
		var data = [];
		var rands, idnodeparent, name;

		function __iframe__(){
			function eval_it(chld, prt){
				var nchld = chld.length;
				for(var i = 0; i < nchld; i++){
					if(chld[i].nodeType == Node.ELEMENT_NODE && chld[i].tagName.toLowerCase() == "li"){
						var x = {
							id: chld[i].getAttribute("data-id"),
							parent:prt,
							text:chld[i].getAttribute("data-text"),
							data:{
								text:chld[i].getAttribute("data-text"),
								route:chld[i].getAttribute("data-route"),
								id:chld[i].getAttribute("data-id"),
							}
						}
						data.push(x);
						var tmp = chld[i].childNodes;
						var nt = tmp.length;
						var j = 0;
						var cond = false;
						while(j < nt && !cond){
							cond = tmp[j].nodeType == Node.ELEMENT_NODE && tmp[j].tagName.toLowerCase() == "ul";
							if(cond)
								eval_it(tmp[j].childNodes, chld[i].getAttribute("data-id"));
							j++;
						}
					}
				}
			}

			eval_it(window.parent.document.getElementById("dirs_str").childNodes, "#");

			$('#data').jstree({
				'core' : {
					'data' : data,
					"check_callback" : true
				},
				"checkbox" : {
					"keep_selected_style" : false
				},
				"plugins" : [ "contextmenu", "checkbox","dnd" ],
				"contextmenu":{         
					"items": window.parent.Section.items
				},
				changed:function(e, data){
				}
			}).on('create_node.jstree', function(e, data) {});

			$("#add_menu").click(function(){
				window.parent.Section.idnodeparent = "#";
				window.parent.App.getView("access", "create");
			});

			$('#data').on("move_node.jstree", function (e, data) {
				window.parent.Section.move_node(data.node.id, data.parent, data.position);
				$("#upd_menu").show();
			});

			$("#upd_menu").click(function(){
				window.parent.Section.update_menu();
				$("#upd_menu").show();
			});

			$("#reload_page").click(function(){
				window.parent.location.href = window.parent.location.href;
			});

			if(!window.parent.Section.permises["create"]){
				$("#add_menu").remove();
			}

			if(!window.parent.Section.permises["update"]){
				$("#upd_menu").remove();
			}
		}

		setTimeout(__iframe__, 1500);
	</script>
</body>
</html>