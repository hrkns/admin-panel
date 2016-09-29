<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\MasterDocumentation;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Documentation extends Controller{
    public function create(Request $request){
        $name = $request->input("data.name");
        $code = $request->input("data.code");
        $description = $request->input("data.description");
        $new_language = new MasterDocumentation;
        $new_language->name = generateMultilingual($name);
        $new_language->description = generateMultilingual($description);
        $new_language->code = $code; 
        $new_language->__create__();
        $lstatus = $request->input("data.status");

        if(gettype($lstatus) != "array"){
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if(count($st)>0){
                $new_language->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $new_language->available_for_use = $available_for_use?'1':'0';
        $new_language->save();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["CREATE_DOCUMENTATION"]
        ]);

        return \Response::json(array(
            "item"  => array(
                "id"            =>  $new_language->id,
                "name"          =>  $name,
                "code"          =>  $code,
                "description"   =>  $description,
                "status"        =>  $lstatus
            )
        ), 201);
    }

    public function index(Request $request){
        return $this->index_items($request, MasterDocumentation::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_DOCUMENTATIONS");
    }

    public function search(Request $request){
        $keywords_search = $request->input("data.keywords_search");
        $base_items = MasterDocumentation:: where("code", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("description", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                        ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_DOCUMENTATIONS");
    }

    public function read(Request $request, $id){
        $item = MasterDocumentation::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_DOCUMENTATION"]
        ]);

        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "status"        =>$ls,
                "id"            =>$item->id
            )
        ], 200);
    }

    public function update(Request $request, $id){
        $name = $request->input("data.name");
        $code = $request->input("data.code");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $item = MasterDocumentation::where("id", "=", $id)->get()[0];
        $item->name = setFieldMultilingual($item->name, $name);
        $item->description = setFieldMultilingual($item->description, $description);
        $item->code = $code;

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

        $item->available_for_use = $available_for_use?'1':'0';
        $item->__update__();

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_DOCUMENTATION"]
        ]);

        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id){
        $item = MasterDocumentation::where("id", "=", $id)->get()[0];
        $item->__delete__();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["DELETE_DOCUMENTATION"]
        ]);
        return \Response::json(array(), 200);
    }
}
