<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PanelAdminAction;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Action extends Controller{
    public function create(Request $request){
        //throw error if data is not valid
        $this->validate($request, array(
            "data.code"         =>'required|min:1',
            "data.name"         =>'required|min:1'
        ));

        $cond = true;
        $code = trim($request->input("data.code"));
        $cond = $cond && strlen($code) >= 1;
        $subcode = substr($code, 0);
        $n = strlen($subcode);
        $alph = 'abcdefghijklmnopqrstuvwxyz_0123456789';

        for($i = 0; $i < $n && $cond; $i++){
            $cond = $cond && gettype(strpos($alph, strtolower($subcode[$i]))) != "boolean";
        }

        $name = sanitize(trim($request->input("data.name")));
        $cond = $cond && strlen($name);
        $description = sanitize(trim($request->input("data.description")));

        if(!$cond){
            return \Response::json(array(), 400);
        }

        if(PanelAdminAction::where('code', '=', $code)->count() > 0){
            return \Response::json(array(), 409);
        }

        $new_action = new PanelAdminAction;
        $new_action->name = generateMultilingual($name);
        $new_action->description = generateMultilingual($description);
        $new_action->code = $code; 
        $new_action->__create__();

        $lstatus = $request->input("data.status");

        if(gettype($lstatus) != "array"){
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if(count($st)>0){
                $new_action->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $new_action->available_for_use = $available_for_use?'1':'0';
        $new_action->save();

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["CREATE_ACTION"]
        ]);
        $this->writeConstants();

        return \Response::json([
            'item' => array(
                "id"            =>  $new_action->id,
                "name"          =>  translate($new_action->name),
                "description"   =>  translate($new_action->description),
                "code"          =>  $new_action->code,
                "status"      =>  $lstatus
            )
        ], 201);
    }

    public function index(Request $request){
        return $this->index_items($request, PanelAdminAction::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_ACTIONS");
    }

    public function search(Request $request){
        $keywords_search = $request->input("data.keywords_search");
        $base_items = PanelAdminAction:: where("code", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("description", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                        ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_ACTIONS");
    }

    public function read(Request $request, $id){
        $item = PanelAdminAction::where("id", "=", $id)->get();

        if(count($item) > 0)
            $item = $item[0];
        else
            return \Response::json(array(), 404);

        $status = $item->read_Status;
        $ls=array();
        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["READ_ACTION"]
        ]);

        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "status"      =>$ls,
                "id"            =>$item->id,
                "code"          =>$item->code
            )
        ], 200);
    }

    public function update(Request $request, $id){
        $name = $request->input("data.name");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $code = $request->input("data.code");

        /******************************************************/
        //validacion
        if( gettype($name) != "string" ||
            gettype($description) != "string" ||
            gettype($code) != "string")
            return \Response::json(array(), 400);
        $name = sanitize(trim($name));
        $description = sanitize(trim($description));
        $code = sanitize(trim($code));
        if(strlen($name) == 0 || strlen($code) == 0 || strlen($code) > 32)
            return \Response::json(array(), 400);
        /******************************************************/

        $item = PanelAdminAction::where("id", "=", $id)->get()[0];
        $item->name = setFieldMultilingual($item->name, $name);
        $item->description = setFieldMultilingual($item->description, $description);
        $item->code = $code;



        if(gettype($status) != "array")
            $status = array();

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
            "operation" => $GLOBALS["__OPERATION__"]["UPDATE_ACTION"]
        ]);

        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id){
        $item = PanelAdminAction::where("id", "=", $id)->get()[0];
        $item->__delete__();
        $this->writeConstants();
        __ACTIVITY__([
            "operation" => $GLOBALS["__OPERATION__"]["DELETE_ACTION"]
        ]);
        return \Response::json(array(), 200);
    }

    private function writeConstants(){
        $items = PanelAdminAction::all();
        $f = fopen(base_path()."/app/constants_actions.php", "w");
        fwrite($f, "<?php\n\t\$GLOBALS[\"__ACTION__\"] = [\n");
        foreach ($items as $key => $value) {
            fwrite($f, "\t\t'".$value->code."' => ".$value->id.",\n");
        }
        fwrite($f, "\t]\n?>");
        fclose($f);
    }
}
