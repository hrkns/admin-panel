<?php
use App\Http\Requests;
use App\Models\User;
use App\Models\UserRole;
use App\Models\PanelAdminRole;
use App\Models\UserPreferences;
use App\Models\UserSession;
use App\Models\PanelAdminAction;
use App\Models\PanelAdminRoleSection;
use App\Models\PanelAdminRoleSectionAction;
use App\Models\MasterStatus;
use App\Models\MasterLanguage;

/*******************************************************************************************************************/

//sleep(rand(100, 200));

/*
    unique code used to mark the current request and related operations in the database
*/

define("HASH_OPERATION", sqldate()."|".uniqid());
/*

*/

function __ACTIVITY__($arr)
{
    $__SESSION__ = UserSession::where("id", "=", Request::session()->get('idsession'))->get();

    if (count($__SESSION__) > 0) {
        $__SESSION__ = $__SESSION__[0];
    } else {
        $__SESSION__ = false;
    }

    if ($__SESSION__) {
        $__SESSION__->create_Activity([
            "id_operation"  => $arr["operation"],
            "hash_operation"=> HASH_OPERATION,
            "info"          => isset($arr["operation"]["info"])?json_encode($arr["operation"]["info"]):json_encode([])
        ]);
    }
}

function operation($code, $data=array())
{
    __ACTIVITY__([
        "operation" => $code,
        "info" => $data
    ]);
}

/*******************************************************************************************************************/

/*
    returns a modal (lightbox) view, used mostly to create or edit info on the system
*/

function onModalRequest($context, $action, $extra = array())
{
    $tmp = true?GetForUse("MasterStatus"):MasterStatus::all();
    $list_status = array();

    foreach ($tmp as $key => $value) {
        array_push($list_status, array(
            "id"            => $value->id,
            "name"          => $value->name,
            "code"          => $value->code,
            "value"         => $value->value,
            "description"   => $value->description,
            "show_default"  => $value->show_default,
            "for_delete"    => $value->for_delete
        ));
    }

    $id_section = Request::input("data.idsection");
    $terms = terms($id_section);

    /********************************************************************/
    $roles = UserRole::where("id_user", "=", Request::session()->get("iduser"))->get();
    $dbactions = array();
    $tmp = GetForUse("PanelAdminAction");
    $boolean_actions = array();

    foreach ($tmp as $key => $value) {
        $dbactions[strval($value->id)] = $value;
        $boolean_actions[strval($value->code)] = false;
    }

    //evaluo cada uno de los roles que el usuario posee en cada organizacion (uno por organizacion)
    foreach ($roles as $key => $value) {
        $role_sections = PanelAdminRoleSection::where("id_panel_admin_role", "=", $value->id_role)
                                              ->where("id_section", "=", $id_section)->get();

        foreach ($role_sections as $key => $role_section) {
            $actions = PanelAdminRoleSectionAction::where("id_panel_admin_role_section", "=", $role_section->id)->get();

            foreach ($actions as $kkk => $vv) {
                $boolean_actions[strval($dbactions[$vv->id_action]->code)] = true;
            }
        }
    }
    /********************************************************************/

    $GLOBALS["section_terms"] = $terms;

    $viewParms = array( "list_status"=>$list_status,
                        "terms"=>$terms,
                        "role_actions"=>$boolean_actions,
                        "context"=>$context,
                        "action"=>$action,);

    if (array_key_exists("viewParms", $extra)) {
        foreach ($extra["viewParms"] as $key => $value) {
            $viewParms[$key] = $value;
        }
    }

    return \Response::json([
        'view'  => strval(view("app.modals.".$context."-".$action, $viewParms)->render()),
        'css'   => array(),
        'js'    => array(   WEB_ROOT."/assets/js/actions/".$context."-".$action.".js")
    ], 200);
}

/*******************************************************************************************************************/

/*
    global data to be used in the controllers methods

    it refers to the loaded terms in the system, being used in:
        -> http response messages
*/

$GLOBALS["terms"] = terms();

/*******************************************************************************************************************/

/*
    handlers to modal requests

    modal are used mostly to edit data, among other operations

    check the function onModalRequest, upper
*/

require "routes_operations_views_data.php";

foreach ($operations_views_data as $key => $data) {
    Route::get("/".$data["model"]."/".$data["action"], function () use ($data) {
        operation($data["op_code"]);

        if (!isset($data["data"])) {
            $data["data"] = array();
        }

        return onModalRequest($data["model"], $data["action"], array("viewParms"=>$data["data"]));
    })->middleware("session_verification");
}

/*******************************************************************************************************************/

/*
    here the different requests are performed, the structure of one of it is the next:
        -> route
        -> type (POST, GET, PUT, DELETE)
        -> controller and method of the controller being used to handle it

    the meaning of the middlewares are these:
        -> session_verification: check if it's necessary to have started a session in order to be able to execute that request, in other way it's returned a 401 http code status
*/

require "routes_requests.php";

$middlewares = array("session_verification");

