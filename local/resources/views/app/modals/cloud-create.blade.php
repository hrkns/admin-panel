@include("app.modals.include.modal-header")

<div class = "row">
	<div class = "col-sm-4" style = "margin:0px;padding:0px;">
		<button class = "btn btn-block" data-toggle-div="create_dir">
		<strong>{!! term("str_create_directory") !!}</strong></button>
	</div>
	<div class = "col-sm-4" style = "margin:0px;padding:0px;">
		<button class = "btn btn-block" data-toggle-div="create_file"><strong>{!! term("str_create_file") !!}</strong></button>
	</div>
	<div class = "col-sm-4" style = "margin:0px;padding:0px;">
		<button class = "btn btn-block" data-toggle-div="upload_file"><strong>{!! term("str_upload_file") !!}</strong></button>
	</div>
</div>
<div class = "row">
	<div class = "col-sm-12" style = "display:none;overflow:scroll;" data-div-toggle="create_dir">
		<br>
		<h4>{!! term("str_directories") !!}</h4>
		<br>
		<table class = "table table-responsive table-bordered table-hover">
			<thead>
				<th>
				</th>
				<th class = "file-manager-header-field">
					{!! term("str_name") !!}
				</th>
				<th class = "file-manager-header-field">
					{!! term("str_description") !!}
				</th>
				<th class = "file-manager-header-field">
					{!! term("str_parent_directory") !!}
				</th>
			</thead>
			<tbody id = "list_directories">
				<tr data-pre="1">
					<td colspan = "4">
						<button class = "btn btn-block btn-primary" id = "add_dir">
							<i class = "fa fa-plus">&nbsp;{!! term("str_add") !!}</i>
						</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class = "col-sm-12" style = "display:none;overflow:scroll;" data-div-toggle="create_file">
		<br>
		<h4>{!! term("str_files_to_create") !!}</h4>
		<br>
		<table class = "table table-responsive table-bordered table-hover">
			<thead>
				<th>
				</th>
				<th class = "file-manager-header-field">
					{!! term("str_name") !!}
				</th>
				<th class = "file-manager-header-field">
					{!! term("str_description") !!}
				</th>
				<th class = "file-manager-header-field">
					{!! term("str_parent_directory") !!}
				</th>
			</thead>
			<tbody id = "list_files">
				<tr data-pre="2">
					<td colspan = "4">
						<button class = "btn btn-block btn-primary" id = "add_file">
							<i class = "fa fa-plus">&nbsp;{!! term("str_add") !!}</i>
						</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class = "col-sm-12" style = "display:none;overflow:scroll;" data-div-toggle="upload_file">
		<br>
		<h4>{!! term("str_files_to_upload") !!}</h4>
		<br>
		<table class = "table table-responsive table-bordered table-hover">
			<thead>
				<th>
				</th>
				<th class = "file-manager-header-field">
					{!! term("str_name") !!}
				</th>
				<th class = "file-manager-header-field">
					{!! term("str_description") !!}
				</th>
				<th class = "file-manager-header-field">
					{!! term("str_parent_directory") !!}
				</th>
				<th class = "file-manager-header-field">
					{!! term("str_file") !!}
				</th>
			</thead>
			<tbody id = "list_upload_files">
				<tr data-pre="3">
					<td colspan = "5">
						<button class = "btn btn-block btn-primary" id = "add_upload_file">
							<i class = "fa fa-plus">&nbsp;{!! term("str_add") !!}</i>
						</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

@include("app.modals.include.modal-footer")