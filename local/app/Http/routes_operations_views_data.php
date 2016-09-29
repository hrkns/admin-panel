<?php
	namespace App\Models;
	$operations_views_data = array(
		array(
			"model" => "thread", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_THREAD",
			"data" => array(
				"users" => GetForUse("User"),
			)
		),
		array(
			"model" => "administrative-division", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_ADMINISTRATIVE_DIVISION",
			"data" => array(
				"parents" => GetForUse("MasterAdministrativeDivision")
			)
		),
		array(
			"model" => "language", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_LANGUAGE",
		),
		array(
			"model" => "role-permises", 
			"action" => "edit", 
			"op_code" => "GET_VIEW_EDIT_ROLE_PERMISES",
			"data" => array(
				"actions" => GetForUse("PanelAdminAction"),
				"sections" => GetForUse("PanelAdminSection")
			)
		),
		array(
			"model" => "bank", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_BANK",
		),
		array(
			"model" => "role", "action" => "create", "op_code" => "GET_VIEW_CREATE_ROLE",
			"data" => array(
				"actions" => GetForUse("PanelAdminAction"),
				"sections" => GetForUse("PanelAdminSection")
			)
		),
		array(
			"model" => "product", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_PRODUCT",
		),
		array(
			"model" => "customer", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_CLIENT",
		),
		array(
			"model" => "organization", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_ORGANIZATION",
		),
		array(
			"model" => "user", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_USER",
			"data" => array(
				"roles" => GetForUse("PanelAdminRole")
			)
		),
		array(
			"model" => "documentation", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_DOCUMENTATION",
		),
		array(
			"model" => "media", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_MEDIA",
		),
		array(
			"model" => "credit-card", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_CREDIT_CARD",
		),
		array(
			"model" => "e-payment", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_EPAYMENT",
		),
		array(
			"model" => "currency", 
			"action" => "exchange", 
			"op_code" => "GET_VIEW_CREATE_CURRENCY_EXCHANGE",
			"data" => array(
				"currencies" => GetForUse("MasterCurrency")
			)
		),
		array(
			"model" => "currency", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_CURRENCY",
		),
		array(
			"model" => "country", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_COUNTRY",
		),
		array(
			"model" => "action", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_ACTION",
		),
		array(
			"model" => "term", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_TERM",
		),
		array(
			"model" => "status", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_STATUS",
		),
		array(
			"model" => "administrative-division-instance", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_ADMINISTRATIVE_DIVISION_INSTANCE",
			"data" => array(
				"parents" => GetForUse("MasterAdministrativeDivisionInstance"),
				"types" => GetForUse("MasterAdministrativeDivision")
			)
		),
		array(
			"model" => "cloud", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_FILE_OR_DIRECTORY",
		),
		array(
			"model" => "event", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_EVENT",
		),
		array(
			"model" => "operation", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_OPERATION",
		),
		array(
			"model" => "sound", 
			"action" => "create", 
			"op_code" => "GET_VIEW_CREATE_SOUND",
		),


		array(
			"model" => "user",
			"action" => "edit",
			"op_code" => "GET_VIEW_UPDATE_USER",
			"data" => array(
				"roles" => GetForUse("PanelAdminRole")
			)
		),

		array(
			"model" => "user-sessions",
			"action" => "read",
			"op_code" => "GET_VIEW_READ_USER_SESSIONS",
			"data" => array(
			)
		),

		array(
			"model" => "user-session-operations",
			"action" => "read",
			"op_code" => "GET_VIEW_READ_USER_OPERATIONS",
			"data" => array(
			)
		),

		array(
			"model" => "user-account-recovering",
			"action" => "create",
			"op_code" => "GET_VIEW_CREATE_USER_ACCOUNT_RECOVERING",
			"data" => array(
			)
		),

		array(
			"model" => "thread-admin",
			"action" => "create",
			"op_code" => "GET_VIEW_CREATE_THREAD_ADMIN",
			"data" => array(
			)
		),

		array(
			"model" => "thread-admin",
			"action" => "delete",
			"op_code" => "GET_VIEW_DELETE_THREAD_ADMIN",
			"data" => array(
			)
		),

		array(
			"model" => "thread-admin-permises",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_THREAD_ADMIN",
			"data" => array(
			)
		),

		array(
			"model" => "thread-privacy",
			"action" => "edit",
			"op_code" => "GET_VIEW_UPDATE_THREAD_PRIVACY",
			"data" => array(
			)
		),

		array(
			"model" => "terms",
			"action" => "read",
			"op_code" => "GET_VIEW_READ_TERMS",
			"data" => array(
			)
		),

		array(
			"model" => "access",
			"action" => "create",
			"op_code" => "GET_VIEW_CREATE_ACCESS",
			"data" => array(
			)
		),

		array(
			"model" => "access",
			"action" => "edit",
			"op_code" => "GET_VIEW_UPDATE_ACCESS",
			"data" => array(
			)
		),

		array(
			"model" => "access",
			"action" => "delete",
			"op_code" => "GET_VIEW_DELETE_ACCESS",
			"data" => array(
			)
		),

		array(
			"model" => "product-structure",
			"action" => "edit",
			"op_code" => "GET_VIEW_UPDATE_PRODUCT_STRUCTURE",
			"data" => array(
			)
		),

		array(
			"model" => "organization",
			"action" => "edit",
			"op_code" => "GET_VIEW_UPDATE_ORGANIZATION",
			"data" => array(
			)
		),

		array(
			"model" => "customer",
			"action" => "edit",
			"op_code" => "GET_VIEW_UPDATE_CUSTOMER",
			"data" => array(
			)
		),

		array(
			"model" => "directory",
			"action" => "delete",
			"op_code" => "GET_VIEW_DELETE_DIRECTORY",
			"data" => array(
			)
		),

		array(
			"model" => "file",
			"action" => "delete",
			"op_code" => "GET_VIEW_DELETE_FILE",
			"data" => array(
			)
		),

		array(
			"model" => "directory",
			"action" => "edit",
			"op_code" => "GET_VIEW_UPDATE_DIRECTORY",
			"data" => array(
			)
		),

		array(
			"model" => "file",
			"action" => "edit",
			"op_code" => "GET_VIEW_UPDATE_FILE",
			"data" => array(
			)
		),

		array(
			"model" => "directories-and-files",
			"action" => "delete",
			"op_code" => "GET_VIEW_DELETE_DIRECTORIES_AND_FILES",
			"data" => array(
			)
		),

		array(
			"model" => "directories-and-files",
			"action" => "compression",
			"op_code" => "GET_VIEW_COMPRESS_DIRECTORIES_AND_FILES",
			"data" => array(
			)
		),

		array(
			"model" => "directories-and-files",
			"action" => "moving",
			"op_code" => "GET_VIEW_MOVE_DIRECTORIES_AND_FILES",
			"data" => array(
			)
		),

		array(
			"model" => "directories-and-files",
			"action" => "copying",
			"op_code" => "GET_VIEW_COPY_DIRECTORIES_AND_FILES",
			"data" => array(
			)
		),

		array(
			"model" => "dictionary",
			"action" => "import",
			"op_code" => "GET_VIEW_DICTIONARY_IMPORT",
			"data" => array(
			)
		),





		array(
			"model" => "event",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_EVENT",
			"data" => array(
			)
		),
		array(
			"model" => "status",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_STATUS",
			"data" => array(
			)
		),
		array(
			"model" => "action",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_ACTION",
			"data" => array(
			)
		),
		array(
			"model" => "role",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_ROLE",
			"data" => array(
			)
		),
		array(
			"model" => "language",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_LANGUAGE",
			"data" => array(
			)
		),

		array(
			"model" => "term",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_TERM",
			"data" => array(
			)
		),
		array(
			"model" => "administrative-division",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_ADMINISTRATIVE_DIVISION",
			"data" => array(
				"parents" => GetForUse("MasterAdministrativeDivision")
			)
		),
		array(
			"model" => "administrative-division-instance",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_ADMINISTRATIVE_DIVISION_INSTANCE",
			"data" => array(
				"types" => GetForUse("MasterAdministrativeDivision"),
				"parents" => GetForUse("MasterAdministrativeDivisionInstance")
			)
		),
		array(
			"model" => "bank",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_BANK",
			"data" => array(
			)
		),
		array(
			"model" => "e-payment",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_EPAYMENT",
			"data" => array(
			)
		),
		array(
			"model" => "credit-card",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_CREDIT_CARD",
			"data" => array(
			)
		),
		array(
			"model" => "currency",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_CURRENCY",
			"data" => array(
			)
		),
		array(
			"model" => "media",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_MEDIA",
			"data" => array(
			)
		),
		array(
			"model" => "documentation",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_DOCUMENTATION",
			"data" => array(
			)
		),
		array(
			"model" => "sound",
			"action" => "edit",
			"op_code" => "GET_VIEW_EDIT_SOUND",
			"data" => array(
			)
		),
		array(
			"model" => "operation",
			"action" => "edit",
			"op_code" => "GET_VIEW_OPERATION_EDIT",
			"data" => array(
			)
		),
		array(
			"model" => "product",
			"action" => "edit",
			"op_code" => "GET_VIEW_PRODUCT_EDIT",
			"data" => array(
			)
		),
	);
?>