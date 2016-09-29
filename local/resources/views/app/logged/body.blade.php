{{-- visually located at the top of the page --}}
	@include('app.logged.navbar'			, [	"globalPreferences"	=>$globalPreferences, 
												"userPreferences"	=>$userPreferences, 
												"userData"			=>$userData,
												"terms"				=>$terms,
												"roleData"			=>$roleData
	])

{{-- side menu --}}
	@include('app.logged.side-menu')

{{-- the element that contains the sections content --}}
	@include('app.logged.main-container'	, [	"iduser"			=>$iduser,
												"terms"				=>$terms,
												"userPreferences"	=>$userPreferences,
												"languages"			=>$languages
	])

{{-- the js files used for the logged system --}}
	@include('app.logged.footer-js'		, ["iduser"			=>$iduser,
											"terms"			=>$terms
	])

{{-- end of the html documen --}}
	@include('app.logged.html_end')