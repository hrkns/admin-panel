function __action(){
	$("#form_user-account-recovering_create").submit(function(e){
		e.preventDefault();
		Section.functionAcceptRecoveringAccountRequest();
	});

	$("input[name='radio_choice_recovering_account_mode']").click(function(){
		if(this.value == "link"){
			$("#new_password_recover_account").hide(App.TIME_FOR_HIDE);
		}else{
			$("#new_password_recover_account").show(App.TIME_FOR_SHOW);
		}
	});
}