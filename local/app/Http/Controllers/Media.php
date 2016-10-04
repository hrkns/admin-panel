<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MasterMedia;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Media extends Controller
{
    public function create(Request $request)
    {
        $name = $request->input("data.name");
        $desc = $request->input("data.description");
        $lstatus = $request->input("data.status");
        $newitem = new MasterMedia;
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
        $this->writeConstants("MasterMedia", "media");
        operation("CREATE_MEDIA");
        return \Response::json([
            'item' => array(
                "id"            =>$newitem->id,
                "name"          =>translate($newitem->name),
                "description"   =>translate($newitem->description),
                "status"        =>$lstatus,
                "code"          =>$newitem->code
            )
        ], 201);
    }

    public function index(Request $request)
    {
        return $this->index_items($request, MasterMedia::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_MEDIAS");
    }

    public function search(Request $request)
    {
        $keywords_search = $request->input("data.keywords_search");
        $base_items = MasterMedia:: where("code", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("description", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                        ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_MEDIAS");
    }

    public function read(Request $request, $id)
    {
        $item = MasterMedia::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        operation("READ_MEDIA");
        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "status"        =>$ls,
                "id"            =>$item->id,
                "code"          =>$item->code
            )
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $name = $request->input("data.name");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $item = MasterMedia::where("id", "=", $id)->get()[0];
        $item->name = setFieldMultilingual($item->name, $name);
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
        $this->writeConstants("MasterMedia", "media");
        operation("UPDATE_MEDIA");
        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id)
    {
        $item = MasterMedia::where("id", "=", $id)->get()[0];
        $item->__delete__();
        $this->writeConstants("MasterMedia", "media");
        operation("DELETE_MEDIA");
        return \Response::json(array(), 200);
    }
}
