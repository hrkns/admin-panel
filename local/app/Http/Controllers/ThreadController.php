<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Thread;
use App\Models\ThreadJoinRequest;
use App\Models\ThreadAdmin;
use App\Models\ThreadMessage;
use App\Models\ThreadSpeaker;
use App\Models\UserSession;

class ThreadController extends Controller
{
    public function create(Request $request)
    {
        $title = $request->input("data.title");
        $description = $request->input("data.description");
        $privacy = $request->input("data.privacy");
        $speakers = $request->input("data.speakers");

        if (gettype($speakers) != "array") {
            $speakers = array();
        }

        $new_thread = new Thread;
        $new_thread->title = $title;
        $new_thread->description = $description;
        $new_thread->privacy = $privacy;
        $new_thread->__create__();

        $admin = new ThreadAdmin;
        $admin->id_thread = $new_thread->id;
        $admin->id_user = $request->session()->get("iduser");
        $admin->permises = json_encode(array("all"=>true));
        $admin->added_by = $request->session()->get("iduser");
        $admin->__create__();

        if (intval($privacy) != 2) {
            foreach ($speakers as $key => $value) {
                $spk = new ThreadSpeaker;
                $spk->id_thread = $new_thread->id;
                $spk->id_user = $value;
                $spk->__create__();
            }
        }

        $admins = array();

        array_push($admins, array(
            "fullname"=>User::where("id", "=", $request->session()->get("iduser"))->get()[0]->fullname,
            "id"=>$request->session()->get("iduser")
        ));

        $ls2=array();
        $participantes = array();

        foreach ($speakers as $key => $value) {
            $us = User::where("id", "=", $value)->get()[0];
            array_push($ls2, array(
                "fullname"=>$us->fullname,
                "id"=>$us->id
            ));
            array_push($participantes, $us->id);
        }

        $list_admins = $request->input("data.admins");

        if (gettype($list_admins) != "array") {
            $list_admins = array();
        }

        foreach ($list_admins as $key => $a) {
            $new_admin = new ThreadAdmin;
            $new_admin->id_thread = $new_thread->id;
            $new_admin->id_user = $a["id_user"];
            $new_admin->permises = json_encode($a["permises"]);
            $new_admin->added_by = $request->session()->get("iduser");
            $new_admin->__create__();

            $fullname = User::where("id", "=", $a["id_user"])->get()[0]->fullname;

            if (intval($privacy) != 2 && !in_array($a["id_user"], $participantes)) {
                $spk = new ThreadSpeaker;
                $spk->id_thread = $new_thread->id;
                $spk->id_user = $a["id_user"];
                $spk->__create__();
                array_push($participantes, $a["id_user"]);
                array_push($ls2, array(
                    "fullname"=>$fullname,
                    "id"=>$a["id_user"]
                ));
            }

            array_push($admins, array(
                "fullname"=>$fullname,
                "id"=>$a["id_user"],
                "permises"=>$a["permises"]
            ));
        }

        operation("CREATE_THREAD");

        return \Response::json(array(
            "item"  => array(
                "id"            =>  $new_thread->id,
                "title"         =>  $title,
                "description"   =>  $description,
                "privacy"       =>  $privacy,
                "speakers"      =>  $ls2,
                "admins"        =>  $admins,
                "joinThread"    =>  0,
                "joinRequests"  =>  array()
            )
        ), 201);
    }

