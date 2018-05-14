<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterAdministrativeDivision;
use App\Models\MasterAdministrativeDivisionParent;
use App\Models\MasterStatus;
use App\Models\UserSession;

class AdministrativeDivision extends Controller
{
    public function create(Request $request)
    {
        $name = $request->input("data.name");
        $desc = $request->input("data.description");
        $lstatus = $request->input("data.status");
        $parents = $request->input("data.parents") ;

        $newitem = new MasterAdministrativeDivision;
        $newitem->name = generateMultilingual($name);
        $newitem->description = generateMultilingual($desc);
        $newitem->code = $request->input("data.code");
        $newitem->__create__();

        if (gettype($lstatus) != "array") {
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if (count($st)>0) {
                $newitem->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $newitem->available_for_use = $available_for_use?'1':'0';
        $newitem->save();

        if (gettype($parents) != "array") {
            $parents = array();
        }

        foreach ($parents as $key => $value) {
            $newitem->create_Parent([
                "id_parent"=>$value
            ]);
        }
        $this->writeConstants("MasterAdministrativeDivision", "administrative_divisions");
        operation("CREATE_ADMINISTRATIVE_DIVISION");

        return \Response::json([
            'item' => array(
                "id"            =>$newitem->id,
                "name"          =>translate($newitem->name),
                "description"   =>translate($newitem->description),
                "status"        =>$lstatus,
                "parents"       =>$parents,
                "code"          =>$request->input("data.code")
            )
        ], 201);
    }

    public function index(Request $request)
    {
        return $this->index_items($request, MasterAdministrativeDivision::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_ADMINISTRATIVE_DIVISIONS", ["status"=>true],
            function ($parms, &$item, &$i) {
                extract($parms);

                $list_parents = $model->read_Parent;
                $parents=array();

                foreach ($list_parents as $key => $value) {
                    array_push($parents, $value->id_parent);
                }

                $item["parents"] = $parents;
            }
        );
    }

    public function search(Request $request)
    {
        $keywords_search = $request->input("data.keywords_search");
        $base_items = MasterAdministrativeDivision:: where("description", "LIKE", "%".$keywords_search."%")
                                                    ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                                    ->orWhere("code", "LIKE", "%".$keywords_search."%")
                                                    ->get();

        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_ADMINISTRATIVE_DIVISIONS", ["status"=>true],
            function ($parms, &$item, &$i) {
                extract($parms);

                $list_parents = $model->read_Parent;
                $parents=array();

                foreach ($list_parents as $key => $value) {
                    array_push($parents, $value->id_parent);
                }

                $item["parents"] = $parents;
            }
        );
    }

    public function read(Request $request, $id)
    {
        $item = MasterAdministrativeDivision::where("id", "=", $id)->get()[0];
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

        operation("READ_ADMINISTRATIVE_DIVISION");

        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "status"        =>$ls,
                "id"            =>$item->id,
                "parents"       =>$lsp,
                "code"          =>$item->code
            )
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $name = $request->input("data.name");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $parents = $request->input("data.parents");
        $item = MasterAdministrativeDivision::where("id", "=", $id)->get()[0];
        $item->name =setFieldMultilingual($item->name, $name);
        $item->description = setFieldMultilingual($item->description, $description);
        $item->code = $request->input("data.code");

        if (gettype($status) != "array") {
            $status = array();
        }

        $item->delete_Status();
        $available_for_use = false;

        foreach ($status as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if (count($st)>0) {
                $item->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $item->available_for_use = $available_for_use?'1':'0';
        $item->__update__();

        //construimos arbol de dependencias entre divisiones administrativas ya existentes
        $things = MasterAdministrativeDivision::all();
        $dependencias = array();

        foreach ($things as $key => $value) {
            $dependencias[strval($value->id)] = $this->children($value->id);
        }

        if (gettype($parents) != "array") {
            $parents = array();
        }

        $item->delete_Parent();
        $parents_added = array();

        foreach ($parents as $key => $value) {
            //en caso de al que vaya a agregar como padre
            if (intval($value) != intval($id) && !$this->isDescendentRelation($dependencias, $id, $value)) {
                $item->create_Parent([
                    "id_parent"=>$value
                ]);
                array_push($parents_added, strval($value));
            }
        }

        $this->writeConstants("MasterAdministrativeDivision", "administrative_divisions");
        operation("UPDATE_ADMINISTRATIVE_DIVISION");

        return \Response::json(array("parents_added"=>$parents_added), 200);
    }

    public function delete(Request $request, $id)
    {
        $item = MasterAdministrativeDivision::where("id", "=", $id)->get()[0];
        $item->__delete__();
        $this->writeConstants("MasterAdministrativeDivision", "administrative_divisions");
        operation("DELETE_ADMINISTRATIVE_DIVISION");

        return \Response::json(array(), 200);
    }

    private function children($idparent)
    {
        $roots = MasterAdministrativeDivisionParent::where("id_parent", "=", $idparent)->get();
        $children = array();

        foreach ($roots as $key => $value) {
            array_push($children, strval($value->id_item));
        }

        return $children;
    }

    private function isDescendentRelation($dependencias, $parent, $child)
    {
        $parents = array();
        $n = 0;
        $parent = strval($parent);
        $child = strval($child);

        do {
            foreach ($dependencias as $key => $value) {
                if (in_array($child, $value)) {
                    if ($parent == $key) {
                        return true;
                    }

                    $n++;
                    array_push($parents, $key);
                }
            }

            if ($n > 0) {
                $child = $parents[array_keys($parents)[0]];
                unset($parents[array_keys($parents)[0]]);
            }
        } while ($n-- > 0);

        return false;
    }
}
