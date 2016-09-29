<?php
$requests = array(
    /*requests for 'languages' section*/
        [
            "type" => "put", 
            "route" => "language-session", 
            "controller" => "Language", 
            "method" => "updateLanguageSession", 
            "middlewares" => array(
                "session_verification" => false
            ),
        ],[
            "type" => "post", 
            "route" => "language", 
            "controller" => "Language", 
            "method" => "create",
        ],[
            "type" => "get", 
            "route" => "languages", 
            "controller" => "Language", 
            "method" => "index",
        ],[
            "type" => "get", 
            "route" => "language/{id}", 
            "controller" => "Language", 
            "method" => "read",
        ],[
            "type" => "get",
    		"route" => "languages-search",
    		"controller" => "Language",
    		"method" => "search",
    	],[
            "type" => "put",
    		"route" => "language/{id}",
    		"controller" => "Language",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "language/{id}",
    		"controller" => "Language",
    		"method" => "delete",
    	],

    /*requests for 'statuses' section*/
        [
            "type" => "post",
    		"route" => "status",
    		"controller" => "status",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "statuses",
    		"controller" => "Status",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "statuses-search",
    		"controller" => "Status",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "status/{id}",
    		"controller" => "Status",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "status/{id}",
    		"controller" => "Status",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "status/{id}",
    		"controller" => "Status",
    		"method" => "delete",
    	],

    /*requests for 'roles' section*/
        [
            "type" => "post",
    		"route" => "role",
    		"controller" => "Role",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "roles",
    		"controller" => "Role",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "roles-search",
    		"controller" => "Role",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "role/{id}",
    		"controller" => "Role",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "role/{id}",
    		"controller" => "Role",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "role/{id}",
    		"controller" => "Role",
    		"method" => "delete",
    	],[
            "type" => "get",
    		"route" => "role/{id}/permises",
    		"controller" => "Role",
    		"method" => "permises",
    	],[
            "type" => "put",
    		"route" => "role/{id}/permises",
    		"controller" => "Role",
    		"method" => "updatePermises",
    	],

    /*requests for 'actions' section*/
        [
            "type" => "post",
    		"route" => "action",
    		"controller" => "Action",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "actions",
    		"controller" => "Action",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "actions-search",
    		"controller" => "Action",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "action/{id}",
    		"controller" => "Action",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "action/{id}",
    		"controller" => "Action",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "action/{id}",
    		"controller" => "Action",
    		"method" => "delete",
    	],

    /*requests for 'sections and terms' section*/
        [
            "type" => "get",
    		"route" => "section/{id}/terms",
    		"controller" => "Section",
    		"method" => "getTerms",
    	],[
            "type" => "post",
    		"route" => "term",
    		"controller" => "Section",
    		"method" => "createTerm",
    	],[
            "type" => "put",
    		"route" => "term/{id}",
    		"controller" => "Section",
    		"method" => "setTerm",
    	],[
            "type" => "get",
    		"route" => "term/{id}",
    		"controller" => "Section",
    		"method" => "getTerm",
    	],[
            "type" => "post",
    		"route" => "section/{id}/terms-cloning",
    		"controller" => "Section",
    		"method" => "termsCloning",
    	],[
            "type" => "delete",
    		"route" => "term/{id}",
    		"controller" => "Section",
    		"method" => "deleteTerm",
    	],[
            "type" => "get",
    		"route" => "dictionary",
    		"controller" => "Section",
    		"method" => "downloadDictionary",
    	],[
            "type" => "get",
    		"route" => "dictionary/{hash}",
    		"controller" => "Section",
    		"method" => "execDownloadDic",
    	],[
            "type" => "post",
    		"route" => "dictionary-importing",
    		"controller" => "Section",
    		"method" => "dicImporting",
    	],[
            "type" => "get",
    		"route" => "section/{id}",
    		"controller" => "Section",
    		"method" => "show",
    	],[
            "type" => "put",
    		"route" => "menu",
    		"controller" => "Section",
    		"method" => "update_menu",
    	],

    /*requests for 'bank' section*/
        [
            "type" => "post",
    		"route" => "bank",
    		"controller" => "Bank",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "banks",
    		"controller" => "Bank",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "banks-search",
    		"controller" => "Bank",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "bank/{id}",
    		"controller" => "Bank",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "bank/{id}",
    		"controller" => "Bank",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "bank/{id}",
    		"controller" => "Bank",
    		"method" => "delete",
    	],

    /*requests for 'e-payment' section*/
        [
            "type" => "post",
    		"route" => "e-payment",
    		"controller" => "Epayment",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "e-payment-methods",
    		"controller" => "Epayment",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "e-payment-methods-search",
    		"controller" => "Epayment",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "e-payment/{id}",
    		"controller" => "Epayment",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "e-payment/{id}",
    		"controller" => "Epayment",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "e-payment/{id}",
    		"controller" => "Epayment",
    		"method" => "delete",
    	],

    /*requests for 'credit-card' section*/
        [
            "type" => "post",
    		"route" => "credit-card",
    		"controller" => "CreditCard",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "credit-cards",
    		"controller" => "CreditCard",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "credit-cards-search",
    		"controller" => "CreditCard",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "credit-card/{id}",
    		"controller" => "CreditCard",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "credit-card/{id}",
    		"controller" => "CreditCard",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "credit-card/{id}",
    		"controller" => "CreditCard",
    		"method" => "delete",
    	],

    /*requests for 'currency' section*/
        [
            "type" => "post",
    		"route" => "currency",
    		"controller" => "Currency",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "currencies",
    		"controller" => "Currency",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "currencies-search",
    		"controller" => "Currency",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "currency/{id}",
    		"controller" => "Currency",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "currency/{id}",
    		"controller" => "Currency",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "currency/{id}",
    		"controller" => "Currency",
    		"method" => "delete",
    	],[
            "type" => "post",
    		"route" => "exchange",
    		"controller" => "Currency",
    		"method" => "createExchange",
    	],[
            "type" => "put",
    		"route" => "exchange/{id}",
    		"controller" => "Currency",
    		"method" => "updateExchange",
    	],[
            "type" => "delete",
    		"route" => "exchange/{id}",
    		"controller" => "Currency",
    		"method" => "deleteExchange",
    	],[
            "type" => "get",
    		"route" => "exchanges",
    		"controller" => "Currency",
    		"method" => "readExchanges",
    	],

    /*requests for 'media' section*/
        [
            "type" => "post",
    		"route" => "media",
    		"controller" => "Media",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "media",
    		"controller" => "Media",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "media-search",
    		"controller" => "Media",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "media/{id}",
    		"controller" => "Media",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "media/{id}",
    		"controller" => "Media",
    		"method" => "update",
    	],[
            "type" => "put",
    		"route" => "media/{id}",
    		"controller" => "Media",
    		"method" => "delete",
    	],

    /*requests for 'documentation' section*/
        [
            "type" => "post",
    		"route" => "documentation",
    		"controller" => "Documentation",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "documentation",
    		"controller" => "Documentation",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "documentation-search",
    		"controller" => "Documentation",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "documentation/{id}",
    		"controller" => "Documentation",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "documentation/{id}",
    		"controller" => "Documentation",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "documentation/{id}",
    		"controller" => "Documentation",
    		"method" => "delete",
    	],

    /*requests for 'users' section and related activities*/
        [
            "type" => "post",
    		"route" => "user",
    		"controller" => "UserController",
    		"method" => "create", "middlewares" => array(
                "session_verification" => false
            ),
    	],[
            "type" => "get",
    		"route" => "signup-confirmation/{hash}",
    		"controller" => "UserController",
    		"method" => "signupConfirmation", "middlewares" => array(
                "session_verification" => false
            ),
    	],[
            "type" => "post",
    		"route" => "account-recovering",
    		"controller" => "UserController",
    		"method" => "accountRecovering", "middlewares" => array(
                "session_verification" => false
            ),
    	],[
            "type" => "get",
    		"route" => "account-recovering/{hash}",
    		"controller" => "UserController",
    		"method" => "getAccountRecovering", "middlewares" => array(
                "session_verification" => false
            ),
    	],[
            "type" => "post",
    		"route" => "signup-confirmation/{iduser}",
    		"controller" => "UserController",
    		"method" => "postSignupConfirmation",
    	],[
            "type" => "post",
    		"route" => "signup-denegation/{iduser}",
    		"controller" => "UserController",
    		"method" => "denySignup",
    	],[
            "type" => "get",
    		"route" => "users",
    		"controller" => "UserController",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "users-search",
    		"controller" => "UserController",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "user/{id}/info",
    		"controller" => "UserController",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "user/{id}",
    		"controller" => "UserController",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "user/{id}",
    		"controller" => "UserController",
    		"method" => "delete",
    	],[
            "type" => "get",
    		"route" => "user/{id}/sessions",
    		"controller" => "UserController",
    		"method" => "sessionsHistory",
    	],[
            "type" => "get",
    		"route" => "user/{id}/session/{idsession}/operations",
    		"controller" => "UserController",
    		"method" => "sessionOperations",
    	],[
            "type" => "post",
    		"route" => "account-recovering-denegation/{iduser}",
    		"controller" => "UserController",
    		"method" => "denyAccountRecovering",
    	],[
            "type" => "post",
    		"route" => "account-recovering/{iduser}",
    		"controller" => "UserController",
    		"method" => "proccessAccountRecovering",
    	],[
            "type" => "put",
    		"route" => "profile-data",
    		"controller" => "UserController",
    		"method" => "update",
    	],[
            "type" => "get",
    		"route" => "profile-data",
    		"controller" => "UserController",
    		"method" => "read",
    	],

    /*requests for 'organizations' section*/
        [
            "type" => "post",
    		"route" => "organization",
    		"controller" => "OrganizationController",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "organizations",
    		"controller" => "OrganizationController",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "organizations-search",
    		"controller" => "OrganizationController",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "organization/{id}/info",
    		"controller" => "OrganizationController",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "organization/{id}",
    		"controller" => "OrganizationController",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "organization/{id}",
    		"controller" => "OrganizationController",
    		"method" => "delete",
    	],

    /*requests for 'clients' section*/
        [
            "type" => "post",
    		"route" => "customer",
    		"controller" => "ClientController",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "customers",
    		"controller" => "ClientController",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "customers-search",
    		"controller" => "ClientController",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "customer/{id}/info",
    		"controller" => "ClientController",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "customer/{id}",
    		"controller" => "ClientController",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "customer/{id}",
    		"controller" => "ClientController",
    		"method" => "delete",
    	],

    /*requests for 'products-and-services' section*/
        [
            "type" => "post",
    		"route" => "product",
    		"controller" => "Product",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "products",
    		"controller" => "Product",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "products-search",
    		"controller" => "Product",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "product/{id}",
    		"controller" => "Product",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "product/{id}",
    		"controller" => "Product",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "product/{id}",
    		"controller" => "Product",
    		"method" => "delete",
    	],[
            "type" => "get",
    		"route" => "product/{id}/structure",
    		"controller" => "Product",
    		"method" => "getEstructure",
    	],[
            "type" => "put",
    		"route" => "product/{id}/structure",
    		"controller" => "Product",
    		"method" => "setEstructure",
    	],

    /*requests for 'settings (global and custom)' section*/
        [
            "type" => "put",
    		"route" => "global-preferences/logo",
    		"controller" => "Preferences",
    		"method" => "updateLogo",
    	],[
            "type" => "put",
    		"route" => "user/{id}/system-logo",
    		"controller" => "Preferences",
    		"method" => "personalLogo",
    	],[
            "type" => "put",
    		"route" => "global-preferences/user-register",
    		"controller" => "Preferences",
    		"method" => "setLetRegisterUser",
    	],[
            "type" => "put",
    		"route" => "global-preferences/recover-account-mechanism",
    		"controller" => "Preferences",
    		"method" => "recoverAccountMechanism",
    	],[
            "type" => "put",
    		"route" => "user/{id}/amount-items-progressive-requests",
    		"controller" => "Preferences",
    		"method" => "amountItemsProgressiveRequests",
    	],[
            "type" => "put",
    		"route" => "tab-title-preferences",
    		"controller" => "Preferences",
    		"method" => "tabTitle",
    	],[
            "type" => "put",
    		"route" => "global-preferences/tab-icon",
    		"controller" => "Preferences",
    		"method" => "updateTabIcon",
    	],[
            "type" => "put",
    		"route" => "user/{id}/system-tab-icon",
    		"controller" => "Preferences",
    		"method" => "personalTabIcon",
    	],[
            "type" => "put",
    		"route" => "user/{id}/use-global-tab-icon",
    		"controller" => "Preferences",
    		"method" => "useGlobalTabIcon",
    	],[
            "type" => "put",
    		"route" => "global-preferences/terms-of-use-and-privacy-policy",
    		"controller" => "Preferences",
    		"method" => "termsAndPrivacy",
    	],[
            "type" => "put",
    		"route" => "chat-sound-alert",
    		"controller" => "Preferences",
    		"method" => "chatSoundAlert",
    	],[
            "type" => "put",
    		"route" => "type-content-signup-email",
    		"controller" => "Preferences",
    		"method" => "typeContentSignupEmail",
    	],[
            "type" => "put",
    		"route" => "account-recovering-mechanism",
    		"controller" => "Preferences",
    		"method" => "accountRecoveringMechanism",
    	],[
            "type" => "put",
    		"route" => "account-recovering-mechanism-automatic",
    		"controller" => "Preferences",
    		"method" => "accountRecoveringMechanismAutomatic",
    	],[
            "type" => "put",
    		"route" => "general-session-duration",
    		"controller" => "Preferences",
    		"method" => "generalSessionDuration",
    	],[
            "type" => "put",
    		"route" => "custom-session-duration",
    		"controller" => "Preferences",
    		"method" => "customSessionDuration",
    	],[
            "type" => "put",
    		"route" => "default-config-inactivity-time-limit",
    		"controller" => "Preferences",
    		"method" => "defaultConfigInactivityTimeLimit",
    	],[
            "type" => "put",
    		"route" => "custom-config-inactivity-time-limit",
    		"controller" => "Preferences",
    		"method" => "customConfigInactivityTimeLimit",
    	],[
            "type" => "put",
            "route" => "default-language-system",
            "controller" => "Preferences",
            "method" => "defaultLanguageSystem"
        ],[
            "type" => "put",
            "route" => "default-format-show-items",
            "controller" => "Preferences",
            "method" => "formatShowItems"
        ],[
            "type" => "put",
            "route" => "format-show-items",
            "controller" => "Preferences",
            "method" => "customFormatShowItems"
        ],[
            "type" => "put",
            "route" => "default-format-edit-items",
            "controller" => "Preferences",
            "method" => "formatEditItems"
        ],[
            "type" => "put",
            "route" => "format-edit-items",
            "controller" => "Preferences",
            "method" => "customFormatEditItems"
        ],

    /*requests for 'administrative divisions' section*/
        [
            "type" => "post",
    		"route" => "administrative-division",
    		"controller" => "AdministrativeDivision",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "administrative-divisions",
    		"controller" => "AdministrativeDivision",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "administrative-divisions-search",
    		"controller" => "AdministrativeDivision",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "administrative-division/{id}",
    		"controller" => "AdministrativeDivision",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "administrative-division/{id}",
    		"controller" => "AdministrativeDivision",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "administrative-division/{id}",
    		"controller" => "AdministrativeDivision",
    		"method" => "delete",
    	],

    /*requests for 'instances of administrative divisions' section*/
        [
            "type" => "post",
    		"route" => "administrative-division-instance",
    		"controller" => "AdministrativeDivisionInstance",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "administrative-division-instances",
    		"controller" => "AdministrativeDivisionInstance",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "administrative-division-instances-search",
    		"controller" => "AdministrativeDivisionInstance",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "administrative-division-instance/{id}",
    		"controller" => "AdministrativeDivisionInstance",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "administrative-division-instance/{id}",
    		"controller" => "AdministrativeDivisionInstance",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "administrative-division-instance/{id}",
    		"controller" => "AdministrativeDivisionInstance",
    		"method" => "delete",
    	],[
            "type" => "get",
    		"route" => "administrative-division-instances-autocomplete",
    		"controller" => "AdministrativeDivisionInstance",
    		"method" => "autocomplete",
    	],

    /*requests for 'threads' section*/
        [
            "type" => "post",
    		"route" => "thread",
    		"controller" => "ThreadController",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "threads",
    		"controller" => "ThreadController",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "threads-search",
    		"controller" => "ThreadController",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "thread/{id}",
    		"controller" => "ThreadController",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "thread/{id}",
    		"controller" => "ThreadController",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "thread/{id}",
    		"controller" => "ThreadController",
    		"method" => "delete",
    	],[
            "type" => "post",
    		"route" => "thread/{id}/join-request",
    		"controller" => "ThreadController",
    		"method" => "joinRequest",
    	],[
            "type" => "delete",
    		"route" => "thread/{id}/join-request",
    		"controller" => "ThreadController",
    		"method" => "deleteJoinned",
    	],[
            "type" => "delete",
    		"route" => "thread/{id}/speaker",
    		"controller" => "ThreadController",
    		"method" => "removeMe",
    	],[
            "type" => "get",
    		"route" => "thread/{id}/messages",
    		"controller" => "ThreadController",
    		"method" => "messages",
    	],[
            "type" => "get",
    		"route" => "thread/{id}/recent-messages",
    		"controller" => "ThreadController",
    		"method" => "recentMessages",
    	],[
            "type" => "post",
    		"route" => "thread/{id}/message",
    		"controller" => "ThreadController",
    		"method" => "message",
    	],[
            "type" => "post",
    		"route" => "thread/{id}/speaker",
    		"controller" => "ThreadController",
    		"method" => "createSpeaker",
    	],[
            "type" => "post",
    		"route" => "thread/{id}/admins",
    		"controller" => "ThreadController",
    		"method" => "createAdmins",
    	],[
            "type" => "delete",
    		"route" => "thread/{id}/join-request/{idjoinrq}",
    		"controller" => "ThreadController",
    		"method" => "removeJoinRequest",
    	],[
            "type" => "delete",
    		"route" => "thread/{id}/admin/{iduser}",
    		"controller" => "ThreadController",
    		"method" => "removeAdmin",
    	],[
            "type" => "put",
    		"route" => "thread/{id}/admin/{iduser}/permises",
    		"controller" => "ThreadController",
    		"method" => "updatePermises",
    	],

    /*requests for 'sounds' section*/
        [
            "type" => "post",
    		"route" => "sound",
    		"controller" => "Sound",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "sounds",
    		"controller" => "Sound",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "sounds-search",
    		"controller" => "Sound",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "sound/{id}",
    		"controller" => "Sound",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "sound/{id}",
    		"controller" => "Sound",
    		"method" => "update",
    	],[
            "type" => "post",
    		"route" => "sound/{id}/file",
    		"controller" => "Sound",
    		"method" => "updateFile",
    	],[
            "type" => "delete",
    		"route" => "sound/{id}",
    		"controller" => "Sound",
    		"method" => "delete",
    	],

    /*requests for 'operations' section*/
        [
            "type" => "post",
    		"route" => "operation",
    		"controller" => "Operation",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "operations",
    		"controller" => "Operation",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "operations-search",
    		"controller" => "Operation",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "operation/{id}",
    		"controller" => "Operation",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "operation/{id}",
    		"controller" => "Operation",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "operation/{id}",
    		"controller" => "Operation",
    		"method" => "delete",
    	],

    /*requests for 'events' section*/
        [
            "type" => "post",
    		"route" => "event",
    		"controller" => "EventController",
    		"method" => "create",
    	],[
            "type" => "get",
    		"route" => "events",
    		"controller" => "EventController",
    		"method" => "index",
    	],[
            "type" => "get",
    		"route" => "events-search",
    		"controller" => "EventController",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "event/{id}",
    		"controller" => "EventController",
    		"method" => "read",
    	],[
            "type" => "put",
    		"route" => "event/{id}",
    		"controller" => "EventController",
    		"method" => "update",
    	],[
            "type" => "delete",
    		"route" => "event/{id}",
    		"controller" => "EventController",
    		"method" => "delete",],

    /*requests for 'files' section*/
        [
            "type" => "post",
    		"route" => "directories",
    		"controller" => "Cloud",
    		"method" => "createDirs",
    	],[
            "type" => "post",
    		"route" => "files",
    		"controller" => "Cloud",
    		"method" => "createFiles",
    	],[
            "type" => "post",
    		"route" => "files-upload-info",
    		"controller" => "Cloud",
    		"method" => "uploadFilesInfo",
    	],[
            "type" => "post",
    		"route" => "files-upload-file/{id}",
    		"controller" => "Cloud",
    		"method" => "uploadFilesFile",
    	],[
            "type" => "get",
    		"route" => "directory/{id}/content",
    		"controller" => "Cloud",
    		"method" => "directoryContent",
    	],[
            "type" => "delete",
    		"route" => "directory/{id}",
    		"controller" => "Cloud",
    		"method" => "removeDir",
    	],[
            "type" => "delete",
    		"route" => "file/{id}",
    		"controller" => "Cloud",
    		"method" => "removeFile",
    	],[
            "type" => "put",
    		"route" => "directory/{id}",
    		"controller" => "Cloud",
    		"method" => "updateDir",
    	],[
            "type" => "put",
    		"route" => "file/{id}",
    		"controller" => "Cloud",
    		"method" => "updateFile",
    	],[
            "type" => "delete",
    		"route" => "files",
    		"controller" => "Cloud",
    		"method" => "removeFiles",
    	],[
            "type" => "delete",
    		"route" => "directories",
    		"controller" => "Cloud",
    		"method" => "removeDirectories",
    	],[
            "type" => "get",
    		"route" => "cloud-search",
    		"controller" => "Cloud",
    		"method" => "search",
    	],[
            "type" => "get",
    		"route" => "directory/{id}/parents-line",
    		"controller" => "Cloud",
    		"method" => "parentsLine",
    	],[
            "type" => "get",
    		"route" => "file/{id}/download",
    		"controller" => "Cloud",
    		"method" => "downloadFile",
    	],[
            "type" => "get",
    		"route" => "directory/{id}/download",
    		"controller" => "Cloud",
    		"method" => "downloadDirectory",
    	],[
            "type" => "post",
    		"route" => "files-and-directories-compression",
    		"controller" => "Cloud",
    		"method" => "compression",
    	],[
            "type" => "post",
    		"route" => "set-selected-items-parent",
    		"controller" => "Cloud",
    		"method" => "setParent",
    	],[
            "type" => "post",
    		"route" => "copy-items-to",
    		"controller" => "Cloud",
    		"method" => "copyItemsTo",
    	],

    /*requests for 'session' activities*/
        [
            "type" => "post",
    		"route" => "session",
    		"controller" => "Login",
    		"method" => "create", "middlewares" => array(
                "session_verification" => false
            )
        ],[
            "type" => "get",
    		"route" => "sign-out",
    		"controller" => "Login",
    		"method" => "logout", "middlewares" => array(
                "session_verification" => false
            )
        ],[
            "type" => "post",
    		"route" => "unlock-screen",
    		"controller" => "Login",
    		"method" => "unlock_screen", "middlewares" => array(
                "session_verification" => false
            )
        ],[
            "type" => "get",
    		"route" => "inactivity",
    		"controller" => "Login",
    		"method" => "inactivity",
        ],[
            "type" => "post",
            "route" => "tkns",
            "controller" => "Login",
            "method" => "remove_tokens",
        ],

    /*requests for 'developer' section*/
        [
            "type" => "put",
            "route" => "section/{idsection}/use-of-status",
            "controller" => "Section",
            "method" => "useOfStatus", 
        ],
        [
            "type" => "put",
            "route" => "section/{idsection}/default-statuses-values",
            "controller" => "Section",
            "method" => "setDefaultStatusesValues", 
        ],
        [
            "type" => "put",
            "route" => "section/{idsection}/permitted-statuses-values",
            "controller" => "Section",
            "method" => "setPermittedStatusesValues", 
        ],
        [
            "type" => "put",
            "route" => "section/{idsection}/multiple-statuses",
            "controller" => "Section",
            "method" => "multipleStatuses", 
        ],
);
?>