<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\MasterLanguage;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Language extends Controller
{
    public function create(Request $request)
    {
        $name = $request->input("data.name");
        $code = $request->input("data.code");
        $description = $request->input("data.description");
        $new_language = new MasterLanguage;
        $new_language->name = generateMultilingual($name);
        $new_language->description = generateMultilingual($description);
        $new_language->code = $code;
        $new_language->__create__();
        $lstatus = $request->input("data.status");

        if (gettype($lstatus) != "array") {
            $lstatus = array();
        }

        $available_for_use = false;

        foreach ($lstatus as $key => $value) {
            $st = MasterStatus::where("id", "=", $value)->get();

            if (count($st)>0) {
                $new_language->create_Status([
                    "id_status"=>$value
                ]);
                $available_for_use = $available_for_use || (intval($st[0]->show_item) == 1);
            }
        }

        require FILE_ADMIN_PANEL_SETTINGS;
        $globalPreferences["terms_of_use_and_privacy_policy"][$new_language->code] = "";
        file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');

        $new_language->available_for_use = $available_for_use?'1':'0';
        $new_language->save();
        $this->writeConstants("MasterLanguage", "languages");
        operation("CREATE_LANGUAGE");
        return \Response::json(array(
            "item"  => array(
                "id"            =>  $new_language->id,
                "name"          =>  $name,
                "code"          =>  $code,
                "description"   =>  $description,
                "status"        =>  $lstatus
            )
        ), 201);
    }

    public function index(Request $request)
    {
        return $this->index_items($request, MasterLanguage::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_LANGUAGES");
    }

    public function search(Request $request)
    {
        $keywords_search = $request->input("data.keywords_search");
        $base_items = MasterLanguage:: where("code", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("description", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                        ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_LANGUAGES");
    }

    public function read(Request $request, $id)
    {
        $item = MasterLanguage::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();
        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }
        operation("READ_LANGUAGE");
        return \Response::json([
            'item' => array(
                "name"          =>translate($item->name),
                "description"   =>translate($item->description),
                "status"        =>$ls,
                "id"            =>$item->id
            )
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $name = $request->input("data.name");
        $code = $request->input("data.code");
        $description = $request->input("data.description");
        $status = $request->input("data.status");
        $item = MasterLanguage::where("id", "=", $id)->get()[0];
        $item->name = setFieldMultilingual($item->name, $name);
        $item->description = setFieldMultilingual($item->description, $description);
        $old_code = $item->code;
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


        require FILE_ADMIN_PANEL_SETTINGS;
        $globalPreferences["terms_of_use_and_privacy_policy"][$item->code] = $globalPreferences["terms_of_use_and_privacy_policy"][$old_code];
        file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
        $this->writeConstants("MasterLanguage", "languages");
        operation("UPDATE_LANGUAGE");
        return \Response::json(array(), 204);
    }

    public function delete(Request $request, $id)
    {
        $item = MasterLanguage::where("id", "=", $id)->get()[0];
        $item->__delete__();
        $this->writeConstants("MasterLanguage", "languages");
        operation("DELETE_LANGUAGE");
        return \Response::json(array(), 200);
    }

    public function updateLanguageSession(Request $request)
    {
        $codelng = $request->input("data.lng");

        if (!$request->session()->has("iduser")) {
            $request->session()->put("lng", $codelng);
            return \Response::json(array("session" => "finished"), 200);
        }

        $user = User::where("id", "=", $request->session()->get("iduser"))->get()[0];
        $user->default_language_session = $codelng;
        $user->__update__();
        $request->session()->put("lng", $codelng);
        operation("UPDATE_USER_LANGUAGE");
        return \Response::json(array(), 204);
    }
}