    public function index(Request $request)
    {
        return $this->index_items($request, Thread::orderBy("last_activity", "desc")->get(), [
            "title" => [],
            "description" => [],
            "privacy" => []
        ], "READ_THREADS", ["status"=>false],
            function ($parms, &$item, &$i) {
                extract($parms);
                $cond = intval($model->privacy) != 0 || count(ThreadAdmin::where("id_user", "=", $request->session()->get("iduser"))->where("id_thread", "=", $model->id)->get())>0 || count(ThreadSpeaker::where("id_user", "=", $request->session()->get("iduser"))->where("id_thread", "=", $model->id)->get())>0;

                if ($cond) {
                    $joinThread= intval($model->privacy)==2;
                    $admins = ThreadAdmin::where("id_thread", "=", $model->id)->get();
                    $list_of_admins=array();
                    $IAmAnAdmin = false;

                    foreach ($admins as $key => $value) {
                        $us = User::where("id", "=", $value->id_user)->get()[0];
                        array_push($list_of_admins, array(
                            "fullname"=>$us->fullname,
                            "id"=>$us->id,
                            "permises"=>json_decode($value->permises),
                            "added_by"=>$value->added_by
                        ));
                        $joinThread = $joinThread || intval($value->id_user) == intval($request->session()->get("iduser"));
                        $IAmAnAdmin = $IAmAnAdmin || intval($value->id_user) == intval($request->session()->get("iduser"));
                    }

                    $joinRequests = array();

                    if ($IAmAnAdmin) {
                        $tr = ThreadJoinRequest::where("id_thread", "=", $model->id)->get();

                        foreach ($tr as $key => $value) {
                            $us = User::where("id", "=", $value->id_user)->get()[0];
                            array_push($joinRequests, array(
                                "id"        =>  $us->id,
                                "fullname"  =>  $us->fullname,
                                "idrequest" =>  $value->id
                            ));
                        }
                    }

                    $speakers = ThreadSpeaker::where("id_thread", "=", $model->id)->get();
                    $list_of_participants=array();

                    foreach ($speakers as $key => $value) {
                        $us = User::where("id", "=", $value->id_user)->get();

                        if (count($us)) {
                            $us = $us[0];
                            array_push($list_of_participants, array(
                                "fullname"=>$us->fullname,
                                "id"=>$us->id
                            ));
                            $joinThread = $joinThread || intval($value->id_user) == intval($request->session()->get("iduser"));
                        } else {
                            $value->__delete__();
                        }
                    }

                    $item["admins"] = $list_of_admins;
                    $item["speakers"] = $list_of_participants;
                    $item["joinThread"] = $joinThread?0:1;
                    $item["joinRequest"] = count(ThreadJoinRequest::where("id_thread", "=", $model->id)->where("id_user", "=", $request->session()->get("iduser"))->get());
                    $item["joinRequests"] = $joinRequests;

                    if (!$see_all) {
                        $i++;
                    }
                }
            }
        );
    }

    public function search(Request $request)
    {
        $keywords_search = $request->input("data.keywords_search");
        $base_items = Thread:: where("description", "LIKE", "%".$keywords_search."%")
                            ->orWhere("title", "LIKE", "%".$keywords_search."%")
                            ->get();

        return $this->search_items($request, $base_items, [
            "title" => [],
            "description" => [],
            "privacy" => []
        ], "SEARCH_THREADS", ["status"=>false],
            function ($parms, &$item, &$i) {
                extract($parms);
                $cond = intval($model->privacy) != 0 || count(ThreadAdmin::where("id_user", "=", $request->session()->get("iduser"))->where("id_thread", "=", $model->id)->get())>0 || count(ThreadSpeaker::where("id_user", "=", $request->session()->get("iduser"))->where("id_thread", "=", $model->id)->get())>0;

                if ($cond) {
                    $joinThread= intval($model->privacy)==2;
                    $admins = ThreadAdmin::where("id_thread", "=", $model->id)->get();
                    $list_of_admins=array();
                    $IAmAnAdmin = false;

                    foreach ($admins as $key => $value) {
                        $us = User::where("id", "=", $value->id_user)->get()[0];
                        array_push($list_of_admins, array(
                            "fullname"=>$us->fullname,
                            "id"=>$us->id,
                            "permises"=>json_decode($value->permises),
                            "added_by"=>$value->added_by
                        ));
                        $joinThread = $joinThread || intval($value->id_user) == intval($request->session()->get("iduser"));
                        $IAmAnAdmin = $IAmAnAdmin || intval($value->id_user) == intval($request->session()->get("iduser"));
                    }

                    $joinRequests = array();

                    if ($IAmAnAdmin) {
                        $tr = ThreadJoinRequest::where("id_thread", "=", $model->id)->get();

                        foreach ($tr as $key => $value) {
                            $us = User::where("id", "=", $value->id_user)->get()[0];
                            array_push($joinRequests, array(
                                "id"        =>  $us->id,
                                "fullname"  =>  $us->fullname,
                                "idrequest" =>  $value->id
                            ));
                        }
                    }

                    $speakers = ThreadSpeaker::where("id_thread", "=", $model->id)->get();
                    $list_of_participants=array();

                    foreach ($speakers as $key => $value) {
                        $us = User::where("id", "=", $value->id_user)->get()[0];
                        array_push($list_of_participants, array(
                            "fullname"=>$us->fullname,
                            "id"=>$us->id
                        ));
                        $joinThread = $joinThread || intval($value->id_user) == intval($request->session()->get("iduser"));
                    }

                    $item["admins"] = $list_of_admins;
                    $item["speakers"] = $list_of_participants;
                    $item["joinThread"] = $joinThread?0:1;
                    $item["joinRequest"] = count(ThreadJoinRequest::where("id_thread", "=", $model->id)->where("id_user", "=", $request->session()->get("iduser"))->get());
                    $item["joinRequests"] = $joinRequests;
                } else {
                    $i--;
                }
            }
        );
    }

