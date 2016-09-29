@include ("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-12">
		<h4 id = "modal_role_permises_update_title"></h4>
	</div>
	<div class = "col-sm-12" style = "height:350px;overflow:scroll;">
		<table class = "table table-responsive table-bordered table-hover">
			<thead>
				<td>
					<p>{!! term("str_access") !!}</p>
				</td>
				<td align = "center">
					<input type = "checkbox" data-type-checkbox = "all" data-type-ubic = "parent">&nbsp;<br>{!! term("str_select_all") !!}
				</td>
				@foreach ($actions as $value)
					<td align = "center">
						<input type = "checkbox" data-type-checkbox = "{!! $value->id !!}" data-type-ubic = "parent">&nbsp;<br>{!! translate($value->name) !!}
					</td>
				@endforeach
			</thead>
			<tbody id = "rows_permises_update">
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

@include ("app.modals.include.modal-footer")