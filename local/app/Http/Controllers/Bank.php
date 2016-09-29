<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterBank;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Bank extends Controller{
    public function create(Request $request){
        $newitem = new MasterBank;
        $name = $request->input("data.name");
        $lstatus = $request->input("data.status");
        $newitem->name = generateMultilingual($name);
        $newitem->code = $request->input("data.code");
        $newitem->__create__();

        if(gettype($lstatus) != "array"){
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();
            if(count($st)>0){
                $newitem->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $newitem->available_for_use = $available_for_use?'1':'0';
        $newitem->save();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["CREATE_BANK"]
        ]);

        return \Response::json([
            'item' => array(
                "name"          =>translate($newitem->name),
                "status"        =>$lstatus,
                "id"            =>$newitem->id,
                "code"          =>$request->input("data.code")
            )
        ], 201);
    }

    public function index(Request $request){
        return $this->index_items($request, MasterBank::all(), [
            "code" => [],
            "name" => ["translate"=>true]
        ], "READ_BANKS");
    }

    public function search(Request $request){
        $keywords_search = $request->input("data.keywords_search");
        $base_items = MasterBank:: where("name", "LIKE", "%".$keywords_search."%")
                                ->orWhere("code", "LIKE", "%".$keywords_search."%")
                                ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "name" => ["translate"=>true]
        ], "SEARCH_BANKS");
    }

    public function read(Request $request, $id){
        $item = MasterBank::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_BANK"]
        ]);

        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "status"        =>$ls,
                "id"            =>$item->id,
                "code"          =>$item->code
            )
        ], 200);
    }

    public function update(Request $request, $id){
        $name = $request->input("data.name");
        $status = $request->input("data.status");
        $countries = $request->input("data.countries");
        $item = MasterBank::where("id", "=", $id)->get()[0];
        $item->name = setFieldMultilingual($item->name, $name);
        $item->code = $request->input("data.code");

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
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_BANK"]
        ]);
        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id){
        $item = MasterBank::where("id", "=", $id)->get()[0];
        $item->__delete__();

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["DELETE_BANK"]
        ]);
        return \Response::json(array(), 200);
    }
}
