<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PanelAdminRole;
use App\Models\PanelAdminRoleSection;
use App\Models\PanelAdminRoleSectionAction;
use App\Models\PanelAdminRoleStatus;
use App\Models\PanelAdminSection;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Role extends Controller
{
    public function create(Request $request)
    {
        $name = $request->input("data.name");
        $description = $request->input("data.description");
        $new_role = new PanelAdminRole;

        $new_role->name = generateMultilingual($name);
        $new_role->description = generateMultilingual($description);
        $new_role->code = $request->input("data.code");
        $new_role->__create__();
        $accesos = $request->input("data.permises");

        foreach ($accesos as $key => $value) {
            if (array_key_exists("actions", $value)) {
                $new_role_section = $new_role->create_Section([
                    "id_section" => $value["section"]
                ]);

                foreach ($value["actions"] as $k => $idaction) {
                    $new_role_section->create_Action([
                        "id_action" => $idaction
                    ]);
                }
            }
        }

        $lstatus = $request->input("data.status");

        if (gettype($lstatus) != "array") {
            $lstatus = array();
        }

        foreach ($lstatus as $key => $value) {
            $new_role->create_Status([
                "id_status" => $value
            ]);
        }
        $this->writeConstants("PanelAdminRole", "roles");
        operation("CREATE_ROLE");

        return \Response::json([
            'item' => array(
                "id"=>$new_role->id,
                "name"=>translate($new_role->name),
                "description"=>translate($new_role->description),
                "status"=>$lstatus,
                "code"=>$request->input("data.code")
            )
        ], 201);
    }

    public function index(Request $request)
    {
        return $this->index_items($request, PanelAdminRole::all(), [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "READ_ROLES");
    }

    public function search(Request $request)
    {
        $keywords_search = $request->input("data.keywords_search");
        $base_items = PanelAdminRole:: where("code", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("description", "LIKE", "%".$keywords_search."%")
                                        ->orWhere("name", "LIKE", "%".$keywords_search."%")
                                        ->get();
        return $this->search_items($request, $base_items, [
            "code" => [],
            "description" => ["translate"=>true],
            "name" => ["translate"=>true]
        ], "SEARCH_ROLES");
    }

    public function read(Request $request, $id)
    {
        $item = PanelAdminRole::where("id", "=", $id)->get()[0];
        $status = $item->read_Status;
        $ls=array();

        foreach ($status as $key => $value) {
            array_push($ls, $value->id_status);
        }

        operation("READ_ROLE");

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
        $item = PanelAdminRole::where("id", "=", $id)->get()[0];
        $item->name = setFieldMultilingual($item->name, $name);
        $item->description = setFieldMultilingual($item->description, $description);
        $item->code = $request->input("data.code");
        $item->__update__();

        if (gettype($status) != "array") {
            $status = array();
        }

        $item->delete_Status();

        foreach ($status as $key => $value) {
            $item->create_Status([
                "id_status"=>$value
            ]);
        }

        $this->writeConstants("PanelAdminRole", "roles");
        operation("UPDATE_ROLE");

        return \Response::json(array("item"=>array()), 204);
    }

    public function delete(Request $request, $id)
    {
        $item = PanelAdminRole::where("id", "=", $id)->get()[0];
        $item->__delete__();
        $this->writeConstants("PanelAdminRole", "roles");
        operation("DELETE_ROLE");
        return \Response::json(array(), 200);
    }

    public function permises(Request $request, $id)
    {
        $sections = PanelAdminSection::all();
        $permises = array();

        foreach ($sections as $key => $value) {
            $vl = array("section"=>$value->id, "actions"=>array());
            $role_section = PanelAdminRoleSection::where("id_panel_admin_role", "=", $id)
                                               ->  where("id_section", "=", $value->id)->get();
            if (count($role_section)>0) {
                $role_section = $role_section[0];
                $ractions = PanelAdminRoleSectionAction::where("id_panel_admin_role_section", "=", $role_section->id)->get();

                foreach ($ractions as $key => $rac) {
                    array_push($vl["actions"], $rac->id_action);
                }
            }

            array_push($permises, $vl);
        }

        operation("READ_ROLE_PERMISES");
        return \Response::json(array("items"=>$permises), 200);
    }

    public function updatePermises(Request $request, $id)
    {
        $sections_covered_by_role = PanelAdminRoleSection::where("id_panel_admin_role", "=", $id)->get();
        $actions_on_section = array();
        $sections_on_role = array();

        foreach ($sections_covered_by_role as $key => $role_on_section) {
            $sections_on_role[strval($role_on_section->id_section)] = $role_on_section;
            $id_section = $role_on_section->id_section;
            $actions = PanelAdminRoleSectionAction::where("id_panel_admin_role_section", "=", $role_on_section->id)->get();

            foreach ($actions as $k => $action_on_section) {
                $actions_on_section[strval($id_section)."-".strval($action_on_section->id_action)] = $action_on_section;
            }
        }

        $accesos = $request->input("data.permises");

        foreach ($accesos as $key => $value) {
            if (array_key_exists("actions", $value)) {
                if (!array_key_exists(strval($value["section"]), $sections_on_role)) {
                    $new_role_section = new PanelAdminRoleSection;
                    $new_role_section->id_panel_admin_role = $id;
                    $new_role_section->id_section = $value["section"];
                    $new_role_section->__create__();
                } else {
                    $new_role_section = $sections_on_role[strval($value["section"])];
                    unset($sections_on_role[strval($value["section"])]);
                }

                foreach ($value["actions"] as $k => $idaction) {
                    if (!array_key_exists(strval($value["section"])."-".strval($idaction), $actions_on_section)) {
                        $permiso = new PanelAdminRoleSectionAction;
                        $permiso->id_action = $idaction;
                        $permiso->id_panel_admin_role_section = $new_role_section->id;
                        $permiso->__create__();
                    } else {
                        unset($actions_on_section[strval($value["section"])."-".strval($idaction)]);
                    }
                }
            }
        }

        foreach ($actions_on_section as $key => $value) {
            $value->__delete__();
        }

        foreach ($sections_on_role as $key => $value) {
            $value->__delete__();
        }

        operation(""UPDATE_ROLE_PERMISES);
        return \Response::json(array(), 204);
    }
}
