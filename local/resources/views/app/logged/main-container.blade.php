	<section role="main" class="content-body" ui-view style = "background-color:white;" id = "main_container">
		<header class="page-header">
			{{-- link to go to the past section --}}
				<h2><i class = "fa fa-chevron-left" style = "cursor:pointer;display:none;" id = "go_back_section"></i>&nbsp;&nbsp;<span id = "title_section"></span></h2>

			{{-- link to go to gome --}}
				<div class="right-wrapper pull-right">
					<ol class="breadcrumbs">
						<li>
							<a href="./">
								<i class="fa fa-home"></i>
							</a>
						</li>
					</ol>
					&nbsp;&nbsp;&nbsp;
				</div>
		</header>
		<div class = "container" style = "width:100%;">
			<div class = "row" style = "padding-left:2%;padding-right:2%;">
				<div class = "col-sm-9">
					{{-- control to show/hide basic user configs, available for all sections --}}
						<div class = "row">
							<div class = "col-sm-12">
								<strong><a href = "javascript:;" onclick = "$('#global_configs').toggle(200);">{!! term("str_settings", true) !!}</a></strong>
							</div>
						</div>

					{{-- basic user configs --}}
						<div class="row"  id = "global_configs">
							{{-- app language --}}
								<div class = "col-sm-3">
									<br>
									<p><strong>{!! term("str_language_navigation", true) !!}</strong></p>
									<select class = "form-control" id = "select_set_user_session_lng" style = "width:100%;">
										@foreach ($languages as $key => $value)
											<option value = "{!! $value["code"] !!}" {!! $value["code"] == __LNG__?"selected":"" !!}>
												{!! translate($value["name"]) !!}
											</option>
										@endforeach
									</select>
								</div>

							{{-- format show items --}}
								<div class = "col-sm-3">
									<br>
									<p><strong>{!! term("str_format_to_show_items", true) !!}</strong></p>
									<input type = "radio" value = "progressive" name = "global_format_show_items" {!! $userPreferences["format_show_items"] == "progressive"?"checked":"" !!}>&nbsp;<span>{!! term("str_progressive_load", true) !!}</span>
									<br>
									<input type = "radio" value = "pagination" name = "global_format_show_items" {!! $userPreferences["format_show_items"] == "pagination"?"checked":"" !!}>&nbsp;<span>{!! term("str_pagination", true) !!}</span>
								</div>

							{{-- format edit items --}}
								<div class = "col-sm-3">
									<br>
									<p><strong>{!! term("str_format_to_edit_items", true) !!}</strong></p>
									<input type = "radio" value = "inline" name = "global_format_edit_items" {!! $userPreferences["format_edit_items"] == "inline"?"checked":"" !!}>&nbsp;<span>{!! term("str_inline", true) !!}</span>
									<br>
									<input type = "radio" value = "modal" name = "global_format_edit_items" {!! $userPreferences["format_edit_items"] == "modal"?"checked":"" !!}>&nbsp;<span>{!! term("str_modal", true) !!}</span>
								</div>

							{{-- amount items per request --}}
								<div class = "col-sm-3">
									<br>
									<p><strong>{!! term("str_amount_items_to_bring_by_progressive_request", true) !!}</strong></p>
									<form id = "global_form_amount_items_per_request">
										<input type = "text" class = "form-control" id = "global_amount_items_per_request" name = "amount_items_per_request" value = "{!! $userPreferences["amount_items_per_request"] !!}"><br>
										<input type = "submit" class = "btn btn-primary" value = "{!! term("str_save", true) !!}" style = "display:none;" id = "global_amount_items_per_request_submit">
									</form>
								</div>
						</div>

					{{-- amount of items per request settled by user --}}
					<input type = "hidden" id = "AMOUNT_ITEMS_PER_REQUEST" value = {!! $userPreferences["amount_items_per_request"] !!}>
				</div>

				{{-- control to reload the current section --}}
					<div class = "col-sm-3">
						<br>
						<button class = "btn btn-success pull-right btn-block" id = "reload_section" style = "display:none;">{!! term("str_reload_section", true) !!}</button>
					</div>
			</div>
			<div class = "row">
				{{-- container of the section content --}}
					<div id = "content" class = "col-sm-12">
					</div>

				{{-- loading icon, shown when a section is being requested through AJAX --}}
					<div style = "width:100%;display:none;" align = "center" id = "loading_section" class = "col-sm-12">
						<br><br><br>
						<img src="{!! LOADING_ICON !!}" style = "width:15%;">
					</div>

				{{-- message shown when an AJAX request of a section fails --}}
					<div class = "col-sm-12" align = "center" style = "display:none;" id = "error_happened">
						<h2>{!! term("str_there_has_been_an_error", true) !!}</h2>
					</div>
			</div>
		</div>
	</section>
</div>