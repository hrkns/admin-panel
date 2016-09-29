<div class = "modal fade" id = "{!! 'modal_'.$context.'_'.$action !!}" align = "center" style = "padding:5%;">
	<form id = "{!! 'form_'.$context.'_'.$action !!}" {!! (isset($form) && !$form)?'onsubmit = "return false;"':'' !!}>
		<div class = "modal-content">
			<div class = "modal-header">
				<h4>{!! translate($terms["str_".$context."_".$action]) !!}</h4>
				<span class = "pull-right close-modal" data-dismiss = "modal">X</span>
			</div>
			<div class = "modal-body">