<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PanelAdminSound;
use App\Models\PanelAdminSoundStatus;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Sound extends Controller
{
    public function create(Request $request)
    {
        $code = trim($request->input("data.code"));
        $name = sanitize(trim($request->input("data.name")));
        $description = sanitize(trim($request->input("data.description")));
        $new_item = new PanelAdminSound;
        $new_item->name = generateMultilingual($name);
        $new_item->description = generateMultilingual($description);
        $new_item->code = $code;
        $new_item->__create__();
        $lstatus = $request->input("data.status");

        if (gettype($lstatus) != "array") {
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();
            if (count($st)>0) {
                $new_item->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        $new_item->available_for_use = $available_for_use?'1':'0';
        $new_item->save();
        $this->writeConstants("PanelAdminSound", "sounds");

        operation("CREATE_SOUND");

        return \Response::json([
            'item' => array(
                "id"            =>  $new_item->id,
                "name"          =>  translate($new_item->name),
                "description"   =>  translate($new_item->description),
                "code"          =>  $new_item->code,
                "status"        =>  $lstatus
            )
        ], 201);
    }

    public function index(Request $request)
    {
        return $this->index_items($request, PanelAdminSound::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true],
            "file" => []
        ], "READ_SOUNDS");
    }

    public function search(Request $request)
    {
        $keywords_search = $request->input("data.keywords_search");
        $base_items = PanelAdminSound:: where("code", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("description", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                        ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true],
            "file" => []
        ], "SEARCH_SOUNDS");
    }

    public function read(Request $request, $id)
    {
        $item = PanelAdminSound::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        operation("READ_SOUND");

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

    public function update(Request $request, $id)
    {
        $name = $request->input("data.name");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $code = $request->input("data.code");
        $item = PanelAdminSound::where("id", "=", $id)->get()[0];
        $item->name = setFieldMultilingual($item->name, $name);
        $item->description = setFieldMultilingual($item->description, $description);
        $item->code = $code;

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
        $this->writeConstants("PanelAdminSound", "sounds");

        operation("UPDATE_SOUND");

        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id)
    {
        $item = PanelAdminSound::where("id", "=", $id)->get()[0];
        $item->__delete__();

        operation("DELETE_SOUND");

        return \Response::json(array(), 200);
    }

    public function updateFile(Request $request, $id)
    {
        $file = $request->file("audio");
        $namefile = rand_string().".mp3";//.$file->getClientOriginalExtension();
        $file->move(SYSTEM_AUDIO_NOTIFICATIONS_FOLDER, $namefile);

        $sound = PanelAdminSound::where("id", "=", $id)->get()[0];
        $sound->file = $namefile;

        if ($request->has("updating")) {
            $sound->__update__();
        } else {
            $sound->save();
        }

        $this->writeConstants("PanelAdminSound", "sounds");

        operation("UPDATE_SOUND_FILE");

        return \Response::json(array(
            "filename"  =>  $namefile
        ), 200);
    }
}
