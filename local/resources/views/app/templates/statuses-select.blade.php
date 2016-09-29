<p>{!! term("str_statuses") !!}</p>
<select multiple name = "status" {!! !$role_actions["update"]?"disabled":"" !!}>
	@foreach ($list_status as $value)
		<option value = "{!! $value["id"] !!}">{!! translate($value["name"]) !!}</option>
	@endforeach
</select>