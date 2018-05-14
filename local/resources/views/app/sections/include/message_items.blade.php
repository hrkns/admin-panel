{{-- messahe shown when there is no elements for show --}}
	<div id = "message_no_items" style = "display:none;" align = "center">
		{!! term("str_no_elements_for_show") !!}
	</div>

{{-- loading animation shown when some items are being requested --}}
	<div id = "loading_items" align = "center" style = "display:none;">
		<img src="{!! LOADING_ICON !!}">
	</div>

{{-- controls shown when the user has selected 'progressive' as the format for showing items --}}
	<div id = "controls_progressive_format" style = "display:none;">
		{{-- message used when the user is able to work with statuses directly --}}
			<div id = "message_amount_items_hidden" style = "margin-bottom:30px;" align = "center">
			</div>

		{{-- controls to request more items (not shown in 'terms-read' view/modal) --}}
			@if(!isset($flagMessageItems))
				{{-- button to request more items --}}
					<div id = "load_more_items">
						<button class = "btn btn-primary btn-block" id = "see_more_items">{!! term("str_see_more") !!}</button>
						<br>
					</div>

				{{-- button to request all the remaining items --}}
					<div id = "div_load_all_items">
						<a href = "javascript:;" id = "load_all_items" class = "pull-right" style = "text-decoration:none;"><strong>{!! term("str_see_all", true) !!}</strong></a>
						<br><br>
					</div>
			@endif
	</div>

{{-- controls of pagination at bottom of list --}}
	<div id = "controls_pagination_format_bottom_list" class = "pagination-container">
		<br><br>
		{!! print_pagination_skeleton($terms); !!}
	</div>

{{-- controls of pagination at bottom of search results --}}
	<div id = "controls_pagination_format_bottom_search" class = "pagination-container">
		<br><br>
		{!! print_pagination_skeleton($terms); !!}
	</div>