<?php
	//codes
		//1XX
			//nothing
		//2XX
			//general
				define("HTTP_CODE_ITEM_CREATED", 201);
			//registering
				define("HTTP_CODE_USER_REGISTERED", 201);/**/
			//login
				define("HTTP_CODE_SUCCESSFUL_LOGIN", 200);/**/
			//recovering account
				define("HTTP_CODE_ACCOUNT_RECOVERING_DONE", 200);/**/
		//3XX
			//nothing
		//4XX
			//general
				define("HTTP_CODE_INVALID_DATA", 400);
			//login
				define("HTTP_CODE_EMAIL_OR_NICK_DOESNT_EXIST", 404);/**/
				define("HTTP_CODE_INVALID_PASSWORD", 400);/**/
				define("HTTP_CODE_PENDING_SIGNUP_CONFIRMATION", 401);
			//registering
				define("HTTP_CODE_EMAIL_IS_BEING_USED", 409);/**/
				define("HTTP_CODE_NICK_IS_BEING_USED", 409);/**/
			//recovering account
				define("HTTP_CODE_USER_DOESNT_EXIST", 404);/**/
				define("HTTP_CODE_USER_NOT_AVAILABLE", 400);/**/
		//5XX
		//nothing
?>