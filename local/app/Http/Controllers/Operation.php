<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PanelAdminOperation;
use App\Models\PanelAdminOperationStatus;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Operation extends Controller{
    public function create(Request $request){
        $code = trim($request->input("data.code"));
        $name = sanitize(trim($request->input("data.name")));
        $description = sanitize(trim($request->input("data.description")));
        $new_item = new PanelAdminOperation;
        $new_item->name = generateMultilingual($name);
        $new_item->description = generateMultilingual($description);
        $new_item->code = $code; 
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

        $this->writeConstants();

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["CREATE_OPERATION"]
        ]);

        return \Response::json([
            'item' => array(
                "id"            =>  $new_item->id,
                "name"          =>  translate($new_item->name),
                "description"   =>  translate($new_item->description),
                "code" =>  $new_item->code,
                "status"        =>  $lstatus
            )
        ], 201);
    }

    public function index(Request $request){
        return $this->index_items($request, PanelAdminOperation::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_OPERATIONS");
    }

    public function search(Request $request){
        $keywords_search = $request->input("data.keywords_search");
        $base_items = PanelAdminOperation:: where("code", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("description", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                        ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_OPERATIONS");
    }

    public function read(Request $request, $id){
        $item = PanelAdminOperation::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_OPERATION"]
        ]);

        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "status"        =>$ls,
                "id"            =>$item->id,
                "code" =>$item->code
            )
        ], 200);
    }

    public function update(Request $request, $id){
        $name = $request->input("data.name");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $code = $request->input("data.code");
        $item = PanelAdminOperation::where("id", "=", $id)->get()[0];
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
        $this->writeConstants();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_OPERATION"]
        ]);
        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id){
        $item = PanelAdminOperation::where("id", "=", $id)->get()[0];
        $item->__delete__();
        $this->writeConstants();

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["DELETE_OPERATION"]
        ]);

        return \Response::json(array(), 200);
    }

    private function writeConstants(){
        $items = PanelAdminOperation::all();
        $f = fopen(base_path()."/app/constants_operations.php", "w");
        fwrite($f, "<?php\n\t\$GLOBALS[\"__OPERATION__\"] = [\n");
        foreach ($items as $key => $value) {
            fwrite($f, "\t\t'".$value->code."' => ".$value->id.",\n");
        }
        fwrite($f, "\t]\n?>");
        fclose($f);
    }
}
