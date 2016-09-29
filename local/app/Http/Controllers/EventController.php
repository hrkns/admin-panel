<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Event;
use App\Models\EventStatus;
use App\Models\MasterStatus;

class EventController extends Controller{
    public function create(Request $request){
        if(!$request->session()->has("iduser")){
            return \Response::json(array("session" => "finished"), 401);
        }

        $name = sanitize(trim($request->input("data.name")));
        $description = sanitize(trim($request->input("data.description")));
        $new_item = new Event;
        $new_item->name = generateMultilingual($name);
        $new_item->description = generateMultilingual($description);
        $new_item->__create__();
        $lstatus = $request->input("data.status");

        if(gettype($lstatus) != "array"){
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();
            if(count($st)>0){
                $new_item->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $new_item->available_for_use = $available_for_use?'1':'0';
        $new_item->save();

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["CREATE_EVENT"]
        ]);

        return \Response::json([
            'item' => array(
                "id"            =>  $new_item->id,
                "name"          =>  translate($new_item->name),
                "description"   =>  translate($new_item->description),
                "status"        =>  $lstatus
            )
        ], 201);
    }

    public function index(Request $request){
        return $this->index_items($request, Event::all(), [
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_EVENTS");
    }

    public function search(Request $request){
        $keywords_search = $request->input("data.keywords_search");
        $base_items = Event:: where("description", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                        ->get();
        return $this->search_items($request, $base_items, [
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_EVENTS");
    }

    public function read(Request $request, $id){
        if(!$request->session()->has("iduser")){
            return \Response::json(array("session" => "finished"), 401);
        }

        $item = Event::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_EVENT"]
        ]);

        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "status"        =>$ls,
                "id"            =>$item->id,
            )
        ], 200);
    }

    public function update(Request $request, $id){
        if(!$request->session()->has("iduser")){
            return \Response::json(array("session" => "finished"), 401);
        }

        $item = Event::where("id", "=", $id)->get()[0];
        $name = $request->input("data.name");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $item->name = setFieldMultilingual($item->name, $name);
        $item->description = setFieldMultilingual($item->name, $description);

        if(gettype($status) != "array"){
            $status = array();
        }

        $item->delete_Status();
        $available_for_use = false;

        foreach ($status as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if(count($st)>0){
                $item->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_EVENT"]
        ]);

        $item->available_for_use = $available_for_use?'1':'0';
        $item->__update__();
        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id){
        if(!$request->session()->has("iduser")){
            return \Response::json(array("session" => "finished"), 401);
        }

        $item = Event::where("id", "=", $id)->get()[0];
        $item->__delete__();

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["DELETE_EVENT"]
        ]);

        return \Response::json(array(), 200);
    }
}