    public function update(Request $request, $id)
    {
        $title = $request->input("data.title");
        $description = $request->input("data.description");
        $privacy = $request->input("data.privacy");
        $speakers = $request->input("data.speakers");
        $item = Thread::where("id", "=", $id)->get()[0];
        $item->title = $title;
        $item->description = $description;
        $privacy_previous = intval($item->privacy);
        $privacy_now = intval($privacy);
        $item->privacy = $privacy;
        $item->__update__();
        $lastSpeakers = ThreadSpeaker::where("id_thread", "=", $id)->get();

        /*
        foreach ($lastSpeakers as $key => $value) {
            if(count(ThreadAdmin::where("id_thread", "=", $id)->where("id_user", "=", $value->id_user)->get()) == 0){
                $value->__delete__();
            }
        }
        */

        if (gettype($speakers) != "array") {
            $speakers = array();
        }

        foreach ($speakers as $key => $value) {
            $speakers[$key] = strval($value);
        }

        if ($privacy_now < 2 && $privacy_previous < 2) {
            $admins = ThreadAdmin::where("id_thread", "=", $id)->get();

            foreach ($admins as $key => $value) {
                if (strval($value->id_user) != $request->session()->get("iduser") &&
                    !in_array(strval($value->id_user), $speakers)) {
                    $value->__delete__();
                }
            }
        }

        $rts = ThreadSpeaker::where("id_thread", "=", $id)->get();

        foreach ($rts as $key => $value) {
            $value->__delete__();
        }

        foreach ($speakers as $key => $value) {
            $newspk = new ThreadSpeaker;
            $newspk->id_thread = $id;
            $newspk->id_user = $value;
            $newspk->__create__();
        }

        $joinrqs = ThreadJoinRequest::where("id_thread", "=", $id)->get();

        foreach ($joinrqs as $key => $value) {
            if (in_array(intval($value->id_user), $speakers)) {
                $value->__delete__();
            }
        }

        $ret = array();

        if ($privacy_previous == 2 && $privacy_now < 2) {
            $ret = array("speakers"=>array());

            if (intval($request->input("data.add_previous_participants")) == 1) {
                //dependiendo de la decision del usuario, se agregan o no como participantes los que ya han hablado
                $itt = ThreadMessage::distinct()->select("id_user")->where("id_thread", "=", $id)->groupBy("id_user")->get();

                foreach ($itt as $key => $value) {
                    if ($value->id_user != $request->session()->get("iduser") &&
                        count(ThreadSpeaker::where("id_thread", "=", $id)->where("id_user", "=", $value->id_user)->get()) == 0) {
                        $newspk = new ThreadSpeaker;
                        $newspk->id_thread = $id;
                        $newspk->id_user = $value->id_user;
                        $newspk->__create__();
                        array_push($ret["speakers"], $value->id_user);
                    }
                }
            }

            //agregar a los admins como participantes tambien
            $admins = ThreadAdmin::where("id_thread", "=", $id)->get();

            foreach ($admins as $key => $value) {
                if ($value->id_user != $request->session()->get("iduser") &&
                    count(ThreadSpeaker::where("id_thread", "=", $id)->where("id_user", "=", $value->id_user)->get()) == 0) {
                    $newspk = new ThreadSpeaker;
                    $newspk->id_thread = $id;
                    $newspk->id_user = $value->id_user;
                    $newspk->__create__();
                    array_push($ret["speakers"], $value->id_user);
                }
            }
        } elseif ($privacy_previous < 2 && $privacy_now == 2) {
            //se eliminan todos los registros de participantes, ya que ahora cualquiera puede hablar, el inconveniente es que si lo quiere cambiar otra vez a privado tendra que agregarlos manualmente
            $for_delete = ThreadSpeaker::where("id_thread", "=", $id)->get();

            foreach ($for_delete as $key => $value) {
                $value->__delete__();
            }
        }

        operation("UPDATE_THREAD");

        return \Response::json($ret, 200);
    }

