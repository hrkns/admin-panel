			</div>
			<div class = "modal-footer">
				@if ($action == 'edit')
					@if($role_actions["update"])
						<input type = "submit" value = "{!! translate($terms[(isset($buttons) && isset($buttons['submit']) && isset($buttons['submit']['term']))?$buttons['submit']['term']:'str_save_changes']) !!}" class = "btn btn-{!! (isset($buttons) && isset($buttons['submit']) && isset($buttons['submit']['class']))?$buttons['submit']['class']:'primary' !!}">
					@endif

					<button  class = "btn btn-danger" onclick = "return false;" data-dismiss = "modal">{!! translate($terms[$role_actions["update"]?"str_cancel":"str_close"]) !!}</button>
				@elseif ($action == 'create')
					<input type = "submit" value = "{!! translate($terms[(isset($buttons) && isset($buttons['submit']) && isset($buttons['submit']['term']))?$buttons['submit']['term']:'str_load']) !!}" class = "btn btn-{!! (isset($buttons) && isset($buttons['submit']) && isset($buttons['submit']['class']))?$buttons['submit']['class']:'primary' !!}">
					@if (!isset($buttons) || !isset($buttons["submit_plus"]) || $buttons["submit_plus"])
						<input type = "submit" value = "{!! translate($terms['str_load_and_another']) !!}" class = "btn btn-success" id = "load_and_another">
					@endif
					<button  class = "btn btn-danger" onclick = "return false;" data-dismiss = "modal">{!! translate($terms["str_cancel"]) !!}</button>
				@elseif ($action == 'read')
					<button  class = "btn btn-danger" onclick = "return false;" data-dismiss = "modal">{!! translate($terms["str_close"]) !!}</button>
				@elseif ($action == 'delete')
					<input type = "submit" value = "{!! translate($terms['str_ok']) !!}" class = "btn btn-primary">
					<button  class = "btn btn-danger" onclick = "return false;" data-dismiss = "modal">{!! translate($terms["str_cancel"]) !!}</button>
				@else
					@if (!isset($buttons) || !isset($buttons["submit"]) || $buttons["submit"])
						<input type = "submit" value = "{!! translate($terms[(isset($buttons) && isset($buttons['submit']) && isset($buttons['submit']['term']))?$buttons['submit']['term']:'str_ok']) !!}" class = "btn btn-primary">
					@endif
					<button  class = "btn btn-danger" onclick = "return false;" data-dismiss = "modal">{!! translate($terms[(isset($buttons) && isset($buttons['cancel']) && isset($buttons['cancel']['term']))?$buttons['cancel']['term']:'str_cancel']) !!}</button>
				@endif
			</div>
		</div>
	</form>
</div>