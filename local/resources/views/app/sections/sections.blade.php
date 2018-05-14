<?php
	use App\Models\PanelAdminSection;
?>

<input type = "hidden" id = "lng" value = '{!! __LNG__ !!}'>

<div style = "padding:2%;" id = "the_container">
	<h1>{!! term("str_accesses") !!}</h1>
	<ul id = "dirs_str" style = "display:none;">
		<?php
			function print_item_menu($item, $pre_route){
				$children = PanelAdminSection::where("id_parent", "=", $item->id)->orderBy("position", "asc")->get();
				$n = $children->count();

				?>
				<li data-id="{!! $item->id !!}" data-route="{!! $item->route_name !!}" data-text="{!! translate($item->name) !!}" data-icon = "{!! $item->icon !!}">
					<?php
					if($n>0){
						?>
						<ul id = "node_ul_{!! $item->id !!}">
							<?php
							foreach ($children as $key => $value)
								print_item_menu($value, $pre_route.$item->route_name."/");
							?>
						</ul>
						<?php
					}
					?>
				</li>
				<?php
			}

			$items = PanelAdminSection::whereNull("id_parent")->where("available_for_use", "=", 1)->orderBy("position", "asc")->get();
			foreach ($items as $key => $value) 
				print_item_menu($value, WEB_ROOT."/");
		?>
	</ul>
	<iframe id = "content_iframe" src="{!! WEB_ROOT !!}/assets/plugins/jstree/demo/basic/index.php" style = "width:100%;height:1000px;border:none;"></iframe>
</div>

@include("app.sections.include.section-resources")