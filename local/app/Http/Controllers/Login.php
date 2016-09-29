<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserSignupConfirmation;
use App\Models\UserPreferences;
use App\Models\UserSession;
use App\Models\MasterStatus;

class Login extends Controller{
    public function create(Request $request){
        //throw error if data is not valid
        $this->validate($request, array(
            "data.id"                   =>'required|min:1|max:100',
            "data.pass"                 =>'required|min:1'
        ));

        require_once "__phphash/lib/password.php";

        $id = trim($request->input("data.id"));
        $hpass = $request->input("data.pass");
        $usrs = User::where("nick", "=", $id)->orWhere("email", "=", $id)->take(1)->get();

        if(count($usrs) == 1){
            $data_user = $usrs[0];
            $confirmation = UserSignupConfirmation::where("id_user", "=", $data_user["id"])->get();

            if(count($confirmation) > 0){
                return \Response::json([
                    "message"=> HTTP_message("http_msg_no_confirmed_user")
                ], 400);
            }else{
                if($data_user->available_for_use == '1'){
                    if(password_verify($hpass, $data_user["hash_pass"])){
                        //throw 200 success on login
                        createSession($data_user, $request);
                        return \Response::json([
                        ], 200);
                    }else{
                        //throw 404 invalid password
                        return \Response::json([
                            "message" => HTTP_message("http_msg_invalid_password")
                        ], 404);
                    }
                }else{
                    return \Response::json([
                        "message"=> HTTP_message("http_msg_desactived_user")
                    ], 400);
                }
            }
        }else{
            //throw 404 not found
            return \Response::json([
                "message" => HTTP_message("http_msg_nick_email_doesnt_exist")
            ], 404);
        }
    }

    public function remove_tokens(Request $request){
        $tkns = $request->input("data.tokens");

        if(gettype($tkns) == "array"){
            $arr_tkns = $request->session()->get(PROGRESSIVE_REQUEST_TOKENS);

            foreach ($tkns as $key => $value) {
                unset($arr_tkns[$value]);
            }

            $request->session()->put(PROGRESSIVE_REQUEST_TOKENS, $arr_tkns);
            $request->session()->save();
        }

        return \Response::json([
            'message' => "done, tokens removed :)",
        ], 200);
    }

    public function logout(Request $request){
        $__SESSION__ = UserSession::where("id", "=", $request->session()->get('idsession'))->get();

        if(count($__SESSION__) > 0){
            $__SESSION__[0]->end = sqldate();
            $__SESSION__[0]->save();
            $__SESSION__[0]->__delete__();
        }

        $request->session()->forget(PROGRESSIVE_REQUEST_TOKENS);
        $request->session()->forget('idsession');
        $request->session()->forget('iduser');
        $request->session()->forget('datauser');
        $request->session()->forget('lock_screen');
        $request->session()->forget("inactivity_time_limit_action");

        return redirect("/");
    }

    public function unlock_screen(Request $request){
        if($request->session()->has("iduser")){
            $password = $request->input("data.password");
            $iduser = $request->session()->get("iduser");
            $user = User::where("id", "=", $iduser)->get()[0];

            if(password_verify($password, $user["hash_pass"])){
                $request->session()->put("lock_screen", "0");
                __ACTIVITY__([
                    "operation" => $GLOBALS["__OPERATION__"]["UNLOCK_SCREEN"]
                ]);
                return \Response::json([
                ], 200);
            }else{
                return \Response::json([
                ], 400);
            }
        }else{
            return \Response::json([
            ], 200);
        }
    }

    public function inactivity(Request $request){
        if( $request->session()->has("inactivity_time_limit_action") &&
            $request->session()->get("inactivity_time_limit_action") != "no"){
            if($request->session()->get("inactivity_time_limit_action") == "lock_screen"){
                return redirect("/lock-screen");
            }else{
                return redirect("/logout");
            }
        }else{
            return \Response::json([], 400);
        }
    }
}
