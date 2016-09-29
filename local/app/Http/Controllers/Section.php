<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\PanelAdminSection;
use App\Models\PanelAdminSectionTerm;
use App\Models\PanelAdminSectionTermStatus;
use App\Models\PanelAdminAction;
use App\Models\PanelAdminRoleSectionAction;
use App\Models\PanelAdminRoleSection;
use App\Models\PanelAdminRole;
use App\Models\MasterStatus;
use App\Models\UserRole;
use App\Models\UserSession;
use App\Models\UserSectionAmountTimesVisited;
use App\Models\MasterLanguage;

class Section extends Controller{
    /*SECTION*/
        public function show($idsection, Request $request){
            $elemento = PanelAdminSection::where("id", "=", $idsection)->get();
            $tkn = rand_string();
            $array_tokens = $request->session()->get(PROGRESSIVE_REQUEST_TOKENS);
            $array_tokens[$tkn] = array();
            $request->session()->put(PROGRESSIVE_REQUEST_TOKENS, $array_tokens);
            $tmp = GetForUse("MasterStatus");
            $list_status = array();

            foreach ($tmp as $key => $value) {
                array_push($list_status, array(
                    "id"            => $value->id,
                    "name"          => $value->name,
                    "code"          => $value->code,
                    "value"         => $value->value,
                    "description"   => $value->description,
                    "show_default"  => $value->show_default,
                    "for_delete"    => $value->for_delete
                ));
            }

            $all_statuses = array();
            $list_statuses_emergency = array();
            $tmp = MasterStatus::all();
            foreach ($tmp as $key => $value) {
                array_push($all_statuses, $value->id);
                array_push($list_statuses_emergency, array(
                    "id"            => $value->id,
                    "name"          => $value->name,
                    "code"          => $value->code,
                    "value"         => $value->value,
                    "description"   => $value->description,
                    "show_default"  => $value->show_default,
                    "for_delete"    => $value->for_delete
                ));
            }

            $terms = terms($idsection);
            $GLOBALS["section_terms"] = $terms;

            /********************************************************************/
            $roles = UserRole::where("id_user", "=", $request->session()->get("iduser"))->get();
            $dbactions = array();
            $tmp = GetForUse("PanelAdminAction");
            $boolean_actions = array();

            foreach ($tmp as $key => $value) {
                $dbactions[strval($value->id)] = $value;
                $boolean_actions[strval($value->code)] = false;
            }

            //evaluo cada uno de los roles que el usuario posee en cada organizacion (uno por organizacion)
            foreach ($roles as $key => $value){
                $role_sections = PanelAdminRoleSection::where("id_panel_admin_role", "=", $value->id_role)
                                                      ->where("id_section", "=", $idsection)->get();

                foreach ($role_sections as $key => $role_section) {
                    $actions = PanelAdminRoleSectionAction::where("id_panel_admin_role_section", "=", $role_section->id)->get();

                    foreach ($actions as $kkk => $vv) {
                        $boolean_actions[strval($dbactions[$vv->id_action]->code)] = true;
                    }
                }
            }
            /********************************************************************/
            if(!$boolean_actions["read"]){
                return \Response::json([], 401);
            }
            /********************************************************************/

            $times = UserSectionAmountTimesVisited::where("id_section", "=", $idsection)->where("id_user", "=", $request->session()->get("iduser"))->get();

            if(count($times)>0){
                $times = $times[0];
                $times->amount_times_visited = intval($times->amount_times_visited)+1;
                $times->moment = sqldate();
                $times->__update__();
            }else{
                $times = new UserSectionAmountTimesVisited;
                $times->id_user = $request->session()->get("iduser");
                $times->id_section = $idsection;
                $times->moment = sqldate();
                $times->__create__();
            }

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["GET_VIEW"]
            ]);

            //$boolean_actions["language_controls"] = false;
            $emergency = false;

            return \Response::json([
                'view' => strval(view(  "app.sections.".$elemento[0]->route_name, 
                                        array(  "list_status"=>$emergency?$list_statuses_emergency:$list_status, 
                                                "terms"=>$terms,
                                                "idsection"=>$idsection,
                                                "section_code" => $elemento[0]->route_name,
                                                "role_actions" => $boolean_actions
                ))->render()),
                "section_name" => translate(json_decode($elemento[0]->name)),
                "section_config" => [
                    "statuses" => [
                        "multiple" => $emergency?1:$elemento[0]->multiple_statuses,
                        "permitted" => $emergency?$all_statuses:json_decode($elemento[0]->permitted_statuses),
                        "default" => $emergency?$all_statuses:json_decode($elemento[0]->statuses_by_default),
                        "use" => $emergency?1:$elemento[0]->use_statuses,
                    ],
                    "token" => $tkn,
                    "role_actions" => $boolean_actions
                ]
            ], 200);
        }

        private function updateMenu($parent, $children, $session, $tknsLanguages, $lng, &$dbSections, &$tmp_files, &$urls_for_routes, $prefix = ""){
            foreach ($children as $key => $value) {
                $cond = array_key_exists(strval($value["id"]), $dbSections);

                if($cond){
                    $sec = $dbSections[strval($value["id"])];
                    unset($dbSections[strval($value["id"])]);
                    $tknsLanguages = json_decode($sec["name"]);
                    $tknsLanguages->$lng = $value["text"];
                }else{
                    $sec = new PanelAdminSection;
                    foreach ($tknsLanguages as $key => $v) {$tknsLanguages->$key = $value["text"];}
                }

                $sec->name = json_encode($tknsLanguages);

                if($sec->route_name != $value["route"]){
                    if(is_file(PROJECT_VIEWS_ROOT."app/sections/".$sec->route_name.".blade.php")){
                        $content = file_get_contents(PROJECT_VIEWS_ROOT."app/sections/".$sec->route_name.".blade.php");
                        unlink(PROJECT_VIEWS_ROOT."app/sections/".$sec->route_name.".blade.php");
                        file_put_contents(PROJECT_VIEWS_ROOT."app/sections/__".$value["route"]."__.blade.php", $content);

                        $content = file_get_contents(JS_SECTIONS_FOLDER.$sec->route_name.".js");
                        unlink(JS_SECTIONS_FOLDER.$sec->route_name.".js");
                        file_put_contents(JS_SECTIONS_FOLDER."__".$value["route"]."__.js", $content);

                        array_push($tmp_files, $value["route"]);
                    }else{
                        file_put_contents(PROJECT_VIEWS_ROOT."app/sections/".$value["route"].".blade.php", "");
                        file_put_contents(JS_SECTIONS_FOLDER.$value["route"].".js", "function module(){\n}");
                    }
                }

                $sec->route_name = $value["route"];
                $sec->icon = $value["icon"];
                $sec->id_parent = $parent;
                $sec->position = $value["pos"];
                $sec->last_activity_by = $session->get("iduser");

                if($cond){
                    $sec->__update__();
                }else{
                    $sec->__create__();
                    file_put_contents(SYSTEM_TERMS_FOLDER."section-".$sec->id."-terms.json", "{}");
                }

                array_push($urls_for_routes, $prefix.$sec->route_name);

                if(array_key_exists("children", $value)){
                    $this->updateMenu($sec->id, $value["children"], $session, $tknsLanguages, $lng, $dbSections, $tmp_files, $urls_for_routes, $prefix.$sec->route_name."/");
                }
            }
        }

        public function update_menu(Request $request){
            $varr = PanelAdminSection::all();
            $dbSections = array();

            foreach ($varr as $key => $value) {
                $dbSections[strval($value->id)] = $value;
            }

            $lng = __LNG__;
            $urls_for_routes = array();
            $tknsLanguages = arrayLanguagesKeys(dbLanguages());
            $tmp_files = array();
            $this->updateMenu(null, $request->input("data")["children"], $request->session(), $tknsLanguages, $lng, $dbSections, $tmp_files, $urls_for_routes);
            file_put_contents(FILE_URL_ROUTES, '<?php $sections_url = ' . var_export($urls_for_routes, true) . ';?>');

            foreach ($dbSections as $key => $value) {
                copy(PROJECT_VIEWS_ROOT."app/sections/".$value->route_name.".blade.php", PROJECT_VIEWS_ROOT."app/sections/__DELETED__".$value->route_name.".blade.php");
                unlink(PROJECT_VIEWS_ROOT."app/sections/".$value->route_name.".blade.php");

                copy(JS_SECTIONS_FOLDER.$value->route_name.".js", JS_SECTIONS_FOLDER."__DELETED__".$value->route_name.".js");
                unlink(JS_SECTIONS_FOLDER.$value->route_name.".js");

                $value->__delete__();
            }

            foreach ($tmp_files as $key => $value) {
                copy(PROJECT_VIEWS_ROOT."app/sections/__".$value."__.blade.php", PROJECT_VIEWS_ROOT."app/sections/".$value.".blade.php");
                unlink(PROJECT_VIEWS_ROOT."app/sections/__".$value."__.blade.php");

                copy(JS_SECTIONS_FOLDER."__".$value."__.js", JS_SECTIONS_FOLDER.$value.".js");
                unlink(JS_SECTIONS_FOLDER."__".$value."__.js");
            }

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_SECTIONS"]
            ]);

            return \Response::json([
            ], 204);
        }

    /*DICTIONARY*/
        public function getTerms($id, Request $request){
            $id = strtolower($id) == "general"?null:$id;
            $ls = PanelAdminSectionTerm::where("id_panel_admin_section", "=", $id)->get();
            $items = array();

            foreach ($ls as $key => $value) {
                $status = $value->read_Status;
                $lx=array();

                foreach ($status as $key => $vv) {
                    array_push($lx, $vv->id_status);
                }

                array_push($items, array(
                    "id"            =>  $value->id,
                    "name"          =>  translate($value->name),
                    "code"          =>  $value->code,
                    "description"   =>  translate($value->description),
                    "value"         =>  translate($value->value),
                    "status"        =>  $lx
                ));
            }

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["READ_TERMS"]
            ]);

            return \Response::json([
                "items" =>  $items
            ], 200);
        }

        public function createTerm(Request $request){
            $code = $request->input("data.code");
            $description = $request->input("data.description");
            $name = $request->input("data.name");
            $value = $request->input("data.value");
            $idsection = $request->input("data.idsection");
            $idsection = strtolower($idsection) == "general"?null:$idsection;
            $newTerm = new PanelAdminSectionTerm;
            $newTerm->code = $code;
            $newTerm->id_panel_admin_section = $idsection;
            $newTerm->description = generateMultilingual($description);
            $newTerm->name = generateMultilingual($name);
            $newTerm->value = generateMultilingual($value);
            $newTerm->__create__();
            $lstatus = $request->input("data.status");

            if(gettype($lstatus) != "array"){
                $lstatus = array();
            }

            foreach ($lstatus as $key => $value) {
                $newTerm->create_Status([
                    "id_status"=>$value
                ]);
            }


            $terms = PanelAdminSectionTerm::where("id_panel_admin_section", "=", $idsection)->get();
            $f = fopen(SYSTEM_TERMS_FOLDER."section-".$idsection."-terms.json", "w");
            foreach ($terms as $key => $value) {
                $tmp = $value;
                unset($terms[$key]);
                $terms[$value->code] = $value;
            }
            fwrite($f, json_encode($terms));
            fclose($f);
            /*****/

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["CREATE_TERM"]
            ]);

            return \Response::json([
                'item' => array(
                    "id"            =>  $newTerm->id,
                    "name"          =>  translate($newTerm->name),
                    "description"   =>  translate($newTerm->description),
                    "code"          =>  $newTerm->code,
                    "value"         =>  translate($newTerm->value),
                    "status"        =>  $lstatus
                )
            ], 200);
        }

        public function setTerm(Request $request, $id){
            $name = $request->input("data.name");
            $description = $request->input("data.description");
            $status = $request->input("data.status");
            $code = $request->input("data.code");
            $vale = $request->input("data.value");
            $id = strtolower($id) == "general"?null:$id;
            $item = PanelAdminSectionTerm::where("id", "=", $id)->get()[0];
            $item->name = setFieldMultilingual($item->name, $name);
            $item->description = setFieldMultilingual($item->description, $description);
            $item->value = setFieldMultilingual($item->value, $vale);
            $item->code = $code;
            $item->__update__();
            $idsection = $item->id_panel_admin_section;

            $terms = PanelAdminSectionTerm::where("id_panel_admin_section", "=", $idsection)->get();
            $f = fopen(SYSTEM_TERMS_FOLDER."section-".$idsection."-terms.json", "w");

            foreach ($terms as $key => $value) {
                $tmp = $value;
                unset($terms[$key]);
                $terms[$value->code] = $value;
            }

            fwrite($f, json_encode($terms));
            fclose($f);
            /*****/

            if(gettype($status) != "array"){
                $status = array();
            }

            $item->delete_Status();

            foreach ($status as $key => $value) {
                $item->create_Status([
                    "id_status"=>$value
                ]);
            }

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_TERM"]
            ]);

            return \Response::json(array(
                /*
                "terms"=>$terms, 
                "file"=>"assets/js/__languages__/section-".$idsection."-terms.json"
                */
            ), 200);
        }

        public function getTerm(Request $request, $id){
            $id = strtolower($id) == "general"?null:$id;
            $item = PanelAdminSectionTerm::where("id", "=", $id)->get()[0];
            $status = $item->read_Status;
            $ls=array();

            foreach ($status as $key => $value) {
                array_push($ls, $value->id_status);
            }

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["READ_TERM"]
            ]);

            return \Response::json([
                'item' => array(
                    "name"          =>translate($item->name),
                    "description"   =>translate($item->description),
                    "status"        =>$ls,
                    "id"            =>$item->id,
                    "value"         =>translate($item->value),
                    "code"          =>$item->code
                )
            ], 200);
        }

        private function cloneTermsFromSection($idorigin, $iddestiny, $option, &$existente, $request){
            $terms = PanelAdminSectionTerm::where("id_panel_admin_section", "=", $idorigin)->get();

            foreach ($terms as $key => $term) {
                $cond = true;

                switch($option){
                    case "ignore":{
                        $cond = !array_key_exists($term->code, $existente);
                    }
                    break;
                    case "overwrite":{
                        $v = array_key_exists($term->code, $existente);

                        if($v){
                            $item = $existente[$term->code];
                        }
                        else{
                            $item = new PanelAdminSectionTerm;
                            $item->id_panel_admin_section = $iddestiny;
                            $item->code = $term->code;
                        }

                        $item->name = $term->name;
                        $item->description = $term->description;
                        $item->value = $term->value;

                        if($v){
                            $item->__update__();
                        }else{
                            $item->__create__();
                        }

                        if(!$v){
                            $existente[$term->code] = $item;
                        }

                        $item->delete_Status();
                        $status = $term->read_Status;

                        foreach ($status as $key => $value) {
                            $item->create_Status([
                                "id_status"=>$value->id_status
                            ]);
                        }

                        $cond = false;
                    }
                    break;
                    case "append_sufix":
                        if(array_key_exists($term->code, $existente))
                            $term->code .= "_".rand_string();
                    break;
                }

                if($cond){
                    $lstatus = $term->read_Status;
                    $newTerm = new PanelAdminSectionTerm;
                    $newTerm->code = $term->code;
                    $newTerm->id_panel_admin_section = $iddestiny;
                    $newTerm->description = $term->description;
                    $newTerm->name = $term->name;
                    $newTerm->value = $term->value;
                    $newTerm->__create__();

                    foreach ($lstatus as $k => $value) {
                        $newTerm->create_Status([
                            "id_status"=>$value->id_status
                        ]);
                    }

                    $existente[strval($newTerm->code)] = $newTerm;
                }
            }
        }

        public function termsCloning(Request $request, $id){
            $id = strtolower($id) == "general"?null:$id;
            $sections = $request->input("data.sections");
            $option = $request->input("data.option");
            $list = PanelAdminSectionTerm::where("id_panel_admin_section", "=", $id)->get();
            $arr = array();

            foreach ($list as $key => $value) {
                $arr[strval($value->code)] = $value;
            }

            foreach ($sections as $key => $value) {
                $this->cloneTermsFromSection($value, $id, $option, $arr, $request);
            }

            $terms = PanelAdminSectionTerm::where("id_panel_admin_section", "=", $id)->get();
            $f = fopen(SYSTEM_TERMS_FOLDER."section-".$id."-terms.json", "w");

            foreach ($terms as $key => $value) {
                $tmp = $value;
                unset($terms[$key]);
                $terms[$value->code] = $value;
            }

            fwrite($f, json_encode($terms));
            fclose($f);

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["CLONE_TERMS"]
            ]);

            return \Response::json(array(), 204);
        }

        public function deleteTerm(Request $request, $id){
            $item = PanelAdminSectionTerm::where("id", "=", $id)->get()[0];
            $item->__delete__();
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["DELETE_TERM"]
            ]);
            return \Response::json(array(), 200);
        }

        public function downloadDictionary(Request $request){
            $sections = $request->input("data.sections");
            $terms = array();
            $languages = GetForUse("MasterLanguage");

            foreach ($languages as $key => $value) {
                $languages[$key]["name"] = json_decode($languages[$key]["name"]);
                $languages[$key]["description"] = json_decode($languages[$key]["description"]);
            }

            foreach ($sections as $k => $value) {
                $key = $value;

                if($value == "*"){
                    $value = null;
                }

                $arr = PanelAdminSectionTerm::where("id_panel_admin_section", "=", $value)->get();

                foreach ($arr as $index => $valor) {
                    $arr[$index]["name"] = json_decode($arr[$index]["name"]);
                    $arr[$index]["description"] = json_decode($arr[$index]["description"]);
                    $arr[$index]["value"] = json_decode($arr[$index]["value"]);
                }

                if($value == null){
                    $name_section = json_decode($GLOBALS["terms"]["str_general"]);
                }else{
                    $name_section = json_decode(PanelAdminSection::where("id", "=", $value)->get()[0]->name);
                }

                array_push($terms, [
                    "section_name" => $name_section,
                    "terms" => $arr/*,
                    "section_id" => $value
                    */
                ]);
            }

            $string_randomic = rand_string();
            $random_name_folder = TMP_FILES.$string_randomic;

            mkdir($random_name_folder);
            file_put_contents($random_name_folder."/data.js", "var Terms = ".json_encode(array(
                "terms" => $terms,
                "offline_dict" => array(
                    "offline_edition_dictionary_edition" => json_decode($GLOBALS["terms"]["offline_edition_dictionary_edition"]),
                    "offline_edition_dump" => json_decode($GLOBALS["terms"]["offline_edition_dump"]),
                    "offline_edition_code" => json_decode($GLOBALS["terms"]["offline_edition_code"]),
                    "offline_edition_name" => json_decode($GLOBALS["terms"]["offline_edition_name"]),
                    "offline_edition_description" => json_decode($GLOBALS["terms"]["offline_edition_description"]),
                    "offline_edition_value" => json_decode($GLOBALS["terms"]["offline_edition_value"]),
                    "offline_edition_remove" => json_decode($GLOBALS["terms"]["offline_edition_remove"]),
                    "offline_edition_dumped_edited_dictionary" => json_decode($GLOBALS["terms"]["offline_edition_dumped_edited_dictionary"]),
                    "offline_edition_close" => json_decode($GLOBALS["terms"]["offline_edition_close"]),
                    "offline_edition_reset" => json_decode($GLOBALS["terms"]["offline_edition_reset"]),
                ),
                "languages" => $languages
            )));

            copy(FILES_EXPORT_DICTIONARY_FOLDER."algorithm.js",     $random_name_folder."/algorithm.js");
            copy(FILES_EXPORT_DICTIONARY_FOLDER."angular.js",       $random_name_folder."/angular.js");
            copy(FILES_EXPORT_DICTIONARY_FOLDER."bootstrap.css",    $random_name_folder."/bootstrap.css");
            copy(FILES_EXPORT_DICTIONARY_FOLDER."bootstrap.js",     $random_name_folder."/bootstrap.js");
            copy(FILES_EXPORT_DICTIONARY_FOLDER."index.html",       $random_name_folder."/index.html");
            copy(FILES_EXPORT_DICTIONARY_FOLDER."jquery.js",        $random_name_folder."/jquery.js");

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["CREATE_DOWNLOAD_DICTIONARY"]
            ]);

            return \Response::json(array("hash" => $string_randomic), 200);
        }

        public function execDownloadDic(Request $request, $hash){
            $random_name_folder = TMP_FILES.$hash;
            compressFolder($random_name_folder."/", $hash.".zip", TMP_FILES);
            deleteDir($random_name_folder);
            $response = \Response::download($random_name_folder.".zip", "dictionary.zip");
            ob_end_clean();
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["EXECUTE_DOWNLOAD_DICTIONARY"]
            ]);
            return $response;
        }

        public function dicImporting(Request $request){
            if(count($_FILES) == 1 && isset($_FILES["file"])){
                $content = json_decode(file_get_contents($_FILES["file"]["tmp_name"]));

                if(json_last_error() == 0){
                    $tmp = PanelAdminSectionTerm::all();
                    $all_terms = array();

                    foreach ($tmp as $key => $value) {
                        $all_terms[strval($value->id)] = $value;
                    }

                    foreach ($content->terms as $key => $config) {
                        foreach ($config->terms as $k => $term) {
                            //$all_terms[strval($term->id)]->code = $term->code;
                            $all_terms[strval($term->id)]->name = json_encode($term->name);
                            $all_terms[strval($term->id)]->description = json_encode($term->description);
                            $all_terms[strval($term->id)]->value = json_encode($term->value);
                            $all_terms[strval($term->id)]->__update__();
                        }
                    }

                    return \Response::json([], 201);
                }else{
                    return \Response::json([], 400);
                }
            }else{
                return \Response::json([], 400);
            }

            /*

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["IMPORT_EDITED_DICTIONARY"]
            ]);

            */
        }

    /*DEVELOPER*/
        public function useOfStatus(Request $request, $idsection){
            $section = PanelAdminSection::where("id", "=", $idsection)->get()[0];
            $value = isTrue($request->input("data.use"))?1:0;
            $section->use_statuses = $value;
            $section->__update__();
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_USE_OF_STATUS"]
            ]);
            return \Response::json([
            ], 200);
        }

        public function setDefaultStatusesValues(Request $request, $idsection){
            $section = PanelAdminSection::where("id", "=", $idsection)->get()[0];
            $values = $request->input("data.values");

            if(gettype($values) == "array"){
                $value = json_encode($values);
            }else{
                $value = "[]";
            }

            $section->statuses_by_default  = $value;
            $section->__update__();
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_DEFAULT_STATUSES_VALUE_ON_SECTION"]
            ]);
            return \Response::json([
            ], 200);
        }

        public function setPermittedStatusesValues(Request $request, $idsection){
            $section = PanelAdminSection::where("id", "=", $idsection)->get()[0];
            $values = $request->input("data.values");

            if(gettype($values) == "array"){
                $value = json_encode($values);
            }else{
                $value = "[]";
            }

            $section->permitted_statuses  = $value;
            $section->__update__();
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_PERMITTED_STATUSES_VALUE_ON_SECTION"]
            ]);
            return \Response::json([
            ], 200);
        }

        public function multipleStatuses(Request $request, $idsection){
            $section = PanelAdminSection::where("id", "=", $idsection)->get()[0];
            $value = isTrue($request->input("data.multiple"))?1:0;
            $section->multiple_statuses = $value;
            $section->__update__();
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_MULTIPLE_STATUSES_ON_SECTION"]
            ]);
            return \Response::json([
            ], 200);
        }
}
