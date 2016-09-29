<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Organization;
use App\Models\MasterStatus;
use App\Models\UserSession;

class OrganizationController extends Controller{
    public function create(Request $request){
        $name = $request->input("data.name");
        $img = $request->input("data.img");

        if(strpos($img, "default.jpg") === false){
            $name_img = rand_string(true).".jpeg";
            base64_to_img($img, SYSTEM_DIR_ORGANIZATION_IMGS.$name_img);
            //create thumbnails
            /**/
        }else{
            $name_img = "default.jpg";
        }

        $newOrganization = new Organization;
        $newOrganization->name = $name;
        $newOrganization->img = $name_img;
        $newOrganization->__create__();
        $lstatus = $request->input("data.status");

        if(gettype($lstatus) != "array"){
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if(count($st)>0){
                $newOrganization->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $newOrganization->available_for_use = $available_for_use?'1':'0';
        $newOrganization->save();
        $coms = $request->input("data.media");

        if(gettype($coms) != "array"){
            $coms = array();
        }

        foreach ($coms as $key => $value) {
            $newOrganization->create_Media([
                "id_media"=>$value["code"],
                "value"=>$value["value"]
            ]);
        }

        $ladres = $request->input("data.addresses");

        if(gettype($ladres) != "array"){
            $ladres = array();
        }

        foreach ($ladres as $key => $value) {
            $newOrganization->create_Address([
                "address"=>$value
            ]);
        }

        $ids = $request->input("data.documentation");

        if(gettype($ids) != "array"){
            $ids = array();
        }

        foreach ($ids as $key => $value) {
            $newOrganization->create_Documentation([
                "id_documentation"=>$value["code"],
                "value"=>$value["value"]
            ]);
        }

        $payment_methods = $request->input("data.payment_methods");

        if(gettype($payment_methods) != "array"){
            $payment_methods = array("banks"=>array(), "credit-cards"=>array(), "e-payments"=>array());
        }

        foreach ($payment_methods as $key => $value) {
            if(gettype($value) != "array"){
                $payment_methods[$key] = array();
            }
        }

        //banks
        if(isset($payment_methods["banks"])){
            foreach ($payment_methods["banks"] as $key => $method) {
                $newOrganization->create_PaymentMethodBank([
                    "id_method"=>$method["id_method"],
                    "info"=>$method["info"]
                ]);
            }
        }

        //electronicos
        if(isset($payment_methods["credit-cards"])){
            foreach ($payment_methods["credit-cards"] as $key => $method) {
                $newOrganization->create_PaymentMethodCreditCard([
                    "id_method"=>$method["id_method"],
                    "info"=>$method["info"]
                ]);
            }
        }

        //tarjetas de credito
        if(isset($payment_methods["e-payments"])){
            foreach ($payment_methods["e-payments"] as $key => $method) {
                $newOrganization->create_PaymentMethodElectronic([
                    "id_method"=>$method["id_method"],
                    "info"=>$method["info"]
                ]);
            }
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["CREATE_ORGANIZATION"]
        ]);

        return \Response::json([
            'item' => array(
                "id"            =>  $newOrganization->id,
                "name"          =>  $newOrganization->name,
                "img"           =>  $newOrganization->img,
                "status"        =>  $lstatus
            )
        ], 201);
    }

    public function index(Request $request){
        return $this->index_items($request, Organization::all(), [
            "code" => [],
            "img" => [],
            "name" => []
        ], "READ_ORGANIZATIONS");
    }

    public function search(Request $request){
        $keywords_search = $request->input("data.keywords_search");
        $base_items = Organization::where("name", "LIKE", "%".$keywords_search."%")->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "img" => [],
            "name" => []
        ], "SEARCH_ORGANIZATIONS");
    }

    public function read(Request $request, $id){
        $Organization =    Organization::where("id", "=", $id)->get()[0];
        $response = array("item"=>array(
            "id"=>$id,
            "img"=>$Organization->img,
            "name"=>$Organization->name,
            "status"=>array(),
            "media"=>array(),
            "documentation"=>array(),
            "addresses"=>array(),
            "payment_methods"=>array(
            "banks"=>array(),
            "e-payments"=>array(),
            "credit-cards"=>array()
            )
        ));
        $v1 = $Organization->read_Status;
        $v2 = $Organization->read_Documentation;
        $v3 = $Organization->read_Media;
        $v4 = $Organization->read_Address;

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

        $marr = $Organization->read_PaymentMethodBank;

        foreach ($marr as $key => $value) {
            array_push($response["item"]["payment_methods"]["banks"], array(
                "id_method"=>$value->id_method,
                "info"=>$value->info
            ));
        }

        $marr = $Organization->read_PaymentMethodCreditCard;

        foreach ($marr as $key => $value) {
            array_push($response["item"]["payment_methods"]["credit-cards"], array(
                "id_method"=>$value->id_method,
                "info"=>$value->info
            ));
        }

        $marr = $Organization->read_PaymentMethodElectronic;

        foreach ($marr as $key => $value) {
            array_push($response["item"]["payment_methods"]["e-payments"], array(
                "id_method"=>$value->id_method,
                "info"=>$value->info
            ));
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_ORGANIZATION"]
        ]);

        return \Response::json($response, 200);
    }

    public function update(Request $request, $id){
        $editOrganization = Organization::where("id", "=", $id)->get()[0];
        $name = $request->input("data.name");
        $img = $request->input("data.img");

        if(strpos($img, "assets") === false && strpos($img, "default.jpg") === false){
            $name_img = rand_string(true).".jpeg";
            base64_to_img($img, SYSTEM_DIR_ORGANIZATION_IMGS.$name_img);
            $editOrganization->img = $name_img;
            //create thumbnails
            /**/
        }else if(strpos($img, "default.jpg") != false){
            $name_img = "default.jpg";
            $editOrganization->img = $name_img;
        }

        $editOrganization->name = $name;
        $lstatus = $request->input("data.status");

        if(gettype($lstatus) != "array"){
            $lstatus = array();
        }

        $available_for_use = false;
        $editOrganization->delete_Status();

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if(count($st)>0){
                $editOrganization->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $editOrganization->available_for_use = $available_for_use?'1':'0';
        $editOrganization->__update__();
        $coms = $request->input("data.media");

        if(gettype($coms) != "array"){
            $coms = array();
        }

        $editOrganization->delete_Media();

        foreach ($coms as $key => $value) {
            $editOrganization->create_Media([
                "id_media"=>$value["code"],
                "value"=>$value["value"]
            ]);
        }

        $ladres = $request->input("data.addresses");

        if(gettype($ladres) != "array"){
            $ladres = array();
        }

        $editOrganization->delete_Address();

        foreach ($ladres as $key => $value) {
            $editOrganization->create_Address([
                "address"=>$value
            ]);
        }

        $ids = $request->input("data.documentation");

        if(gettype($ids) != "array"){
            $ids = array();
        }

        $editOrganization->delete_Documentation();

        foreach ($ids as $key => $value) {
            $editOrganization->create_Documentation([
                "id_documentation"=>$value["code"],
                "value"=>$value["value"]
            ]);
        }

        $payment_methods = $request->input("data.payment_methods");

        if(gettype($payment_methods) != "array"){
            $payment_methods = array("banks"=>array(), "credit-cards"=>array(), "e-payments"=>array());
        }

        foreach ($payment_methods as $key => $value) {
            if(gettype($value) != "array"){
                $payment_methods[$key] = array();
            }
        }

        //banks
        $editOrganization->delete_PaymentMethodBank();
        if(isset($payment_methods["banks"])){
            foreach ($payment_methods["banks"] as $key => $method) {
                $editOrganization->create_PaymentMethodBank([
                    "id_method"=>$method["id_method"],
                    "info"=>$method["info"]
                ]);
            }
        }

        //electronicos
        $editOrganization->delete_PaymentMethodCreditCard();
        if(isset($payment_methods["credit-cards"])){
            foreach ($payment_methods["credit-cards"] as $key => $method) {
                $editOrganization->create_PaymentMethodCreditCard([
                    "id_method"=>$method["id_method"],
                    "info"=>$method["info"]
                ]);
            }
        }

        //tarjetas de credito
        $editOrganization->delete_PaymentMethodElectronic();
        if(isset($payment_methods["e-payments"])){
            foreach ($payment_methods["e-payments"] as $key => $method) {
                $editOrganization->create_PaymentMethodElectronic([
                    "id_method"=>$method["id_method"],
                    "info"=>$method["info"]
                ]);
            }
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_ORGANIZATION"]
        ]);

        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id){
        $item = Organization::where("id", "=", $id)->get()[0];
        $item->__delete__();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["DELETE_ORGANIZATION"]
        ]);
        return \Response::json(array(), 200);
    }
}
