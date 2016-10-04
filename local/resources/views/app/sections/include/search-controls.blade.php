<?php
	$languages = GetForUse("MasterLanguage");
?>

{{-- container of controls for searching --}}
	<div id = "container_controls">
		{{-- control to show/hide the controls for searching --}}
			<div style = "margin-bottom:15px;">
				<a href = "javascript:;" onclick = "$('#div_search_controls').toggle(200);"><strong>{!! term("str_search_controls") !!}</strong></a>
			</div>

		{{-- container for controls for searching --}}
			<div style = "display:none;" id = "div_search_controls">
				<div class = "row">
					{{-- controls for choosing the status to show --}}
						<div class = "col-sm-4" id = "col_see_with_status">
							{{-- if an item has one of the next statuses, it can be deleted --}}
								<select id = "status_for_delete" style = "display:none;">
									@foreach ($list_status as $key => $value) 
										if($value["for_delete"] == "1"){
										?>
										<option value = "{!! $value["id"] !!}" {!! $value["show_default"]=="1"?"selected":"" !!}>{!! translate($value["name"]) !!}</option>
									@endforeach
								</select>
								<br>

							{{-- if an item has one of status selected of the next select multiple, the item is shown --}}
								<p><strong>{!! term("str_text_statuses_to_show") !!}</strong></p>
								<select multiple id = "see_with_status" data-select-type = "status">
									@foreach ($list_status as $key => $value) 
										<option data-code = "{!! $value["code"] !!}" value = "{!! $value["id"] !!}" {!! $value["show_default"]=="1"?"selected":"" !!}>{!! translate($value["name"]) !!}</option>
									@endforeach
								</select>

								<br><br>

							{{-- if the next control is selected, all the items, no matter the status it has, is shown --}}
								<input type = "checkbox" id = "see_all_items">&nbsp;{!! term("str_checkbox_for_show_all_items") !!}.
						</div>
					<div class = "col-sm-4" id = "col_items_language" data-column="language">
						{{-- languages available in the system to show items (remember, is a multilingual system) --}}
							<br>
							<p><strong>{!! term("str_text_language_to_show_items") !!}</strong></p>
							<select class = "form-control" id = "set_items_language">
								@foreach ($languages as $key => $value) 
									<option value = "{!! $value["code"] !!}" {!! $value["code"] == __LNG__?"selected":"" !!}>
										{!! translate($value["name"]) !!}
									</option>
								@endforeach
							</select>
					</div>
					<div class = "col-sm-4" id = "col_search_text">
						{{-- keywords for searching --}}
							<br>
							<p><strong>{!! term("str_text_description_of_input_text_search") !!}</strong></p>
							<input class = "form-control" style = "width:100%;" id = "input_text_search">
					</div>
				</div>
				<div class = "row">
					<div class = "col-sm-4" id = "col_select_page" style = "display:none;">
						{{-- control shown when the pagination format is being used, contains in a select all the pages of the current list of items --}}
							<br>
							<p><strong>{!! term("str_go_to_page", true) !!}</strong></p>
							<select id = "pages_of_list" class = "form-control" style = "display:none;">
							</select>
							<select id = "pages_of_search" class = "form-control" style = "display:none;">
							</select>
					</div>
				</div>
				<div class = "row" style = "margin-top:10px;margin-bottom:10px;">
					{{-- control shown when the user is located in the search results --}}
						<div class = col-sm-6>
							<a href = "javascript:;" class = "pull-left" id = "go_back_section_interface" style = "display:none;"><strong>{!! term("str_go_back") !!}</strong></a>
						</div>

					{{-- control shown when the user is located in the main list of items, and a searching has been performed previously --}}
						<div class = col-sm-6>
							<a href = "javascript:;" class = "pull-right" id = "go_forward_results" style = "display:none;"><strong>{!! term("str_go_to_results") !!}</strong></a>
						</div>
				</div>
			</div>
	</div>

{{-- controls of pagination at the top of the list of items --}}
	<div id = "controls_pagination_format_top_list" class = "pagination-container">
		{!! print_pagination_skeleton($terms) !!}
	</div>

{{-- controls of pagination at the top of the search results --}}
	<div id = "controls_pagination_format_top_search" class = "pagination-container">
		{!! print_pagination_skeleton($terms) !!}
	</div>

{{-- search results context, the controls and initial content are copied from 'item_controls', located at the view 'items-list', in order to learn more, check in 'admin-panel.js' the code tagged with the comment 'MAIN_DIV_SEARCH_RESULTS born'--}}
	<div id = "main_div_search_results" style = "display:none;">
	</div>