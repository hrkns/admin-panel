<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Client;
use App\Models\MasterStatus;
use App\Models\UserSession;

class ClientController extends Controller{
    public function create(Request $request){
        $name = $request->input("data.name");
        $img = $request->input("data.img");

        if(strpos($img, "default.jpg") === false){
            $name_img = rand_string(true).".jpeg";
            base64_to_img($img, SYSTEM_DIR_CLIENT_IMGS.$name_img);
            //create thumbnails
            /**/
        }else{
            $name_img = "default.jpg";
        }

        $newClient = new Client;
        $newClient->name = $name;
        $newClient->img = $name_img;
        $newClient->__create__();
        $lstatus = $request->input("data.status");

        if(gettype($lstatus) != "array"){
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if(count($st)>0){
                $newClient->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $newClient->available_for_use = $available_for_use?'1':'0';
        $newClient->save();
        $coms = $request->input("data.media");

        if(gettype($coms) != "array"){
            $coms = array();
        }

        foreach ($coms as $key => $value){
            $newClient->create_Media([
                "id_media"=>$value["code"],
                "value"=>$value["value"]
            ]);
        }

        $ladres = $request->input("data.addresses");

        if(gettype($ladres) != "array"){
            $ladres = array();
        }

        foreach ($ladres as $key => $value) {
            $newClient->create_Address([
                "address"=>$value,
            ]);
        }

        $ids = $request->input("data.documentation");

        if(gettype($ids) != "array"){
            $ids = array();
        }

        foreach ($ids as $key => $value) {
            $newClient->create_Documentation([
                "value"=>$value["code"],
                "id_documentation"=>$value["code"]
            ]);
        }
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["CREATE_CLIENT"]
        ]);

        return \Response::json([
            'item' => array(
                "id"            =>  $newClient->id,
                "name"          =>  $newClient->name,
                "img"           =>  $newClient->img,
                "status"        =>  $lstatus
            )
        ], 201);
    }

    public function index(Request $request){
        return $this->index_items($request, Client::all(), [
            "name" => [],
            "img" => []
        ], "READ_CLIENTS");
    }

    public function search(Request $request){
        $keywords_search = $request->input("data.keywords_search");
        $base_items = Client::where("name", "LIKE", "%".$keywords_search."%")
                            ->get();
        return $this->search_items($request, $base_items, [
            "name" => [],
            "img" => []
        ], "SEARCH_CLIENTS");
    }

    public function read(Request $request, $id){
        $Client =    Client::where("id", "=", $id)->get()[0];
        $response = array("item"=>array(
            "id"=>$id,
            "img"=>$Client->img,
            "name"=>$Client->name,
            "status"=>array(),
            "media"=>array(),
            "documentation"=>array(),
            "addresses"=>array()
        ));
        $v1 = $Client->read_Status;
        $v2 = $Client->read_Documentation;
        $v3 = $Client->read_Media;
        $v4 = $Client->read_Address;

        foreach ($v1 as $key => $value) {
            array_push($response["item"]["status"], $value->id_status);
        }

        foreach ($v2 as $key => $value) {
            array_push($response["item"]["documentation"], array(
                "code"=>$value->id_documentation,
                "value"=>$value->value
            ));
        }

        foreach ($v3 as $key => $value) {
            array_push($response["item"]["media"], array(
                "code"=>$value->id_media,
                "value"=>$value->value
            ));
        }

        foreach ($v4 as $key => $value) {
            array_push($response["item"]["addresses"], $value->address);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_CLIENT"]
        ]);

        return \Response::json($response, 200);
    }

    public function update(Request $request, $id){
        $editClient = Client::where("id", "=", $id)->get()[0];
        $name = $request->input("data.name");
        $img = $request->input("data.img");

        if(strpos($img, "assets") === false && strpos($img, "default.jpg") === false){
            $name_img = rand_string(true).".jpeg";
            base64_to_img($img, SYSTEM_DIR_CLIENT_IMGS.$name_img);
            $editClient->img = $name_img;
            //create thumbnails
            /**/
        }else if(strpos($img, "default.jpg") != false){
            $name_img = "default.jpg";
            $editClient->img = $name_img;
        }

        $editClient->name = $name;
        $lstatus = $request->input("data.status");

        if(gettype($lstatus) != "array"){
            $lstatus = array();
        }

        $available_for_use = false;
        $editClient->delete_Status();

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if(count($st)>0){
                $editClient->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $editClient->available_for_use = $available_for_use?'1':'0';
        $editClient->__update__();
        $coms = $request->input("data.media");

        if(gettype($coms) != "array"){
            $coms = array();
        }

        $editClient->delete_Media();

        foreach ($coms as $key => $value) {
            $editClient->create_Media([
                "id_media"=>$value["code"],
                "value"=>$value["value"]
            ]);
        }

        $ladres = $request->input("data.addresses");

        if(gettype($ladres) != "array"){
            $ladres = array();
        }

        $editClient->delete_Address();

        foreach ($ladres as $key => $value) {
            $editClient->create_Address([
                "address"=>$value
            ]);
        }

        $ids = $request->input("data.documentation");

        if(gettype($ids) != "array"){
            $ids = array();
        }

        $editClient->delete_Documentation();


        foreach ($ids as $key => $value) {
            $editClient->create_Documentation([
                "id_documentation"=>$value["code"],
                "value"=>$value["value"]
            ]);
        }
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_CLIENT"]
        ]);

        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id){
        $item = Client::where("id", "=", $id)->get()[0];
        $item->__delete__();

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["DELETE_CLIENT"]
        ]);
        return \Response::json(array(), 200);
    }
}