foreach ($requests as $key => $data_request) {
    $v = Route::$data_request["type"]("/".$data_request["route"], $data_request["controller"]."@".$data_request["method"]);

    if (!isset($data_request["middlewares"])) {
        $data_request["middlewares"] = array();
        ;
    }

    foreach ($middlewares as $k => $middleware) {
        if (!isset($data_request["middlewares"][$middleware]) || $data_request["middlewares"][$middleware]) {
            $v->middleware($middleware);
        }
    }

    $v->middleware("installer");
}

/*******************************************************************************************************************/

/*
    this method decides if it's shown the logged or not-logged view
*/
function show_logged_or_not_logged()
{
    include FILE_ADMIN_PANEL_SETTINGS;

    /*
        build the needed parameters to the view
    */

    $userPreferences = UserPreferences::where("id_user", "=", Request::session()->get("iduser"))->get()[0];
    $userData = User::where("id", "=", Request::session()->get("iduser"))->get()[0];
    $role = UserRole::where("id_user", "=", $userData->id)->get();

    if (count($role)>0) {
        $role = $role[0]->id_role;
        $roleData = PanelAdminRole::where("id", "=", $role)->get()[0];
    } else {
        $roleData = [
            "id"=>$userData->id_role,
            "available_for_use" => "",
            "name" => "",
            "description" => "",
            "code" => "",
        ];
    }

    $terms = terms();
    $languages = GetForUse("MasterLanguage");

    $parameters = array(
        "globalSettings" =>$globalSettings,
        "userPreferences"   =>$userPreferences,
        "iduser"            =>Request::session()->get("iduser"),
        "userData"          =>$userData,
        "terms"             =>$terms,
        "roleData"          =>$roleData,
        "languages"         =>$languages
    );

    return view('app.logged', array("parameters" => $parameters));
}

/*
    request to show the root view of the app
*/
Route::get("/", function () {
    Request::session()->put('backward', "");

    if (Request::session()->has("iduser")) {
        return show_logged_or_not_logged();
    } else {
        return view('app.not-logged');
    }
})->middleware("lock_screen")->middleware("installer");

/*
    request to show the terms of use and privacy policy
*/

Route::get("/terms-of-use-and-privacy-policy", function () {
    /*
        Call the admin-panel settins file, where the TSPP is located
    */
    include FILE_ADMIN_PANEL_SETTINGS;

    /*
        get all the dictionary terms with no section related
    */
    $terms = terms();

    return view('app.terms-of-use-and-privacy-policy', [
        "terms" => $terms,
        'terms_of_use_and_privacy_policy' => $globalSettings["terms_of_use_and_privacy_policy"][__LNG__],
        "tab_icon" => $globalSettings["tab_icon"]
    ]);
})->middleware("lock_screen")->middleware("installer");

/*
    request to lock the screen
*/

Route::get("/lock-screen", function () {
    include FILE_ADMIN_PANEL_SETTINGS;
    $terms = terms();
    Request::session()->put("lock_screen", "1");
    $datauser = User::where("id", "=", Request::session()->get("iduser"))->get();

    if (count($datauser) > 0) {
        $datauser = $datauser[0];
    } else {
        return redirect(WEB_ROOT.'/logout');
    }

    return view('app.lock-screen', [
        "terms" => $terms,
        "datauser" => $datauser,
        "tab_icon" => $globalSettings["tab_icon"],
        "name_of_system" => $globalSettings["name_of_system"]
    ]);
})->middleware("installer");

/*
    request for installer interface
*/

Route::get("/installer", function () {
    include FILE_ADMIN_PANEL_SETTINGS;
    $terms = terms();

    return view('app.installer', [
        "terms" => $terms,
        "settings" => $globalSettings,
        "globalSettings" => $globalSettings,
    ]);
});

Route::post("/install", function () {
    include FILE_ADMIN_PANEL_SETTINGS;
    $dbconfig = Request::input("data.db");
    $smtpconfig = Request::input("data.smtp");

    $globalSettings["db_address"] = $dbconfig["host"];
    $globalSettings["db_name"] = $dbconfig["name"];
    $globalSettings["db_user"] = $dbconfig["user"];

    if (strlen($dbconfig["password"]) > 0) {
        $globalSettings["db_password"] = $dbconfig["password"];
    }

    $globalSettings["smtp_host"] = $smtpconfig["host"];
    $globalSettings["smtp_port"] = intval($smtpconfig["port"]);
    $globalSettings["smtp_email_from"] = $smtpconfig["email"];

    if (strlen($smtpconfig["password"]) > 0) {
        $globalSettings["smtp_password_from"] = $smtpconfig["password"];
    }

    $globalSettings["smtp_fullname_from"] = $smtpconfig["fullname"];
    $globalSettings["smtp_secure"] = isTrue($smtpconfig["secure"]);
    $globalSettings["installed"]++;
    saveGlobalSettings($globalSettings);

    return \Response::json([], 200);
});

/*******************************************************************************************************************/

/*
here we handle the GET requests associated with the sections, when they are not made clicking the side menu options but accessing through the url bar
*/

require "routes_sections_url.php";

foreach ($sections_url as $key => $url) {
    Route::get("/".$url, function () {
        if (!Request::session()->has("iduser")) {
            return redirect("/");
        } else {
            return show_logged_or_not_logged();
        }
    })->middleware("lock_screen")->middleware("installer");
}
