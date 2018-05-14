@if ($role_actions["create"])
	<div class = "pull-right">
		@if (isset($extra))
			@foreach ($extra as $button)
				<button class = "btn btn-{!! $button['class'] !!}" id = "{!! $button['id']  or '' !!}" style = "margin-top:7px;" data-href = "modal" data-modal="{!! $button['model'].'@'.$button['operation'] !!}">
					{!! term($button["term"]) !!}
				</button>
			@endforeach
		@endif

		<button class = "btn btn-primary" id = "{!! $id or '' !!}" style = "margin-top:7px;" data-modal="{!! $model !!}@create">
			{!! term("str_add") !!}
		</button>
	</div>
@endif