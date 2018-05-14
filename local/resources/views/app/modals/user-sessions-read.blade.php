@include("app.modals.include.modal-header")

<div style = "overflow:scroll;max-height:300px;">
	<h3 id = "activities_tracking_user_fullname"></h3>
	<br>
	<table class = "table table-responsive table-bordered table-hover">
		<thead>
			<th class = "table-header-field">
				{!! term("str_session_id") !!}
			</th>
			<th class = "table-header-field">
				{!! term("str_info") !!}
			</th>
			<th class = "table-header-field">
				{!! term("str_start") !!}
			</th>
			<th class = "table-header-field">
				{!! term("str_end") !!}
			</th>
			<th class = "table-header-field">
				{!! term("str_options") !!}
			</th>
		</thead>
		<tbody id = "list_sessions">
		</tbody>
	</table>
</div>

@include("app.modals.include.modal-footer")