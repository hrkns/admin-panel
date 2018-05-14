//"use strict";
jQuery(document).ready(function() {
	$("#form-login").submit(function(e){
		e.preventDefault();
		var minl1 = 1;
		var minl2 = 1;
		var val1 = $('input[name="username"]').val().trim().length;
		var val2 = $('input[name="password"]').val().length;

		if(val1 < minl1 || val2 < minl2){
			return;
		}

		App.LockScreen();
		App.ShowLoading(App.__GENERAL__.str_authenticating);
		App.DOM_Disabling("form-login");

		App.HTTP.create({
			url: 'session',
			data: {	id:$('input[name="username"]').val(), 
					pass:$('input[name="password"]').val(),
					keep_session_alive:$("#remember").is(":checked")},
			success: function(data){
				App.ShowLoading(App.__GENERAL__.str_entering);
				window.location.reload();
			},error:function(x, y, z){
				App.Alert(x.message);
				App.UnlockScreen();
				App.HideLoading();
				App.DOM_Enabling("form-login");
			},
			log_ui_msg : false
		});
	});

	$("#form-register").submit(function(e){
		e.preventDefault();

		if(!$("#form-register input[type='checkbox']").prop("checked")){
			return;
		}

		var fullname = $(this).find("input[name='fullname']").val().trim();
		var nick = $(this).find("input[name='nick']").val().trim();
		var email = $(this).find("input[name='email']").val().trim();
		var pass = $(this).find("input[name='password']").val().trim();
		var data = {
			fullname:fullname,
			nick:nick,
			email:email,
			pass:pass,
			status:Array()
		};

		data["communication_routes"] = Array();
		data["roles_in_organization"] = Array();
		data["profile_img"] = "default";

		App.LockScreen();
		App.ShowLoading(App.__GENERAL__.str_signing_up);
		App.DOM_Disabling("form-register");

		App.HTTP.create({
			url:App.WEB_ROOT+"/user",
			data:data,
			success:function(d, e, f){
				$("#form-register").find("input").val("");
				$("#form-register").find("input[type='checkbox']").prop("checked", false);
				$("#show_box_login").trigger("click");
				App.Alert(d.data.message)
			},error:function(x, y, z){
				App.Alert(x.message)
			},after:function(){
				App.UnlockScreen();
				App.HideLoading();
				App.DOM_Enabling("form-register");
			},
			log_ui_msg : false
		});
	});
	$("#click_show_box_forgot_from_login").click(function(){
		$("#box_login").hide(App.TIME_FOR_HIDE);
		$("#box_forgot").show(App.TIME_FOR_SHOW);
	});
	$("#show_box_login_from_forgot").click(function(){
		$("#box_forgot").hide(App.TIME_FOR_HIDE);
		$("#box_login").show(App.TIME_FOR_SHOW);
		$("#message_recover_account_success").hide();
		$("#form_forgot").find("input[name='email']").val("");
	});
	$("#show_login_from_forgot").click(function(){
		$("#box_forgot").hide(App.TIME_FOR_HIDE);
		$("#box_login").show(App.TIME_FOR_SHOW);
		$("#message_recover_account_success").hide();
		$("#form_forgot").find("input[name='email']").val("");
	});
	$("#show_create_account").click(function(){
		$("#box_login").hide(App.TIME_FOR_HIDE);
		$("#box_register").show(App.TIME_FOR_SHOW);
	});
	$("#show_box_login").click(function(){
		$("#box_register").hide(App.TIME_FOR_HIDE);
		$("#box_login").show(App.TIME_FOR_SHOW);
	});
	$("#form_forgot").submit(function(e){
		e.preventDefault();
		var email = $(this).find("input[name='email']").val().trim();
		App.LockScreen();
		App.ShowLoading(App.__GENERAL__.str_sending_data);
		App.DOM_Disabling("form-forgot");

		App.HTTP.post({
			url:App.WEB_ROOT+"/account-recovering",
			data:{
				email:email
			},success:function(d, e, f){
				App.Alert(d.data.message);
				$("#box_forgot").hide(App.TIME_FOR_HIDE);
				$("#box_login").show(App.TIME_FOR_SHOW);
				$("#form_forgot").find("input[name='email']").val("");
			},error:function(x, y, z){
				App.Alert(x.message);
			},after:function(){
				App.UnlockScreen();
				App.HideLoading();
				App.DOM_Enabling("form-forgot");
			},
			log_ui_msg : false
		});
	})
});