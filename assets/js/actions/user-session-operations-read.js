function __action(){
	this.addItems = function(items){
		$.each(items, function(id, activity){
			var row_operation = document.createElement("tr");

			var column_id_operation = document.createElement("td");
			column_id_operation.align = "center";
			column_id_operation.innerHTML = "<strong>"+activity.id+"</strong>";

			var column_date = document.createElement("td");
			column_date.align = "center";
			column_date.innerHTML = activity.date;

			var column_operation = document.createElement("td");
			column_operation.align = "left";
			column_operation.innerHTML = (activity.operation) ? (activity.operation.name + " ") : '';

			var help_icon = document.createElement("a");
			help_icon.innerHTML = "<i class = 'icon ion-help-circled'></i>";
			help_icon.href = "javascript:;";
			help_icon.title = (activity.operation) ? (activity.operation.description + " ") : '';

			column_operation.appendChild(help_icon);

			var column_info = document.createElement("td");
			column_info.align = "center";
			column_info.innerHTML = build_info_interface(activity);

			row_operation.appendChild(column_id_operation);
			row_operation.appendChild(column_date);
			row_operation.appendChild(column_operation);
			row_operation.appendChild(column_info);

			$("#list_operations").append(row_operation);

			$(help_icon).tooltip();
		});
	}

	$("form_user-session-operations_read").submit(function(e){
		e.preventDefault();
	});

	//DEPLOY THIS, a switch over the "activity.operation.code" field?
	function build_info_interface(activity){
		return "";
	}
}