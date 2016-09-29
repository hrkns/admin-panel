<div class = "modal fade" id = "modal_terms_read" style = "padding:5%;">
	<div class = "modal-content">
		<div class = "modal-header" align = "center">
			<h4>{!! term("str_terms_edition") !!} (<span id = "modal_terms_read_title"></span>)</h4>
			<span class = "pull-right close-modal" data-dismiss = "modal">X</span>
		</div>
		<div class = "modal-body">
			<div class = "row">
				<div class = "col-sm-6">
					<button class = "btn btn-block" id = "show_managing_controls">{!! term("str_terms_management") !!}</button>
				</div>
				<div class = "col-sm-6">
					<button class = "btn btn-block" id = "show_cloning_controls">{!! term("str_terms_clonation") !!}</button>
				</div>
			</div>
			<br><br>
			<div id = "managing_controls">
				@include("app.sections.include.control-create", [
					"model" => "term",
					"id" => "create_term_modal_button"
				])
				@include("app.sections.include.items-list", [
					"fields" => [[
							"code" => "id",
							"term" => "str_id",
						],[
							"code" => "language",
							"term" => "str_language",
						],[
							"code" => "code",
							"term" => "str_code",
						],[
							"code" => "name",
							"term" => "str_name",
						],[
							"code" => "description",
							"term" => "str_description",
						],[
							"code" => "value",
							"term" => "str_value",
						],[
							"code" => "statuses",
							"term" => "str_statuses",
						],[
							"code" => "options",
							"term" => "str_options",
						]
					],
					"flagMessageItems" => true
				])
			</div>
			<div id = "cloning_controls">
				@if($role_actions["create"])
					<div class = "row">
						<div class = "col-sm-12">
							<p><strong>{!! term("str_title_terms_cloning") !!} </strong> {!! term("str_description_terms_cloning") !!} </p>
							<select id = "sections_for_clone_terms" multiple>
							</select>
						</div>
					</div>
					<div class = "row">
						<div class = "col-sm-4">
							<br>
							<input type = "radio" value = "ignore" name = "clone_terms_options" id = "clone_terms_ignore">&nbsp;{!! term("str_terms_cloning_radio_1") !!}
						</div>
						<div class = "col-sm-4">
							<br>
							<input type = "radio" value = "overwrite" name = "clone_terms_options" id = "clone_terms_overwrite">&nbsp;{!! term("str_terms_cloning_radio_2") !!}
						</div>
						<div class = "col-sm-4">
							<br>
							<input type = "radio" value = "append_sufix" name = "clone_terms_options" id = "clone_terms_append_sufix" checked>&nbsp;{!! term("str_terms_cloning_radio_3") !!}
						</div>
					</div>
					<div class = "row">
						<div class = "col-sm-12" align = "center">
							<br>
							<button class = "btn btn-primary" id = "clone_terms">{!! term("str_clone") !!}</button>
							<br>
							<br>
						</div>
					</div>
				@endif
			</div>
		</div>
		<div class = "modal-footer">
			<button  class = "btn btn-danger" onclick = "return false;" data-dismiss = "modal">
				{!! term("str_cancel") !!}
			</button>
		</div>
	</div>
</div>