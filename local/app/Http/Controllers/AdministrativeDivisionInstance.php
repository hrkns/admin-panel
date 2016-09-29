<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterAdministrativeDivisionInstance;
use App\Models\MasterStatus;
use App\Models\UserSession;

class AdministrativeDivisionInstance extends Controller{
    public function create(Request $request){
        $name = $request->input("data.name");
        $desc = $request->input("data.description");
        $lstatus = $request->input("data.status");
        $parents = $request->input("data.parents");
        $types = $request->input("data.types");
        $newitem = new MasterAdministrativeDivisionInstance;

        $newitem->name = generateMultilingual($name);
        $newitem->description = generateMultilingual($desc);
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

        if(gettype($parents) != "array"){
            $parents = array();
        }

        foreach ($parents as $key => $value) {
            $newitem->create_Parent([
                "id_parent"=>$value
            ]);
        }

        if(gettype($types) != "array"){
            $types = array();
        }

        foreach ($types as $key => $value) {
            $newitem->create_Type([
                "id_type"=>$value
            ]);
        }
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["CREATE_ADMINISTRATIVE_DIVISION_INSTANCE"]
        ]);

        return \Response::json([
            'item' => array(
                "id"            =>$newitem->id,
                "name"          =>translate($newitem->name),
                "description"   =>translate($newitem->description),
                "status"        =>$lstatus,
                "parents"       =>$parents,
                "types"         =>$types,
                "code"          =>$request->input("data.code")
            )
        ], 201);
    }

    public function index(Request $request){
        return $this->index_items($request, MasterAdministrativeDivisionInstance::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_ADMINISTRATIVE_DIVISION_INSTANCES", ["status"=>true], 
            function($parms, &$item){
                extract($parms);

                $list_parents = $model->read_Parent;
                $parents=array();

                foreach ($list_parents as $key => $value) {
                    array_push($parents, $value->id_parent);
                }

                $list_types = $model->read_Type;
                $types=array();

                foreach ($list_types as $key => $value) {
                    array_push($types, $value->id_type);
                }

                $item["parents"] = $parents;
                $item["types"] = $types;
            }
        );
    }

    public function search(Request $request){
        $keywords_search = $request->input("data.keywords_search");
        $base_items = MasterAdministrativeDivisionInstance:: where("description", "LIKE", "%".$keywords_search."%")
                                                            ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                                            ->orWhere("code", "LIKE", "%".$keywords_search."%")
                                                            ->get();

        return $this->index_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_ADMINISTRATIVE_DIVISION_INSTANCES", ["status"=>true], 
            function($parms, &$item){
                extract($parms);

                $list_parents = $model->read_Parent;
                $parents=array();

                foreach ($list_parents as $key => $value) {
                    array_push($parents, $value->id_parent);
                }

                $list_types = $model->read_Type;
                $types=array();

                foreach ($list_types as $key => $value) {
                    array_push($types, $value->id_parent);
                }

                $item["parents"] = $parents;
                $item["types"] = $types;
            }
        );
    }

    public function read(Request $request, $id){
        $item = MasterAdministrativeDivisionInstance::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        $parents = $item->read_Parent;
        $lsp=array();

        foreach ($parents as $key => $value) {
            array_push($lsp, $value->id_parent);
        }

        $lttypes = $item->read_Type;
        $types=array();

        foreach ($lttypes as $key => $value) {
            array_push($types, $value->id_type);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_ADMINISTRATIVE_DIVISION_INSTANCE"]
        ]);

        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "status"        =>$ls,
                "id"            =>$item->id,
                "parents"       =>$lsp,
                "types"         =>$types,
                "code"          =>$item->code
            )
        ], 200);
    }

    public function update(Request $request, $id){
        $name = $request->input("data.name");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $parents = $request->input("data.parents");
        $types = $request->input("data.types");
        $item = MasterAdministrativeDivisionInstance::where("id", "=", $id)->get()[0];

        $item->name = setFieldMultilingual($item->name, $name);
        $item->description = setFieldMultilingual($item->description, $description);
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

        if(gettype($parents) != "array"){
            $parents = array();
        }

        $item->delete_Parent();

        foreach ($parents as $key => $value) {
            $item->create_Parent([
                "id_parent"=>$value
            ]);
        }

        if(gettype($types) != "array"){
            $types = array();
        }

        $item->delete_Type();

        foreach ($types as $key => $value) {
            $item->create_Type([
                "id_type"=>$value
            ]);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_ADMINISTRATIVE_DIVISION_INSTANCE"]
        ]);

        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id){
        $item = MasterAdministrativeDivisionInstance::where("id", "=", $id)->get()[0];
        $item->__delete__();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["DELETE_ADMINISTRATIVE_DIVISION_INSTANCE"]
        ]);
        return \Response::json(array(), 200);
    }
}