    public function delete(Request $request, $id)
    {
        $item = Thread::where("id", "=", $id)->get()[0];
        $lastSpeakers = ThreadSpeaker::where("id_thread", "=", $id)->get();
        $messagesjson = ThreadMessage::where("id_thread", "=", $id)->get()->toJson();
        $item->__delete__();

        operation("DELETE_THREAD");
        return \Response::json(array(), 200);
    }

    public function joinRequest(Request $request, $id)
    {
        $joinrqs = ThreadJoinRequest::where("id_thread", "=", $id)->where("id_user", "=", $request->session()->get("iduser"))->get();

        if (count($joinrqs)>0) {
            $joinrqs[0]->__delete__();
            return \Response::json(array("val"=>0), 200);
        } else {
            $nuevo = new ThreadJoinRequest;
            $nuevo->id_user = $request->session()->get("iduser");
            $nuevo->id_thread = $id;
            $nuevo->__create__();
            operation("CREATE_JOIN_REQUEST");
            return \Response::json(array("val"=>1), 200);
        }
    }

    public function deleteJoinned(Request $request, $id)
    {
        ThreadSpeaker::where("id_thread", "=", $id)->where("id_user", "=", $request->session()->get("iduser"))->get()[0]->__delete__();
        $tmp = Thread::where("id", "=", $id)->get();
        $tmp[0]->last_activity = sqldate();
        $tmp[0]->__update__();
        operation("DELETE_THREAD_SPEAKER");
        return \Response::json(array(), 200);
    }

    public function messages(Request $request, $id)
    {
        $code_status = 200;
        $token = $request->input("data.token");
        $tokens_list = $request->session()->get(PROGRESSIVE_REQUEST_TOKENS);
        $items_to_return = array();

        if (gettype($tokens_list) == "array" && array_key_exists($token, $tokens_list)) {
            $type = $request->input("data.type_request");

            if ($request->has("data.reset") && array_key_exists($type, $tokens_list[$token])) {
                unset($tokens_list[$token][$type]);
            }

            if (!array_key_exists($type, $tokens_list[$token])) {
                $tokens_list[$token][$type] = array( "items"=>ThreadMessage::where("id_thread", "=", $id)->orderBy("id", "desc")->get(),
                                            "index"=>0);
                $tokens_list[$token][$type]["length"] = count($tokens_list[$token][$type]["items"]);
            }

            $temporal_array = $tokens_list[$token][$type];

            for ($i = 0; $i < AMOUNT_ITEMS_PER_REQUEST && $tokens_list[$token][$type]["index"] < $tokens_list[$token][$type]["length"]; $i++) {
                $model = $tokens_list[$token][$type]["items"][$tokens_list[$token][$type]["index"]];
                $user = User::where("id", "=", $model->id_user)->get();

                if (count($user) > 0) {
                    $user = $user[0];
                } else {
                    $user = array(
                        "id" => null,
                        "profile_img" => "default.jpg",
                        "fullname" => term("str_user")
                    );
                }

                array_push($items_to_return, array(
                    "id"=>$model->id,
                    "message"=>$model->message,
                    "user"=>array(
                        "profile_img" => $user["profile_img"],
                        "fullname"=> $user["fullname"],
                        "id"    =>$user["id"]
                    ),
                    "moment"=>$model->moment
                ));
                $tokens_list[$token][$type]["index"]+=1;
            }

            $request->session()->put(PROGRESSIVE_REQUEST_TOKENS, $tokens_list);
        } else {
            $code_status=400;
            $items_to_return = $token;
        }

        operation("READ_THREAD_MESSAGES");

        return \Response::json([
            'items' => $items_to_return
        ], $code_status);
    }

