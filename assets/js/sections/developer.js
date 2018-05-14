function module(){
	/**/
		$("#use_of_status_toggle").click(function(){
			//$("#use_of_status_div").toggle(App.TIME_FOR_SHOW);
		});

	/******************************************************************************************/

	/**/

		var changing_use_of_status = {};

		function handle_change_checked(thing){
			var value = $(thing).attr("data-section-use-statuses");
			var checked = $(thing).prop("checked");

			if(changing_use_of_status[value]){
				return;
			}

			changing_use_of_status[value] = true;
			$(thing).attr("disabled", true);
			$("#toggle_all_use_statuses").attr("disabled", true);

			App.HTTP.update({
				url : App.WEB_ROOT + "/section/"+value+"/use-of-status",
				data : {
					use : checked
				},
				success : function(){
					if(checked){
						//$(thing).parents("tr").find(".select2-container").hide();
					}else{
						//$(thing).parents("tr").find(".select2-container").show();
					}
				},error : function(){
					$(thing).prop("checked", !checked);
				},after : function(){
					changing_use_of_status[value] = false;
					$(thing).attr("disabled", false)
					$("#toggle_all_use_statuses").attr("disabled", false);
				}
			});
		}

		$("[data-section-use-statuses]").change(function(){
			handle_change_checked(this);
		});

		/*
		$("#toggle_all_use_statuses").change(function(){
			$("[data-section-use-statuses]").prop("checked", this.checked);

			$("[data-section-use-statuses]").each(function(){
				changing_use_of_status[$(this).attr("data-section-use-statuses")] = true;
				$(this).prop("disabled", true);
			});
		});
		*/

	/******************************************************************************************/

	/**/
		$("[data-select-values-default]").select2();
		$(".select2-container").css("width", "100%");

		var previous_default_values = {};
		var updating_default_values = {};

		$("[data-select-values-default]").each(function(){
			var ref = $(this).attr("data-select-values-default");
			previous_default_values[ref] = $(this).val() != null?$(this).val():[];
			updating_default_values[ref] = false;

			$("[data-save-default-values='"+ref+"']").click(function(){
				if(updating_default_values[ref]){
					return;
				}

				updating_default_values[ref] = true;
				var to_send = $("[data-select-values-default='"+ref+"']").val();

				if(to_send == null){
					to_send = [];
				}

				$("[data-select-values-default='"+ref+"']").attr("disabled", true);
				$("[data-save-default-values='"+ref+"']").attr("disabled", true);

				App.HTTP.update({
					url : App.WEB_ROOT + "/section/"+ref+"/default-statuses-values",
					data : {
						values : to_send
					},
					before : function(){
					},
					received : function(){
					},
					success : function(d, e, f){
						previous_default_values[ref] = to_send;
						$("[data-save-default-values='"+ref+"']").hide(App.TIME_FOR_HIDE);
					},
					error : function(x, y, z){
					},
					after : function(){
						$("[data-select-values-default='"+ref+"']").attr("disabled", false);
						$("[data-save-default-values='"+ref+"']").attr("disabled", false);
						updating_default_values[ref] = false;
					}
				});
			});
		});

		App.TimeInterval(function(){
			$("[data-select-values-default]").each(function(){
				var ref = $(this).attr("data-select-values-default");

				if(!updating_default_values[ref]){
					if(App.flexible_equal_array($(this).val(), previous_default_values[ref])){
						$("[data-save-default-values='"+ref+"']").hide(App.TIME_FOR_HIDE);
					}else{
						$("[data-save-default-values='"+ref+"']").show(App.TIME_FOR_SHOW);
					}
				}
			});
		}, 1000);

	/******************************************************************************************/

	/**/
		$("[data-select-values-permitted]").select2();
		$(".select2-container").css("width", "100%");

		var previous_permitted_values = {};
		var updating_permitted_values = {};

		$("[data-select-values-permitted]").each(function(){
			var ref = $(this).attr("data-select-values-permitted");
			previous_permitted_values[ref] = $(this).val() != null?$(this).val():[];
			updating_permitted_values[ref] = false;

			$("[data-save-permitted-values='"+ref+"']").click(function(){
				if(updating_permitted_values[ref]){
					return;
				}

				updating_permitted_values[ref] = true;
				var to_send = $("[data-select-values-permitted='"+ref+"']").val();

				if(to_send == null){
					to_send = [];
				}

				$("[data-select-values-permitted='"+ref+"']").attr("disabled", true);
				$("[data-save-permitted-values='"+ref+"']").attr("disabled", true);

				App.HTTP.update({
					url : App.WEB_ROOT + "/section/"+ref+"/permitted-statuses-values",
					data : {
						values : to_send
					},
					before : function(){
					},
					received : function(){
					},
					success : function(d, e, f){
						previous_permitted_values[ref] = to_send;
						$("[data-save-permitted-values='"+ref+"']").hide(App.TIME_FOR_HIDE);
					},
					error : function(x, y, z){
					},
					after : function(){
						$("[data-select-values-permitted='"+ref+"']").attr("disabled", false);
						$("[data-save-permitted-values='"+ref+"']").attr("disabled", false);
						updating_permitted_values[ref] = false;
					}
				});
			});
		});

		App.TimeInterval(function(){
			$("[data-select-values-permitted]").each(function(){
				var ref = $(this).attr("data-select-values-permitted");

				if(!updating_permitted_values[ref]){
					if(App.flexible_equal_array($(this).val(), previous_permitted_values[ref])){
						$("[data-save-permitted-values='"+ref+"']").hide(App.TIME_FOR_HIDE);
					}else{
						$("[data-save-permitted-values='"+ref+"']").show(App.TIME_FOR_SHOW);
					}
				}
			});
		}, 1000);

	/******************************************************************************************/

	/**/
		var changing_multiple_statuses = {};

		$("[data-section-multiple-statuses]").change(function(){
			var thing = this;
			var value = $(thing).attr("data-section-multiple-statuses");
			var checked = $(thing).prop("checked");

			if(changing_multiple_statuses[value]){
				return;
			}

			changing_multiple_statuses[value] = true;
			$(thing).attr("disabled", true);

			App.HTTP.update({
				url : App.WEB_ROOT + "/section/"+value+"/multiple-statuses",
				data : {
					multiple : checked
				},
				success : function(){
				},error : function(){
					$(thing).prop("checked", !checked);
				},after : function(){
					changing_multiple_statuses[value] = false;
					$(thing).attr("disabled", false)
				}
			});
		});
}