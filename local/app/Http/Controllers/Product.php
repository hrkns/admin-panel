<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\ProductService;
use App\Models\ProductServiceField;
use App\Models\ProductServiceStatus;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Product extends Controller{
    public function create(Request $request){
        $name = $request->input("data.name");
        $code = $request->input("data.code");
        $fields = $request->input("data.fields");

        if(gettype($fields) != "array"){
            $fields = array();
        }

        $instances = $request->input("data.instances");
        $description = $request->input("data.description");

        $new_product_service = new ProductService;
        $new_product_service->name = generateMultilingual($name);
        $new_product_service->description = generateMultilingual($description);
        $new_product_service->code = $code;
        $new_product_service->__create__();

        $lstatus = $request->input("data.status");

        if(gettype($lstatus) != "array"){
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if(count($st)>0){
                $new_product_service->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $new_product_service->available_for_use = $available_for_use?'1':'0';
        $new_product_service->__update__();


        foreach ($fields as $key => $field) {
            $new_product_service->create_Field([
                "code" => $field["code"],
                "name" => generateMultilingual($field["name"]),
                "description" => generateMultilingual($field["description"])
            ]);
        }


        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["CREATE_PRODUCT"]
        ]);

        return \Response::json(array(
            "item"  => array(
                "id"            =>  $new_product_service->id,
                "name"          =>  $name,
                "code"          =>  $code,
                "description"   =>  $description,
                "status"        =>  $lstatus
            ),
            "message" => "mensaje cualquiera",
        ), 201);
    }

    public function index(Request $request){
        return $this->index_items($request, ProductService::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_PRODUCTS");
    }

    public function search(Request $request){
        $keywords_search = $request->input("data.keywords_search");
        $base_items = ProductService:: where("code", "LIKE", "%".$keywords_search."%")
                                ->orWhere("description", "LIKE", "%".$keywords_search."%")
                                ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_PRODUCTS");
    }

    public function read(Request $request, $id){
        $item = ProductService::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_PRODUCT"]
        ]);

        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "status"        =>$ls,
                "id"            =>$item->id,
                "code"            =>$item->code,
            ),
            "message" => "mensaje cualquiera",
        ], 200);
    }

    public function update(Request $request, $id){
        $name = $request->input("data.name");
        $code = $request->input("data.code");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $item = ProductService::where("id", "=", $id)->get()[0];
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
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_PRODUCT"]
        ]);
        return \Response::json(array(
            "message" => "mensaje cualquiera",), 204);
    }

    public function getEstructure(Request $request, $id){
        $fields = ProductServiceField::where("id_product_service", "=", $id)->get();
        $items = array();

        foreach ($fields as $key => $field){
            array_push($items, [
                "id"  =>$field->id,
                "code"=>$field->code,
                "name"=>translate($field->name),
                "description"=>translate($field->description)
            ]);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_PRODUCT_STRUCTURE"]
        ]);

        return \Response::json(array(
            "items" => $items,
            "message" => "mensaje cualquiera",
        ), 200);
    }

    public function setEstructure(Request $request, $id){
        $fields = $request->input("data.fields");

        if(gettype($fields) != "array"){
            $fields = array();
        }

        foreach ($fields as $key => $field){
            if($field["id"] == "-1"){
                $newField = new ProductServiceField;
                $newField->id_product_service = $id;
                $newField->code = $field["code"];
                $newField->name = generateMultilingual($field["name"]);
                $newField->description = generateMultilingual($field["description"]);
                $newField->__create__();
            }else{
                $oldField = ProductServiceField::where("id", "=", $field["id"])->get()[0];
                $oldField->code = $field["code"];
                $oldField->name = setFieldMultilingual($oldField->name, $field["name"]);
                $oldField->description = setFieldMultilingual($oldField->description, $field["description"]);
                $oldField->__update__();
            }
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_PRODUCT_ESTRUCTURE"],
            "message" => "mensaje cualquiera",
        ]);
        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id){
        $item = ProductService::where("id", "=", $id)->get()[0];
        $item->__delete__();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["DELETE_PRODUCT"]
        ]);
        return \Response::json(array(
            "message" => "mensaje cualquiera",), 200);
    }
}
