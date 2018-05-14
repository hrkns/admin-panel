@include ("app.modals.include.modal-header", ["form"=>false])

<div class = "row">
	<div class="btn-group col-sm-12" id = "buttons_list_apis">
	</div>
	<div class = "col-sm-12">
		<div class = "container" style = "width:100%;">
			<br><br>
			<a href="javascript:;" id = "show_hide_apis_controls">
				<strong>
					{!! term("str_show_hide_api_controls") !!}
				</strong>
			</a>
			<br><br>
			<div class = "row" id = "configs_apis" style = "display:none;padding:2%;">
				<div id = "configs_fixerio" style = "overflow:scroll;max-height:300px;">
					<table class = "table table-responsive table-bordered table-hover">
						<thead>
							<th>
								<div style = "width:100%;" align = "center">
									<input type = "checkbox" id = "fixerio_select_all">
								</div>
							</th>
							<th>
								<div style = "width:100%;" align = "center">
									{!!  term("str_currency_1") !!}
									<br>

									<select style = "display:;" id = "fixerio_currencies_1" multiple>
										@foreach ($currencies as $key => $value)
											<option value = "{!!  $value->id !!}" data-code = "{!!  $value->code !!}">{!!  translate($value->name) !!}</option>
										@endforeach
									</select>
									<br>
									<input type = "checkbox" id = "fixerio_selectall_1" >&nbsp;{!! term("str_select_all") !!}
								</div>
							</th>
							<th>
								<div style = "width:100%;" align = "center">
									=
								</div>
							</th>
							<th>
								<div style = "width:100%;" align = "center">
									{!!  term("str_value") !!}
									<br>
									<button class = "btn btn-block btn-success" id = "fixerio_exchange">
										{!!  term("str_exchange") !!}
									</button>
								</div>
							</th>
							<th>
								<div style = "width:100%;" align = "center">
									{!!  term("str_currency_2") !!}
									<br>
									<select id = "fixerio_currencies_2" style = "display:;" multiple>
										@foreach ($currencies as $key => $value)
											<option value = "{!!  $value->id !!}" data-code = "{!!  $value->code !!}">{!!  translate($value->name) !!}</option>
										@endforeach
									</select>
									<br>
									<input type = "checkbox" id = "fixerio_selectall_2" >&nbsp;{!! term("str_select_all") !!}
								</div>
							</th>
							<th>
								<div style = "width:100%;" align = "center">
									{!!  term("str_options") !!}
									<br>
									<button class = "btn btn-info" id = "fixerio_use_selected">
									{!!  term("str_use_selected") !!}
									</button>
								</div>
							</th>
						</thead>
						<tbody id = "fixerio_list_exchanges">
						</tbody>
					</table>
				</div>
				<div id = "configs_currencylayer">
				</div>
				<div id = "configs_openexchange">
				</div>
				<div id = "configs_xecurrency">
				</div>
				<div id = "configs_freecurrency">
				</div>
				<div id = "configs_jsonrates">
				</div>
				<div id = "configs_currencyapi">
				</div>
				<div id = "configs_xignite">
				</div>
				<div id = "configs_getexchangerates">
				</div>
			</div>
		</div>
	</div>
</div>
<hr>
<div class = "row">
	<div class = "col-sm-12" style = "overflow:scroll;max-height:300px;" id = "parent_list_exchanges">
		<table class = "table table-responsive table-bordered table-hover">
			<thead>
				<th>
				</th>
				<th>
					<div style = "width:100%;" align = "center">
						{!!  term("str_currency_1") !!}
						<br>
						<select id = "exchanges_currencies_1" style = "display:;" multiple>
							@foreach ($currencies as $key => $value)
								<option value = "{!!  $value->id !!}" data-code = "{!!  $value->code !!}">{!!  translate($value->name) !!}</option>
							@endforeach
						</select>
						<br>
						<input type = "checkbox" id = "selectall_1" >&nbsp;{!! term("str_select_all") !!}
					</div>
				</th>
				<th>
					<div style = "width:100%;" align = "center">
						=
					</div>
				</th>
				<th>
					<div style = "width:100%;" align = "center">
						{!!  term("str_value") !!}
					</div>
				</th>
				<th>
					<div style = "width:100%;" align = "center">
						{!!  term("str_currency_2") !!}
						<br>
						<select id = "exchanges_currencies_2" style = "display:;" multiple>
							@foreach ($currencies as $key => $value)
								<option value = "{!!  $value->id !!}" data-code = "{!!  $value->code !!}">{!!  translate($value->name) !!}</option>
							@endforeach
						</select>
						<br>
						<input type = "checkbox" id = "selectall_2" >&nbsp;{!! term("str_select_all") !!}
					</div>
				</th>
				<th>
					<div style = "width:100%;" align = "center">
						{!!  term("str_options") !!}
					</div>
				</th>
			</thead>
			<tbody id = "list_exchanges">
			</tbody>
		</table>
	</div>
		<br>
		<button class = "btn btn-primary btn-block" id = "add_exchange">{!! term("str_add_exchange") !!}</button>
</div>

@include ("app.modals.include.modal-footer", [
	"buttons" => [
		"submit" => false,
		"cancel" => [
			"term" => "str_close"
		]
	]
])