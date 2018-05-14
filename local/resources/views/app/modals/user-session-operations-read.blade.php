@include("app.modals.include.modal-header")

<div style = "overflow:scroll;max-height:300px;">
	<h5 id = "session_operations_history_user_fullname"></h5>
	<p>(<span id = "session_operations_history_start"></span>) - (<span id = "session_operations_history_end"></span>)</p>
	<br>
	<table class = "table table-responsive table-bordered table-hover">
		<thead>
			<th class = "table-header-field">
				{!! term("str_operation_id") !!}
			</th>
			<th class = "table-header-field">
				{!! term("str_date") !!}
			</th>
			<th class = "table-header-field">
				{!! term("str_operation") !!}
			</th>
			<th class = "table-header-field">
				{!! term("str_info") !!}
			</th>
		</thead>
		<tbody id = "list_operations">
		</tbody>
	</table>
</div>

@include("app.modals.include.modal-footer")