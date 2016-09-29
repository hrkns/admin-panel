<br><br><br>
@include("app.sections.include.search-controls")
<div id = "items_controls" class = "items-controls">
	<table class = "table table-responsive table-bordered table-striped table-hover list-items">
		<thead>
			<tr>
				@foreach ($fields as $field)
					<th class = "table-header-field" data-column = "{!! $field['code'] or '' !!}">
						{!! term($field['term']) !!}

						@if($field['code'] == 'options' && $role_actions["update"])
							<button class = "btn btn-info" id = "update_all" style = "display:none;"><i class = "fa fa-save"></i></button>
							<div id = "relleno_last_column">
								<br><br>
							</div>
						@else
							<br><br><br>
						@endif
					</th>
				@endforeach
			</tr>
		</thead>
		<tbody id = "list_items">
		</tbody>
	</table>
	<br><br>
	<?php
		$config = [];

		if(isset($flagMessageItems)){
			$config["flagMessageItems"] = true;
		}
	?>

	@include("app.sections.include.message_items", $config)
</div>