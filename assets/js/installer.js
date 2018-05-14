(function(){
	$("#form").submit(function(e){
		e.preventDefault();
		App.LockScreen();
		App.ShowLoading(App.__GENERAL__.str_saving_changes);

		App.HTTP.post({
			url : App.WEB_ROOT + "/install",
			data : {
				db : {
					host : $("#form").find("input[name='db_address']").val().trim(),
					name : $("#form").find("input[name='db_name']").val().trim(),
					user : $("#form").find("input[name='db_user']").val().trim(),
					password : $("#form").find("input[name='db_password']").val().trim()
				},
				smtp :{
					host : $("#form").find("input[name='smtp_host']").val().trim(),
					port : $("#form").find("input[name='smtp_port']").val().trim(),
					email : $("#form").find("input[name='smtp_email_from']").val().trim(),
					password : $("#form").find("input[name='smtp_password_from']").val().trim(),
					fullname : $("#form").find("input[name='smtp_fullname_from']").val().trim(),
					secure : $("#form").find("input[name='smtp_secure']").prop("checked")
				}
			},
			success : function(d, e, f){
				window.location.href = App.WEB_ROOT;
			},
			error : function(x, y, z){
				App.UnlockScreen();
				App.HideLoading();
				App.Alert(x.message);
			},
			log_ui_msg : false
		});
	});

	App.abort_session_monitor();
})();