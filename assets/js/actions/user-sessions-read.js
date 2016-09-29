function __action(){
	this.addItems = function(items){
		$.each(items, function(key, session){
			var row = document.createElement("tr");

			var column_id_session = document.createElement("td");
			column_id_session.align = "center";
			column_id_session.innerHTML = "<strong>"+session.id+"</strong>";

			var column_info_session = document.createElement("td");
			column_info_session.align = "left";
			column_info_session.innerHTML = 	'<p><strong>IP: </strong>'+session.info.ip+'</p>'+
												'<p><strong>User-Agent: </strong>'+session.info.user_agent+'</p>';

			var column_start_of_session = document.createElement("td");
			column_start_of_session.align = "center";
			column_start_of_session.innerHTML = session.start;

			var column_end_of_session = document.createElement("td");
			column_end_of_session.align = "center";
			column_end_of_session.innerHTML = session.end;

			var column_option_of_session_preview = document.createElement("td");
			column_option_of_session_preview.align = "center";

			var button_see_all_activities_of_the_session = document.createElement("button");
			button_see_all_activities_of_the_session.className = "btn";
			button_see_all_activities_of_the_session.innerHTML =  App.terms.str_see_all_operations;

			var getting_all_activities = false;

			button_see_all_activities_of_the_session.onclick = function(){
				if(getting_all_activities){
					return;
				}

				getting_all_activities = true;
				App.LockScreen();
				App.ShowLoading(App.terms.str_requesting_activities_list_on_session);

				App.HTTP.read({
					url : App.WEB_ROOT + "/user/" + Section.ID_USER_CHECKING + "/session/" + session.id + "/operations",
					success : function(d, e, f){
						setTimeout(function(){
							$("#session_operations_history_user_fullname").html($("#preview_user_"+Section.ID_USER_CHECKING+"_fullname").text());
							$("#session_operations_history_start").html(session.start);
							$("#session_operations_history_end").html(session.end);
						}, 1500);

						$("#list_operations").empty();

						App.getView("user-session-operations", "read", function(){
							Actions["user-session-operations-read"].addItems(d.data.items)
						})
					},error : function(x, y, z){
					},after : function(){
						App.UnlockScreen();
						getting_all_activities = false;
						App.HideLoading();
					}
				});
			}

			column_option_of_session_preview.appendChild(button_see_all_activities_of_the_session);

			row.appendChild(column_id_session);
			row.appendChild(column_info_session);
			row.appendChild(column_start_of_session);
			row.appendChild(column_end_of_session);
			row.appendChild(column_option_of_session_preview);

			$("#list_sessions").append(row);
		});
	}

	$("#form_user-sessions_read").submit(function(e){
		e.preventDefault();
	});
}