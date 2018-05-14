<?php
	use App\Models\Directory;
	$directories = Directory::where("id_user", "=", Request::session()->get("iduser"))->get();
	$dirs_select_content = '<option value = "root">'.term("str_root").'</option>';

	foreach ($directories as $key => $value) {
		$dirs_select_content .= '<option value = "'.$value["id"].'">'.$value["name"].'</option>';
	}
?>

<div style = "padding:2%;" id = "the_container">
	<br>
	<select style = "display:none;" id = "directories">
		{!! $dirs_select_content !!}
	</select>
	<div class = "row">
		<div class = "col-sm-12 pull-left ">
			<h1 style = "padding:0px;margin:0px;">{!! term("str_files") !!}</h1>
		</div>
	</div>
	<br>
	<div class = "row">
		<div class = "col-sm-12">
			<div class = "btn-group" id = "lineal_tree">
				<button data-id="root" class = "btn btn-primary" onclick = "Section.enterFromLinealTree('root')">{!! term("str_root") !!}&nbsp;<i class = "fa fa-chevron-right"></i></button>
			</div>
		</div>
		<div class = "col-sm-9">
			<div style = "display:none;" id = "group_options">
				<br>
				<button class = "btn btn-danger" id = "remove_selected_items">
					<i class = "fa fa-remove"></i>
					&nbsp;
					{!! term("str_delete") !!}
				</button>
				<button class = "btn btn-info" id = "compress_selected_items">
					<i class = "fa fa-archive"></i>
					&nbsp;
					{!! term("str_compress") !!}
				</button>
				<button class = "btn btn-warning" id = "move_selected_items_to">
					<i class = "fa fa-arrow-right"></i>
					&nbsp;
					{!! term("str_move_to") !!}
				</button>
				<button class = "btn btn-success" id = "copy_selected_items_to">
					<i class = "fa fa-copy"></i>
					&nbsp;
					{!! term("str_copy_to") !!}
				</button>
				<br>
				<br>
			</div>
		</div>
		<div class = "col-sm-3 pull-right">
			<br>
			<input type = "text" class = "form-control" placeholder = "{!! term("str_write_name_of_file_or_dir_to_search") !!}" id = "keywords">
			<input type = "checkbox" id = "search_keywords_from_current_directory">&nbsp;{!! term("str_search_keywords_from_current_directory") !!}<br>
			<a 	href = "javascript:;" 
				id = "go_to_search_results" 
				style = "display:none;"
			>
				<strong>
					{!! term("str_go_to_search_results") !!}
				</strong>
			</a>
			<a 	href = "javascript:;" 
				id = "go_to_main_view" 
				style = "display:none;"
			>
				<strong>
					{!! term("str_go_to_main_view") !!}
				</strong>
			</a>
			<br>
		</div>
		<br><br><br><br><br>
		<div style = "overflow:scroll;" class = "col-sm-12">
			<table class = "table table-responsive" id = "table_main_view" >
				<thead>
					<th>
						<div align = "center">
							<input type = "checkbox" id = "select_all">
						</div>
					</th>
					<th>
						<div style = "width:100%;" align = "left">
							<strong>
								{!! term("str_name") !!}
							</strong>
						</div>
					</th>
					<th>
						<div style = "width:100%;" align = "left">
							<strong>
								{!! term("str_type") !!}
							</strong>
						</div>
					</th>
					<th>
						<div style = "width:100%;" align = "left">
							<strong>
								{!! term("str_size") !!}
							</strong>
						</div>
					</th>
					<th>
						<div style = "width:100%;" align = "left">
							<strong>
								{!! term("str_options") !!}
							</strong>
						</div>
					</th>
				</thead>
				<tbody id = "list_items">
					<tr data-pre = "5" id = "loading_items" style = "display:none;">
						<td colspan = "5" style = "background-color: white !important;">
							<div style = "width:100%" align = "center">			
								<img style = "width:15%;" src="{!! LOADING_ICON !!}">
							</div>
						</td>
					</tr>
					<tr data-pre = "4">
						<td colspan = "5">
							<div style = "width:100%" align = "center">
								<a href = "javascript:;" style = "text-decoration:none !important;"  data-modal="file-manager@create" data-href='modal' id = "show_modal_create">
									<i class = "fa fa-plus"></i>
									&nbsp;
									{!! term("str_create_directory_or_file") !!}
								</a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

			<div id = "table_search_results" style = "display:none;">
				<h4>{!! term("str_search_results") !!}</h4><br>
				<table class = "table table-responsive">
					<thead>
						<th>
							<div align = "center">
								<input type = "checkbox" id = "select_all_search_results">
							</div>
						</th>
						<th>
							<div style = "width:100%;" align = "left">
								<strong>
									{!! term("str_name") !!}
								</strong>
							</div>
						</th>
						<th>
							<div style = "width:100%;" align = "left">
								<strong>
									{!! term("str_type") !!}
								</strong>
							</div>
						</th>
						<th>
							<div style = "width:100%;" align = "left">
								<strong>
									{!! term("str_size") !!}
								</strong>
							</div>
						</th>
						<th>
							<div style = "width:100%;" align = "left">
								<strong>
									{!! term("str_location") !!}
								</strong>
							</div>
						</th>
						<th>
							<div style = "width:100%;" align = "left">
								<strong>
									{!! term("str_options") !!}
								</strong>
							</div>
						</th>
					</thead>
					<tbody id = "list_items_search_results">
						<tr data-pre = "4" id = "no_results_msg" style = "display:none;">
							<td colspan = "6">
								<div style = "width:100%" align = "center">
									{!! term("str_no_results") !!}
								</div>
							</td>
						</tr>
						<tr data-pre = "5" id = "loading_items_search_results" style = "display:none;background-color: white !important;">
							<td colspan = "6" style = "background-color: white !important;">
								<div style = "width:100%" align = "center">			
									<img style = "width:15%;" src="{!! LOADING_ICON !!}">
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@include("app.sections.include.section-resources")