<?php
	$tables = array(
		array(
			"name" => "client", 
				"cascade"=>array("address", "documentation", "media", "status")
		),

			array(
				"name" => "client_address", 
					"cascade"=>array()
			),

			array(
				"name" => "client_media", 
					"cascade"=>array()
			),

			array(
				"name" => "client_documentation", 
					"cascade"=>array()
			),

			array(
				"name" => "client_status", 
					"cascade"=>array()
			),

		array(
			"name" => "compressed_file_info", 
				"cascade"=>array()
		),

		array(
			"name" => "directory",
				"cascade" => array("role_permises", "user_permises", "download")
		),

			array(
				"name" => "directory_role_permises",
					"cascade" => array()
			),

			array(
				"name" => "directory_user_permises",
					"cascade" => array()
			),

			array(
				"name" => "directory_download",
					"cascade" => array()
			),

		array(
			"name" => "event", 
				"cascade"=>array("status")
		),

			array(
				"name" => "event_status", 
					"cascade"=>array()
			),

		array(
			"name" => "file",
				"cascade" => array("role_permises", "user_permises", "download")
		),

			array(
				"name" => "file_role_permises",
					"cascade" => array()
			),

			array(
				"name" => "file_user_permises",
					"cascade" => array()
			),

			array(
				"name" => "file_download",
					"cascade" => array()
			),

		array(
			"name" => "master_administrative_division", 
				"cascade"=>array("parent", "status")
		),

			array(
				"name" => "master_administrative_division_parent", 
					"cascade"=>array()
			),

			array(
				"name" => "master_administrative_division_status", 
					"cascade"=>array()
			),

		array(
			"name" => "master_administrative_division_instance", 
				"cascade"=>array("parent", "status", "type")
		),

			array(
				"name" => "master_administrative_division_instance_parent", 
					"cascade"=>array()
			),

			array(
				"name" => "master_administrative_division_instance_status", 
					"cascade"=>array()
			),

			array(
				"name" => "master_administrative_division_instance_type", 
					"cascade"=>array()
			),

		array(
			"name" => "master_bank", 
				"cascade"=>array("status")
		),

			array(
				"name" => "master_bank_status", 
					"cascade"=>array()
			),

		array(
			"name" => "master_credit_card", 
				"cascade"=>array("status")
		),

			array(
				"name" => "master_credit_card_status", 
					"cascade"=>array()
			),

		array(
			"name" => "master_currency", 
				"cascade"=>array("exchange", "status")
		),

			array(
				"name" => "master_currency_exchange", 
					"cascade"=>array()
			),

			array(
				"name" => "master_currency_status", 
					"cascade"=>array()
			),

		array(
			"name" => "master_documentation", 
				"cascade"=>array("status")
		),

			array(
				"name" => "master_documentation_status", 
					"cascade"=>array()
			),

		array(
			"name" => "master_epayment", 
				"cascade"=>array("status")
		),

			array(
				"name" => "master_epayment_status", 
					"cascade"=>array()
			),

		array(
			"name" => "master_language", 
				"cascade"=>array("status")
		),

			array(
				"name" => "master_language_status", 
					"cascade"=>array()
			),

		array(
			"name" => "master_media", 
				"cascade"=>array("status")
		),

			array(
				"name" => "master_media_status", 
					"cascade"=>array()
			),

		array(
			"name" => "master_status", 
				"cascade"=>array("status")
		),

			array(
				"name" => "master_status_status", 
					"cascade"=>array()
			),

		array(
			"name" => "panel_admin_action", 
				"cascade"=>array("status")
		),

			array(
				"name" => "panel_admin_action_status", 
					"cascade"=>array()
			),

		array(
			"name" => "panel_admin_operation", 
				"cascade"=>array("status")
		),

			array(
				"name" => "panel_admin_operation_status", 
					"cascade"=>array()
			),

		array(
			"name" => "panel_admin_role", 
				"cascade"=>array("status", "section")
		),

			array(
				"name" => "panel_admin_role_section", 
					"cascade"=>array("action")
			),

				array(
					"name" => "panel_admin_role_section_action", 
						"cascade"=>array()
				),

			array(
				"name" => "panel_admin_role_status", 
					"cascade"=>array()
			),

		array(
			"name" => "panel_admin_section", 
				"cascade"=>array("term")
		),

			array(
				"name" => "panel_admin_section_term", 
					"cascade"=>array("status")
			),

				array(
					"name" => "panel_admin_section_term_status", 
						"cascade"=>array()
				),

		array(
			"name" => "panel_admin_sound", 
				"cascade"=>array("status")
		),

			array(
				"name" => "panel_admin_sound_status", 
					"cascade"=>array()
			),

		array(
			"name" => "product_service", 
				"cascade"=>array("status", "field")
		),

			array(
				"name" => "product_service_field", 
					"cascade"=>array()
			),

			array(
				"name" => "product_service_status", 
					"cascade"=>array()
			),

		array(
			"name" => "purchase", 
				"cascade"=>array("status")
		),

			array(
				"name" => "purchase_status", 
					"cascade"=>array()
			),

		array(
			"name" => "sale", 
				"cascade"=>array("status")
		),

			array(
				"name" => "sale_status", 
					"cascade"=>array()
			),

		array(
			"name" => "thread", 
				"cascade"=>array("admin", "join_request", "message", "speaker")
		),

			array(
				"name" => "thread_admin", 
					"cascade"=>array()
			),

			array(
				"name" => "thread_join_request", 
					"cascade"=>array()
			),

			array(
				"name" => "thread_message", 
					"cascade"=>array()
			),

			array(
				"name" => "thread_speaker", 
					"cascade"=>array()
			),

		array(
			"name" => "user", 
				"cascade"=>array("media", "preferences", "role", "session", "status", "signup_confirmation", "account_recovering", "section_amount_times_visited")
		),

			array(
				"name" => "user_media", 
					"cascade"=>array()
			),

			array(
				"name" => "user_preferences", 
					"cascade"=>array()
			),

			array(
				"name" => "user_role", 
					"cascade"=>array()
			),

			array(
				"name" => "user_session", 
					"cascade"=>array("activity")
			),

				array(
					"name" => "user_session_activity", 
						"cascade"=>array()
				),

			array(
				"name" => "user_status", 
					"cascade"=>array()
			),

			array(
				"name" => "user_signup_confirmation", 

					"cascade"=>array()
			),

			array(
				"name" => "user_account_recovering", 

					"cascade"=>array()
			),

			array(
				"name" => "user_section_amount_times_visited", 

					"cascade"=>array()
			),
	);
?>