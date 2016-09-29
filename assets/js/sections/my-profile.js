//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	var originalData = {};
	var sendForm = false;
	var sendingForm = false;

	/***********************************************************************************************************/

	function add_row_communication_route(mode, data){
		var tr = document.createElement("tr");
		var td1 = document.createElement("td");
		var select = document.createElement("select");

		select.className = "form-control";
		select.innerHTML = generateMediaSelect();

		if(typeof data != "undefined"){
			select.value = data.code;
		}

		td1.appendChild(select);

		var td2 = document.createElement("td");
		var value = document.createElement("input");

		value.className = "form-control";

		if(typeof data != "undefined"){
			value.value = data.value;
		}

		td2.appendChild(value);

		var td3 = document.createElement("td");
		var btnrm = document.createElement("button");

		btnrm.innerHTML = App.terms.str_remove;
		btnrm.className = "btn btn-danger";

		btnrm.onclick = function(e){
			e.preventDefault();
			$(tr).remove();
		}

		td3.appendChild(btnrm);

		tr.appendChild(td1);
		tr.appendChild(td2);
		tr.appendChild(td3);
		$("#list_communication_routes_"+mode+"_user").append(tr);
		
		$(select).select2();
	}

	$("#change_profile_img_edit_user").click(function(e){
		e.preventDefault();
		$("#file_profile_img_edit_user").trigger("click");
	});

	$("#remove_profile_img_edit_user").click(function(e){
		e.preventDefault();
		$("#profile_img_edit_user").attr("src", "assets/images/profile/default.jpg");
		$(this).hide(App.TIME_FOR_HIDE);
	});

	$("#file_profile_img_edit_user").change(function(e){
		if(this.files && this.files[0]){
			$("#remove_profile_img_edit_user").show(App.TIME_FOR_SHOW);
			var reader = new FileReader();

			reader.onload = function(e){
				$("#profile_img_edit_user").fadeOut(500, function() {
					$("#profile_img_edit_user").attr('src', e.target.result);
			    }).fadeIn(500);
			};

			reader.readAsDataURL(this.files[0]);
		}
	});

	$("#form_user_update").submit(function(e){
		e.preventDefault();

		if(!sendForm){
			return;
		}

		var fullname = $("#form_user_update").find("input[name='fullname']").val().trim();
		var nick = $("#form_user_update").find("input[name='nick']").val().trim();
		var email = $("#form_user_update").find("input[name='email']").val().trim();
		var pass = $("#form_user_update").find("input[name='password']").val().trim();
		var data = {
			fullname:fullname,
			nick:nick,
			email:email,
			pass:pass
		};

		var lcoms = [];

		$("#list_communication_routes_edit_user").children().each(function(){
			var x = {};
			x.code = $(this).find("select").val();
			x.value = $(this).find("input").val().trim();
			lcoms.push(x);
		});

		data["media"] = lcoms;
		data["profile_img"] = $("#profile_img_edit_user").attr("src");

		App.LockScreen();
		App.DOM_Disabling($("#form_user_update"));
		sendingForm = true;
		App.ShowLoading(App.terms.str_saving_changes);

		App.HTTP.update({
			url:App.WEB_ROOT+"/profile-data",
			data:data,
			success:function(d, e, f){
				originalData = {
					fullname : $("#form_user_update").find("input[name='fullname']").val().trim(),
					nick : $("#form_user_update").find("input[name='nick']").val().trim(),
					email : $("#form_user_update").find("input[name='email']").val().trim(),
					profile_img : $("#profile_img_edit_user").attr("src"),
					media : data.media
				}
				$("#form_user_update").find("input[name='password']").val("");

				$("[data-navbar-user-profile-img]").attr("src", originalData.profile_img);
				$("[data-navbar-user-fullname").html(originalData.fullname);
			},error:function(x, y, z){
			},after:function(){
				App.UnlockScreen();
				App.DOM_Enabling($("#form_user_update"));
				sendingForm=false;
				App.HideLoading();
			}
		});
	});

	$("#add_communication_route_edit_user").click(function(e){
		e.preventDefault();
		add_row_communication_route("edit");
	});

	var generateMediaSelect = function(){
		var str = "";

		$.each(master_media, function(k, media){
			str += '<option value = "'+media.id+'" data-code = "'+media.code+'">'+media.name+'</option>';
		});

		return str;
	}

	var master_media;

	this.start = function(){
		function getMedia(){
			App.GetMasterData("media", function(d, e, f){
				master_media = d.data.items;
				getInfoProfile();
			});
		}

		function getInfoProfile(){
			App.LockScreen();
			App.ShowLoading(App.terms.str_loading_your_profile_info);

			App.HTTP.read({
				url:App.WEB_ROOT+"/profile-data",
				success:function(d, e, f){
					$("#form_user_update").find("input[name='fullname']").val(d.data.item.fullname);
					$("#form_user_update").find("input[name='nick']").val(d.data.item.nick);
					$("#form_user_update").find("input[name='email']").val(d.data.item.email);
					$("#form_user_update").find("input[name='password']").val("");
					originalData = d.data.item;

					if(d.data.item.profile_img.indexOf("default") != -1){
						$("#profile_img_edit_user").attr("src", "assets/images/profile/default.jpg");
						$("#remove_profile_img_edit_user").hide(App.TIME_FOR_HIDE);
					}else{
						$("#profile_img_edit_user").attr("src", App.IMG_PROFILE_FOLDER_ROUTE+d.data.item.profile_img);
						$("#remove_profile_img_edit_user").show(App.TIME_FOR_SHOW);
					}

					$("#list_communication_routes_edit_user").empty();

					$.each(d.data.item.media, function(k, v){
						add_row_communication_route("edit", v);
					});

					$("#my_profile_content").show(App.TIME_FOR_SHOW);
					$("#form_user_update").find("input[name='password']").val("");

					App.TimeInterval(function(){
						sendForm = false;
						sendForm = sendForm || originalData.fullname != $("#form_user_update").find("input[name='fullname']").val().trim()
											|| originalData.nick != $("#form_user_update").find("input[name='nick']").val().trim()
											|| originalData.email != $("#form_user_update").find("input[name='email']").val().trim()
											|| "" != $("#form_user_update").find("input[name='password']").val()
											|| $("#profile_img_edit_user").attr("src").indexOf(originalData.profile_img) == -1;

						sendForm = sendForm && $("#form_user_update").find("input[name='fullname']").val().trim().length > 0;
						var arr = $("#list_communication_routes_edit_user").children();
						var i = 0, n = arr.length, m = originalData.media.length;

						if(n == m){
							while(i < n && !sendForm){
								sendForm = 		sendForm 
											|| 	$(arr[i]).find("select").val() != originalData.media[i].code
											||	$(arr[i]).find("input").val().trim() != originalData.media[i].value;
								i++;
							}
						}else{
							sendForm = true;
						}


						if(!sendingForm){
							if(sendForm){
								$("#send_form").show(App.TIME_FOR_SHOW).attr("disabled", false);
							}else{
								$("#send_form").hide(App.TIME_FOR_HIDE).attr("disabled", true);
							}
						}
					}, 1000);
				},after:function(x, y, z){
					App.UnlockScreen();
					App.HideLoading();
				},log_ui_msg:false
			});
		}

		App.ShowLoading();
		getMedia();
	}
}