    public function message(Request $request, $id)
    {
        $msg = $request->input("data.msg");
        $newMessage = new ThreadMessage;
        $newMessage->id_thread = $id;
        $newMessage->id_user = $request->session()->get("iduser");
        $newMessage->message = $msg;
        $newMessage->__create__();
        $us = User::where("id", "=", $newMessage->id_user)->get()[0];
        $tmp = Thread::where("id", "=", $id)->get();
        $tmp[0]->last_activity = sqldate();
        $tmp[0]->__update__();

        operation("CREATE_THREAD_MESSAGE");

        return \Response::json(array(
            "item"  => array(
                "id"=>$newMessage->id,
                "message"=>$newMessage->message,
                "user"=>array(
                    "profile_img" => $us->profile_img,
                    "fullname"=> $us->fullname,
                    "id"    =>$us->id
                ),
                "moment"=>ThreadMessage::where("id", "=", $newMessage->id)->get()[0]->moment
            )
        ), 201);
    }

    public function createSpeaker(Request $request, $id)
    {
        $idspeaker = $request->input("data.iduser");
        $v = ThreadJoinRequest::where("id_thread", "=", $id)->where("id_user", "=", $idspeaker)->get()[0];
        $v->__delete__();
        $nuevo = new ThreadSpeaker;
        $nuevo->id_thread = $id;
        $nuevo->id_user = $idspeaker;
        $nuevo->__create__();
        $tmp = Thread::where("id", "=", $id)->get();
        $tmp[0]->last_activity = sqldate();
        $tmp[0]->__update__();
        operation("CREATE_THREAD_PARTICIPANT");
        return \Response::json(array(
        ), 201);
    }

    public function removeJoinRequest(Request $request, $id, $idjoinrq)
    {
        ThreadJoinRequest::where("id", "=", $idjoinrq)->get()[0]->__delete__();
        $tmp = Thread::where("id", "=", $id)->get();
        $tmp[0]->last_activity = sqldate();
        $tmp[0]->__update__();
        operation("REJECT_JOIN_REQUEST");
        return \Response::json(array(
        ), 200);
    }

    public function recentMessages(Request $request, $id)
    {
        $msgs = ThreadMessage::where("moment", ">", $request->input("data.last_date"))
        ->orWhere(function ($query) use ($request) {
            $query->where("moment", "=", $request->input("data.last_date"));
            $query->where("id", ">",  $request->input("data.last_id"));
        })->get();

        $items = array();

        foreach ($msgs as $key => $value) {
            if ($value->id_user != $request->session()->get("iduser")) {
                $us = User::where("id", "=", $value->id_user)->get()[0];

                array_push($items, array(
                    "id"=>$value->id,
                    "message"=>$value->message,
                    "user"=>array(
                        "profile_img" => $us->profile_img,
                        "fullname"=> $us->fullname,
                        "id"    =>$us->id
                    ),
                    "moment"=>$value->moment
                ));
            }
        }

        return \Response::json(array(
            "items" => $items
        ), 200);
    }

    public function createAdmins(Request $request, $id)
    {
        $participantes = array();
        $list_admins = $request->input("data.admins");

        if (gettype($list_admins) != "array") {
            $list_admins = array();
        }

        $privacy = Thread::where("id", "=", $id)->get()[0]->privacy;

        foreach ($list_admins as $key => $a) {
            $new_admin = new ThreadAdmin;
            $new_admin->id_thread = $id;
            $new_admin->id_user = $a["id_user"];
            $new_admin->permises = json_encode($a["permises"]);
            $new_admin->added_by = $request->session()->get("iduser");
            $new_admin->__create__();

            $fullname = User::where("id", "=", $a["id_user"])->get()[0]->fullname;

            if (intval($privacy) != 2 && !in_array($a["id_user"], $participantes)) {
                $spk = new ThreadSpeaker;
                $spk->id_thread = $id;
                $spk->id_user = $a["id_user"];
                $spk->__create__();
                array_push($participantes, $a["id_user"]);
            }
        }

        operation("CREATE_THREAD_ADMINS");

        return \Response::json(array(), 200);
    }

    public function removeAdmin(Request $request, $id, $iduser)
    {
        $admin = ThreadAdmin::where("id_thread", "=", $id)->where("id_user", "=", $iduser)->get();

        if (count($admin)>0) {
            $admin[0]->__delete__();
        }

        operation("DELETE_THREAD_ADMIN");

        return \Response::json(array(), 200);
    }

    public function updatePermises(Request $request, $id, $iduser)
    {
        $v = ThreadAdmin::where("id_thread", "=", $id)->where("id_user", "=", $iduser)->get()[0];
        $v->permises = json_encode($request->input("data.permises"));
        $v->__update__();

        operation("UPDATE_THREAD_ADMIN_PERMISES");

        return \Response::json(array(), 200);
    }
}
