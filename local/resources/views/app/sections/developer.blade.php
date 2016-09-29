<?php
	$list_status = GetForUse("MasterStatus");
	$sections = GetForUse("PanelAdminSection");
?>

<div style = "padding:2%;" id = "the_container">
	<h3><a href = "javascript:;" style = "text-decoration:none;color:black;" id = "use_of_status_toggle">{!! term("str_use_of_statuses") !!}</a></h3>
	<div id = "use_of_status_div" style = "display:;padding-left:5%;overflow:scroll;height:500px;">
		<table class = "table table-bordered table-hover" style = "width:100%;">
			<thead>
				<th>
				</th>
				<th class = "table-header-field">
					{!! term("str_use") !!}
				</th>
				<th class = "table-header-field">
					{!! term("str_choice_default_statuses_value_for_section") !!}
				</th>
				<th class = "table-header-field">
					{!! term("str_choice_permitted_statuses_value_for_section") !!}
				</th>
				<th class = "table-header-field">
					{!! term("str_multiple") !!}
				</th>
			</thead>
			<tbody>
				@foreach ($sections as $section) 
					<tr>
						<td style = "width:200px;">
							<p>
								<strong>
									{!! translate($section["name"]) !!}
								</strong>
							</p>
						</td>
						<td style = "width:50px;">
							<input type = "checkbox" style = "height:25px;width:25px;" data-section-use-statuses="{!! $section->id !!}" {!! isTrue($section->use_statuses)?"checked":"" !!}>
						</td>
						<td>
							<select multiple data-select-values-default="{!! $section->id !!}" style = "display:{!! isTrue($section->use_statuses)?"non":"" !!};">
								@foreach ($list_status as $value)
									<option value = "{!! $value["id"] !!}" {!! in_array($value->id, json_decode($section->statuses_by_default))?"selected":"" !!}>{!! translate($value["name"]) !!}</option>
								@endforeach
							</select>
							<button class = "btn btn-primary" style = "display:none;" data-save-default-values="{!! $section->id !!}"><i class = "icon ion-checkmark-round"></i></button>
						</td>
						<td>
							<select multiple data-select-values-permitted="{!! $section->id !!}">
								@foreach ($list_status as $value)
									<option value = "{!! $value["id"] !!}" {!! in_array($value->id, json_decode($section->permitted_statuses))?"selected":"" !!}>{!! translate($value["name"]) !!}</option>
								@endforeach
							</select>
							<button class = "btn btn-primary" style = "display:none;" data-save-permitted-values="{!! $section->id !!}"><i class = "icon ion-checkmark-round"></i></button>
						</td>
						<td style = "width:50px;">
							<input type = "checkbox" style = "height:25px;width:25px;" data-section-multiple-statuses="{!! $section->id !!}" {!! isTrue($section->multiple_statuses)?"checked":"" !!}>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@include("app.sections.include.section-resources")