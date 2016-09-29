@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4">
		<br>
		<p>{!! term("str_code") !!}</p>
		<input name = "code" class = "form-control">
		<br>
	</div>
	<div class = "col-sm-4">
		<br>
		<p>{!! term("str_name") !!}</p>
		<input name = "name" class = "form-control">
		<br>
	</div>
	<div class = "col-sm-4">
		<br>
		<p>{!! term("str_description") !!}</p>
		<textarea name = "description" class = "form-control"></textarea>
		<br>
	</div>
	<div class = "col-sm-12" data-controls="statuses">
		<br>
		@include("app.templates.statuses-select")
		<br>
	</div>
</div>
<div class = "row">
	<div class = "col-sm-12" style = "height:350px;overflow:scroll;">
		<table class = "table table-responsive table-bordered table-hover">
			<thead>
				<td>
					<p>{!! term("str_access") !!}</p>
				</td>
				<td align = "center">
					<input class = "form-control" type = "checkbox" data-type-checkbox = "all" data-type-ubic = "parent">&nbsp;
					<br>{!! term("str_select_all") !!}
				</td>
				@foreach ($actions as $value)
					<td align = "center">
						<input class = "form-control" type = "checkbox" data-type-checkbox = "{!! $value->id !!}" data-type-ubic = "parent">&nbsp;<br>{!! translate($value->name) !!}
					</td>
				@endforeach
			</thead>
			<tbody id = "rows_permises">
				@foreach ($sections as $value)
					<tr data-id = "{!! $value->id !!}">
						<td>
							<p>
								{!! translate($value->name) !!}
							</p>
						</td>
						<td>
							<input type = "checkbox" class = "form-control" data-type-checkbox = "all" data-type-ubic = "child">
						</td>
						@foreach ($actions as $value)
							<td>
								<input type = "checkbox" class = "form-control" data-type-checkbox = "{!! $value->id !!}" data-type-ubic = "child" data-type-check = "action">
							</td>
						@endforeach
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@include("app.modals.include.modal-footer")