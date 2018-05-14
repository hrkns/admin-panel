<?php
	use App\Models\PanelAdminSection;
?>

<br>

<div class = "row" style = "margin-right:5px;">
	<div class = "col-sm-6">
		<h4 style = "padding:0% 0% 0% 3%;">{!! term("str_title_description_section") !!}</h4>
	</div>
	<div class = "col-sm-3">
		<button class = "btn btn-block btn-primary" id = "btn_import_dict">
			{!! term("str_import_dictionary") !!}
		</button>
	</div>
	<div class = "col-sm-3">
		<button class = "btn btn-block btn-danger" id = "show_checkboxes_export_dictionary">
			{!! term("str_export_dictionary") !!}
		</button>
	</div>
</div>

<ul id = "tree_sections">
	<?php
		function print_item_menu($item, $pre_route=""){
			$children = PanelAdminSection::where("id_parent", "=", $item->id)->orderBy("position", "asc")->get();
			$n = $children->count();

			?>
			<li>
				<strong>
					<a onclick = "Section.getTerms(this);" href="javascript:;" data-id="{!! $item->id !!}" data-route="{!! $pre_route.$item->route_name !!}">
						<span class="title" id = "title_section_{!! $item->id !!}"> {!! translate($item->name) !!} </span>
					</a>
				</strong>
				<input type = "checkbox" data-type-checkbox="select-to-export" checked style = "display:none;" data-id-section = "{!! $item->id !!}">
				<?php
				if($n>0){
					?>
					<ul>
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
		foreach ($items as $key => $value){
			print_item_menu($value, WEB_ROOT."/");
		}
	?>
	<li>
		<strong>
			<a onclick = "Section.getTerms(this);" href="javascript:;" data-id="general" data-route="*">
				<span class="title" id = "title_section_general"> {!! term("str_general") !!} </span>
			</a>
		</strong>
		<input type = "checkbox" data-type-checkbox="select-to-export" checked style = "display:none;" data-id-section = "*">
	</li>
</ul>

@include("app.sections.include.section-resources")