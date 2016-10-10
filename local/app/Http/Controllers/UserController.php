<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserAccountRecovering;
use App\Models\UserStatus;
use App\Models\UserSession;
use App\Models\UserSessionActivity;
use App\Models\UserSignupConfirmation;
use App\Models\DeletedUserSession;
use App\Models\DeletedUserSessionActivity;
use App\Models\CreatedUserSession;
use App\Models\CreatedUserSessionActivity;
use App\Models\UserPreferences;
use App\Models\MasterStatus;
use App\Models\PanelAdminOperation;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $fullname = $request->input("data.fullname");
        $nick = $request->input("data.nick");
        $pass = $request->input("data.pass");
        $email = $request->input("data.email");
        $profile_img = $request->input("data.profile_img");

        $exists = User::where("email", "=", $email)->orWhere("nick", "=", $nick)->get();

        if (count($exists) > 0) {
            $exists = $exists[0];

            if ($exists->nick == $nick) {
                return \Response::json([
                    "message"=>HTTP_message("http_msg_nick_already_used")
                ], 401);
            } else {
                return \Response::json([
                    "message"=>HTTP_message("http_msg_nick_email_used")
                ], 401);
            }
        }

        if (strpos($profile_img, "default") === false) {
            $name_img = rand_string(true).".jpeg";
            $fullroute = SYSTEM_DIR_PROFILE_IMGS.$name_img;
            base64_to_img($profile_img, $fullroute);
            //create thumbnails
            //$imagick = new \Imagick($fullroute);
            //$imagick->thumbnailImage(100, 100, true, true);
        } else {
            $name_img = "default.jpg";
        }

        $newuser = new User;
        $newuser->fullname = $fullname;
        $newuser->nick = $nick;
        $newuser->email = $email;
        $newuser->profile_img = $name_img;
        $newuser->default_language_session = __LNG__;
        require_once "__phphash/lib/password.php";
        $newuser->hash_pass = password_hash($pass, PASSWORD_BCRYPT);
        $newuser->__create__();
        $statuses = $request->input("data.status");

        if (gettype($statuses) != "array") {
            $statuses = array();
        }

        $available_for_use = false;

        foreach ($statuses as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if (count($st)>0 && !($request->has("data.role") && $st[0]->code == "SIGNUP_CONFIRMATION")) {
                $newuser->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $newuser->available_for_use = $available_for_use?'1':'0';
        $newuser->save();
        $coms = $request->input("data.media");

        if (gettype($coms) != "array") {
            $coms = array();
        }

        foreach ($coms as $key => $value) {
            $newuser->create_Media([
                "id_media"=>$value["code"],
                "value"=>$value["value"]
            ]);
        }

        $preferences = new UserPreferences;
        include FILE_ADMIN_PANEL_SETTINGS;
        $preferences->id_user = $newuser->id;
        $globalLogo = substr(strrchr($globalSettings["logo"], "/"), 1);
        $preferences->logo = $globalLogo;
        $preferences->chat_alert_sound = $globalSettings["chat_alert_sound"];
        $preferences->session_duration_amount_val = $globalSettings["general_session_duration_amount_val"];
        $preferences->session_duration_amount_type = $globalSettings["general_session_duration_amount_type"];
        $preferences->use_inactivity_time_limit_as = $globalSettings["apply_default_config_inactivity_time_limit"];
        $preferences->inactivity_time_limit_amount_val = $globalSettings["default_config_inactivity_time_limit_amount_val"];
        $preferences->inactivity_time_limit_amount_type = $globalSettings["default_config_inactivity_time_limit_amount_type"];
        $preferences->format_show_items = $globalSettings["format_show_items"];
        $preferences->__create__();

        if ($request->has("data.role")) {
            $newuser->create_Role([
                "id_role"=>$request->input("data.role")
            ]);
        }

        $content = "";
        $append_http_msg = "";

        if ($request->has("data.role")) {
            $content = "<p>Welcome to Admin-Panel</p><br>".
                        "<p><strong>Email: </strong>".$email."</p>".
                        "<p><strong>Nick: </strong>".$nick."</p>".
                        "<p><strong>Password: </strong>".$pass."</p><br>".
                        "<p>We recommend you to change the password as soon as possible.</p>".
                        "<p><strong><a href = '".WEB_URL.WEB_ROOT."'>Login link</a></strong></p>";
        } else {
            switch ($globalSettings['content_registration_email']) {
                case 'link':{
                    $hash = rand_string();
                    $newuser->create_SignupConfirmation([
                        "hash" => $hash
                    ]);
                    $content =   "<p><a href = '".WEB_URL.WEB_ROOT."/signup-confirmation/".$hash."'>Click <strong>aquí</strong></a> to access.</p>";
                    $append_http_msg = HTTP_message("str_autologin_link_sent");
                }break;
                case 'admin':{
                    $content =  "<p>Welcome to Admin-Panel. You'll receive another mail when the administrator process your request.</p><br>".
                                "<p><strong>Email: </strong>".$email."</p>".
                                "<p><strong>Nick: </strong>".$nick."</p>".
                                "<p><strong>Password: </strong>".$pass."</p><br>";
                    $id_status_signup_confirmation = $GLOBALS["__STATUS__"]["SIGNUP_CONFIRMATION"];
                    $newuser->create_Status([
                        "id_status" => $id_status_signup_confirmation
                    ]);
                    array_push($statuses, $id_status_signup_confirmation);
                    $append_http_msg = HTTP_message("str_admin_is_gonna_do_something_about_register");
                }break;
            }
        }

        sendEmail([
            "email" => $newuser->email,
            "fullname" => $newuser->fullname,
            "title" => "Admin-Panel Sign-Up",
            "content" => $content
        ]);

        operation("CREATE_USER");

        return \Response::json([
            'item' => array(
                "id"            =>  $newuser->id,
                "fullname"      =>  $newuser->fullname,
                "profile_img"   =>  $newuser->profile_img,
                "nick"          =>  $newuser->nick,
                "email"         =>  $newuser->email,
                "status"        =>  $statuses
            ),
            "message"   =>  HTTP_message("http_msg_user_registered")." ".$append_http_msg
        ], 201);
    }

    public function index(Request $request)
    {
        return $this->index_items($request, User::all(), [
            "fullname" => [],
            "profile_img" => [],
            "nick" => [],
            "email" => [],
        ], "READ_USERS");
    }

    public function search(Request $request)
    {
        $keywords_search = $request->input("data.keywords_search");
        $base_items = User::where("fullname", "LIKE", "%".$keywords_search."%")
                            ->orWhere("nick", "LIKE", "%".$keywords_search."%")
                            ->orWhere("email", "LIKE", "%".$keywords_search."%")
                            ->get();
        return $this->search_items($request, $base_items, [
            "fullname" => [],
            "profile_img" => [],
            "nick" => [],
            "email" => [],
        ], "SEARCH_USERS");
    }

    public function read(Request $request, $id = null)
    {
        if ($id == null) {
            $id = $request->session()->get("iduser");
        }

        $user =    User::where("id", "=", $id)->get()[0];
        $role = $user->read_Role;
        $response = array("item"=>array(
            "id"=>$id,
            "nick"=>$user->nick,
            "email"=>$user->email,
            "default_language_session"=>$user->default_language_session,
            "profile_img"=>$user->profile_img,
            "fullname"=>$user->fullname,
            "status"=>array(),
            "media"=>array(),
            "role"=>count($role) > 0?$role[0]->id_role:null
        ));
        $v1 = $user->read_Status;
        $v3 = $user->read_Media;

        foreach ($v1 as $key => $value) {
            array_push($response["item"]["status"], $value->id_status);
        }

        foreach ($v3 as $key => $value) {
            array_push($response["item"]["media"], array(
                "code"=>$value->id_media,
                "value"=>$value->value
            ));
        }

        operation("READ_USER");

        return \Response::json($response, 200);
    }

    public function update(Request $request, $id = null)
    {
        if ($id == null) {
            $id = $request->session()->get("iduser");
        }

        $edituser = User::where("id", "=", $id)->get()[0];
        $fullname = $request->input("data.fullname");
        $nick = $request->input("data.nick");
        $pass = $request->input("data.pass");
        $email = $request->input("data.email");
        $profile_img = $request->input("data.profile_img");

        if (strpos($profile_img, "assets") === false && strpos($profile_img, "default.jpg") === false) {
            $name_img = rand_string(true).".jpeg";
            base64_to_img($profile_img, SYSTEM_DIR_PROFILE_IMGS.$name_img);
            $edituser->profile_img = $name_img;
            //create thumbnails
            /**/
        } elseif (strpos($profile_img, "default.jpg") != false) {
            $name_img = "default.jpg";
            $edituser->profile_img = $name_img;
        }

        $edituser->fullname = $fullname;

        include FILE_ADMIN_PANEL_SETTINGS;
        $globalSettings["default_user_changed"] = ($edituser->nick == 'developer' && $nick != 'developer') || strlen($pass)>0;
        saveGlobalSettings($globalSettings);

        $edituser->nick = $nick;
        $edituser->email = $email;

        if (strlen($pass)>0) {
            require_once "__phphash/lib/password.php";
            $edituser->hash_pass = password_hash($pass, PASSWORD_BCRYPT);
        }

        $lstatus = $request->input("data.status");

        if (gettype($lstatus) == "array") {
            $available_for_use = false;
            $edituser->delete_Status();

            foreach ($lstatus as $key => $value) {
                $st = MasterStatus::where("id", "=", $value)->get();

                if (count($st)>0) {
                    $edituser->create_Status([
                        "id_status"=>$value
                    ]);
                    $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
                }
            }

            $edituser->available_for_use = $available_for_use?'1':'0';
        }

        $edituser->__update__();
        $coms = $request->input("data.media");

        if (gettype($coms) != "array") {
            $coms = array();
        }

        $edituser->delete_Media();
        foreach ($coms as $key => $value) {
            $edituser->create_Media([
                "id_media"=>$value["code"],
                "value"=>$value["value"]
            ]);
        }


        if ($request->has("data.role")) {
            $edituser->delete_Role();
            $edituser->create_Role([
                "id_role"=>$request->input("data.role")
            ]);
        }

        operation("UPDATE_USER");

        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id)
    {
        $item = User::where("id", "=", $id)->get()[0];
        $item->__delete__();

        operation("DELETE_USER");

        return \Response::json(array(), 200);
    }

    public function accountRecovering(Request $request)
    {
        $email = $request->input("data.email");
        $us = User::where("email", "=", $email)->get();

        if (count($us)>0) {
            $us = $us[0];

            if ($us->available_for_use == "0") {
                return \Response::json(array(
                ), 400);
            }

            include FILE_ADMIN_PANEL_SETTINGS;
            $append_http_msg = "";

            if ($globalSettings["account_recovering_mechanism_automatic"] == "0") {
                $id_status_account_recovering = $GLOBALS["__STATUS__"]["ACCOUNT_RECOVERING"];

                if (count(UserStatus::where("id_item", "=", $us->id)->where("id_status", "=", $id_status_account_recovering)->get()) == 0) {
                    $us->create_Status([
                        "id_status" => $id_status_account_recovering
                    ]);
                }

                sendEmail([
                    "email"     => $us->email,
                    "fullname"  => $us->fullname,
                    "title"     => "Account Recovering",
                    "content"   => "<p>You'll receive another email when the administrator process your request</p>"
                ]);

                $append_http_msg = HTTP_message("str_admin_is_gonna_do_something");
            } elseif ($globalSettings["account_recovering_mechanism"] == "link") {
                $hash = rand_string();
                $us->create_AccountRecovering([
                    "hash"  => $hash
                ]);
                sendEmail([
                    "email"     => $us->email,
                    "fullname"  => $us->fullname,
                    "title"     => "Account Recovering",
                    "content"   => "<p>Click <a href = '".WEB_URL.WEB_ROOT."/account-recovering/".$hash."'><strong>here</strong></a></p>"
                ]);

                $append_http_msg = HTTP_message("str_recovering_account_link_sent");
            } else {
                $new_password = substr(rand_string(), 0, 8);
                $us->hash_pass = password_hash($new_password, PASSWORD_BCRYPT);
                $us->__update__();
                sendEmail([
                    "email"     => $us->email,
                    "fullname"  => $us->fullname,
                    "title"     => "Account Recovering",
                    "content"   => "<p><strong>New Password: </strong>".$new_password."</p>"
                ]);

                $append_http_msg = HTTP_message("str_new_password_in_email");
            }

            return \Response::json(array(
                "message" => HTTP_message("http_msg_account_recovering_done")." ".$append_http_msg
            ), 200);
        } else {
            return \Response::json(array(
                "message" => HTTP_message("http_msg_user_doesnt_exist")
            ), 404);
        }
    }

    public function sessionsHistory(Request $request, $id)
    {
        $activas = UserSession::where("id_user", "=", $id)->get();
        $terminadas = DeletedUserSession::all();
        $tmp =  [];

        foreach ($terminadas as $key => $value) {
            if (json_decode($value->info)->id_user == $id) {
                $tmp[json_decode($value->info)->id] = json_decode($value->info);
            }
        }

        $array1 = array();
        $array2 = array();

        foreach ($activas as $key => $value) {
            $value->info = json_decode($value->info);
            array_push($array1, $value);
        }

        foreach ($tmp as $key => $value) {
            $value->info = json_decode($value->info);
            array_push($array2, $value);
        }

        function order($x, $z)
        {
            if ($x->id == $z->id) {
                return 0;
            }

            return $x->id < $z->id?-1:1;
        }

        $return = array_merge($array1, $array2);
        usort($return, function ($x, $z) {
            if ($x->id == $z->id) {
                return 0;
            }

            return $x->id < $z->id?-1:1;
        });

        operation("READ_USER_SESSIONS");

        return \Response::json(array(
            "items" => $return,
        ), 200);
    }

    public function sessionOperations(Request $request, $id, $idsession)
    {
        $session = DeletedUserSession::where("id_item", "=", $idsession)->get();
        $activities = array();

        if (count($session) == 0) {
            $session = UserSession::where("id", "=", $idsession)->get();

            if (count($session) > 0) {
                $items = UserSessionActivity::where("id_user_session", "=", $idsession)->orderBy("id", "asc")->get();
            } else {
                //return 404;
                return \Response::json(array(), 404);
            }
        } else {
            $items = DeletedUserSessionActivity::all();
            $tmp = array();
            foreach ($items as $key => $value) {
                $obj = json_decode($value->info);
                if (gettype($obj) == "object" && $obj->id_user_session == $idsession) {
                    $tmp[$value->id_item] = json_decode($value->info);
                }
            }
            $items = $tmp;
        }

        if (count($items) > 0) {
            $ops = array();
            $createdQuery = null;
            $queryForOps = null;
            $tmp = array();

            foreach ($items as $key => $activity) {
                if ($createdQuery != null) {
                    $createdQuery->orWhere("id_item", "=", $activity->id);
                } else {
                    $createdQuery = CreatedUserSessionActivity::where("id_item", "=", $activity->id);
                }

                if ($queryForOps != null) {
                    if (!in_array($activity->id_operation, $ops)) {
                        $queryForOps->orWhere("id", "=", $activity->id_operation);
                        array_push($ops, $activity->id_operation);
                    }
                } else {
                    $queryForOps = PanelAdminOperation::where("id", "=", $activity->id_operation);
                    array_push($ops, $activity->id_operation);
                }

                $tmp[$activity->id] = $activity;
            }

            $items = $tmp;
            $dataOperations = $queryForOps->get();
            $tmp = array();

            foreach ($dataOperations as $key => $value) {
                $tmp[$value->id] = $value;
            }

            $dataOperations = $tmp;
            $dataCreated = $createdQuery->get();
            $tmp = array();

            foreach ($dataCreated as $key => $value) {
                $items[strval($value->id_item)]->date = $value->created_at;
                $items[strval($value->id_item)]->operation = array(
                    "id"            =>  $dataOperations[$items[strval($value->id_item)]->id_operation]->id,
                    "code"          =>  $dataOperations[$items[strval($value->id_item)]->id_operation]->code,
                    "name"          =>  translate($dataOperations[$items[strval($value->id_item)]->id_operation]->name),
                    "description"   =>  translate($dataOperations[$items[strval($value->id_item)]->id_operation]->description)
                );
            }
        }

        operation("READ_USER_SESSION_OPERATIONS");

        return \Response::json(array(
            "items" => $items
        ), 200);
    }

    public function signupConfirmation(Request $request, $hash)
    {
        $signupConfirmation = UserSignupConfirmation::where("hash", "=", $hash)->get();

        if (count($signupConfirmation) > 0) {
            $signupConfirmation = $signupConfirmation[0];
            $data_user = User::where("id", "=", $signupConfirmation->id_user)->get()[0];
            $signupConfirmation->__delete__();
            createSession($data_user, $request);
            return redirect("/home");
        } else {
            return \Response::json(array(), 404);
        }
    }

    public function postSignupConfirmation(Request $request, $iduser)
    {
        $id_status_signup_confirmation = $GLOBALS["__STATUS__"]["SIGNUP_CONFIRMATION"];
        $v = UserStatus::where("id_item", "=", $iduser)->where("id_status", "=", $id_status_signup_confirmation)->get();

        if (count($v) > 0) {
            $v[0]->__delete__();
        }

        $user = User::where("id", "=", $iduser)->get()[0];
        $user->available_for_use = 1;
        $id_status_enabled = $GLOBALS["__STATUS__"]["ENABLED"];
        $user->create_Status([
            "id_status" => $id_status_enabled
        ]);
        $user->save();

        //enviar correo avisando que ya puede ingresar
        sendEmail([
            "email" => $user->email,
            "fullname" => $user->fullname,
            "title" => "Welcome to Admin-Panel",
            "content" => " <p>Welcome to Admin-Panel</p><br>".
                            "<p>You can already sign-in the system.</p>"
        ]);

        operation("APPROVE_USER_SIGNUP");

        return \Response::json([
        ], 200);
    }

    public function denySignup(Request $request, $iduser)
    {
        $user = User::where("id", "=", $iduser)->get()[0];

        //enviar correo avisando que ya puede ingresar
        sendEmail([
            "email" => $user->email,
            "fullname" => $user->fullname,
            "title" => "Admin-Panel Sign-Up Denied",
            "content" => " <p>Your sign-up request has been denied. Your info has been removed from the database. You can already request your sign-up in our site.</p><br>"
        ]);
        $user->__delete__();

        operation("DENY_USER_SIGNUP");

        return \Response::json([
        ], 200);
    }

    public function getAccountRecovering(Request $request, $hash)
    {
        $recover = UserAccountRecovering::where("hash", "=", $hash)->get();

        if (count($recover) > 0) {
            $data_user = User::where("id", "=", $recover[0]->id_user)->get()[0];
            $recover[0]->__delete__();
            createSession($data_user, $request);
            return redirect("/home");
        } else {
            return \Response::json([
            ], 404);
        }
    }

    public function denyAccountRecovering(Request $request, $iduser)
    {
        $id_status_account_recovering = $GLOBALS["__STATUS__"]["ACCOUNT_RECOVERING"];
        $us = User::where("id", "=", $iduser)->get()[0];
        UserStatus::where("id_item", "=", $iduser)->where("id_status", "=", $id_status_account_recovering)->get()[0]->__delete__();
        sendEmail([
            "email"     => $us->email,
            "fullname"  => $us->fullname,
            "title"     => "Account recovering request denied",
            "content"   => "<p>What the subject says. You can request the recovering of your account whenever you want.</p>"
        ]);

        operation("DENY_ACCOUNT_RECOVERING");

        return \Response::json([
        ], 200);
    }

    public function proccessAccountRecovering(Request $request, $iduser)
    {
        $type = $request->input("data.type");
        $us = User::where("id", "=", $iduser)->get()[0];

        if ($type == "link") {
            $hash = rand_string();
            $us->create_AccountRecovering([
                "hash"  => $hash
            ]);
            sendEmail([
                "email"     => $us->email,
                "fullname"  => $us->fullname,
                "title"     => "Account Recovering",
                "content"   => "<p>Click <a href = '".WEB_URL.WEB_ROOT."/account-recovering/".$hash."'><strong>here</strong></a></p>"
            ]);
        } else {
            if ($request->has("data.password")) {
                $password = $request->input("data.password");
            } else {
                $password = substr(rand_string(), 0, 8);
            }
            $us->hash_pass = password_hash($password, PASSWORD_BCRYPT);
            $us->__update__();
            sendEmail([
                "email"     => $us->email,
                "fullname"  => $us->fullname,
                "title"     => "Account Recovering",
                "content"   => "<p><strong>New Password: </strong>".$password."</p>"
            ]);
        }

        $id_status_account_recovering = $GLOBALS["__STATUS__"]["ACCOUNT_RECOVERING"];
        UserStatus::where("id_item", "=", $iduser)->where("id_status", "=", $id_status_account_recovering)->get()[0]->__delete__();

        operation("PROCCESS_ACCOUNT_RECOVERING");

        return \Response::json([
        ], 200);
    }
}
