<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterStatus;
use App\Models\UserPreferences;
use App\Models\UserSession;

class Status extends Controller{
    public function create(Request $request){
        $name = $request->input("data.name");
        $desc = $request->input("data.description");
        $code = $request->input("data.code");
        $show_default = $request->input("data.show_default");;
        $show_item = $request->input("data.show_item");;
        $lstatus = $request->input("data.status");
        $for_delete = $request->input("data.for_delete");;

        $newitem = new MasterStatus;
        $newitem->name = generateMultilingual($name);
        $newitem->description = generateMultilingual($desc);
        $newitem->code = $code;
        $newitem->show_default = $show_default;
        $newitem->show_item = $show_item;
        $newitem->for_delete = $for_delete;
        $newitem->__create__();

        if(gettype($lstatus) != "array")
            $lstatus = array();

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
        $this->writeConstants();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["CREATE_STATUS"]
        ]);

        return \Response::json([
            'item' => array(
                "id"            =>$newitem->id,
                "name"          =>translate($newitem->name),
                "description"   =>translate($newitem->description),
                "code"          =>$code,
                "status"        =>$lstatus,
                "show_default"  =>$show_default,
                "show_item"     =>$show_item,
                "for_delete"    =>$for_delete,
            )
        ], 201);
    }

    public function index(Request $request){
        return $this->index_items($request, MasterStatus::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true],
            "for_delete" => [],
            "show_default" => [],
            "show_item" => [],
        ], "READ_STATUSES");
    }

    public function search(Request $request){
        $keywords_search = $request->input("data.keywords_search");
        $base_items = MasterStatus:: where("code", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("description", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                        ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true],
            "for_delete" => [],
            "show_default" => [],
            "show_item" => [],
        ], "SEARCH_STATUSES");
    }

    public function read(Request $request, $id){
        $item = MasterStatus::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_STATUS"]
        ]);

        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "code"          =>$item->code,
                "status"        =>$ls,
                "show_default"  =>$item->show_default,
                "show_item"     =>$item->show_item,
                "id"            =>$item->id,
                "for_delete"    =>$item->for_delete
            )
        ], 200);
    }

    public function update(Request $request, $id){
        $name = $request->input("data.name");
        $code = $request->input("data.code");
        $description = $request->input("data.description");
        $show_default = $request->input("data.show_default");
        $show_item = $request->input("data.show_item");
        $status = $request->input("data.status");
        $for_delete = $request->input("data.for_delete");
        $item = MasterStatus::where("id", "=", $id)->get()[0];
        $item->name = setFieldMultilingual($item->name, $name);
        $item->description = setFieldMultilingual($item->description, $description);
        $item->code = $code;
        $item->for_delete= $for_delete;
        $item->show_default = $show_default;
        $item->show_item= $show_item;

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
        $this->writeConstants();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_STATUS"]
        ]);
        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id){
        $item = MasterStatus::where("id", "=", $id)->get()[0];
        $item->__delete__();
        $this->writeConstants();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["DELETE_STATUS"]
        ]);
        return \Response::json(array(), 200);
    }

    private function writeConstants(){
        $items = MasterStatus::all();
        $f = fopen(base_path()."/app/constants_statuses.php", "w");
        fwrite($f, "<?php\n\t\$GLOBALS[\"__STATUS__\"] = [\n");
        foreach ($items as $key => $value) {
            fwrite($f, "\t\t'".$value->code."' => ".$value->id.",\n");
        }
        fwrite($f, "\t]\n?>");
        fclose($f);
    }
}
