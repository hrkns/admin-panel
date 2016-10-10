<?php
use Illuminate\Http\Request;
use App\Models\MasterLanguage;
use App\Models\PanelAdminSection;
use App\Models\UserSession;
use App\Models\UserPreferences;

/*
        echo "<pre>";
        print_r($_SERVER);
        echo "</pre>";
        exit();
    */

    function __TRANSLATION__($resource)
    {
        if (gettype($resource) == "string") {
            $obj = json_decode($resource);
        } else {
            $obj = $resource;
        }

        if (is_null($obj)) {
            return "";
        }

        if (gettype($obj) == "array") {
            if (array_key_exists(__LNG__, $obj)) {
                return $obj[__LNG__];
            }
        } else {
            if (property_exists($obj, __LNG__)) {
                $lng = __LNG__;
                return $obj->$lng;
            }
        }

        return "";
    }

    function translate($element)
    {
        return __TRANSLATION__($element);
    }

    function term($code, $general_flag = false)
    {
        if ($general_flag) {
            return translate($GLOBALS["terms"][$code]);
        } else {
            return translate($GLOBALS["section_terms"][$code]);
        }
    }

    function overSecureHTTP()
    {
        return
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ( isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    }

    $SERVER_NAME = isset($_SERVER["SERVER_NAME"])?$_SERVER["SERVER_NAME"]:"";
    $current_directory = str_replace("\\", "/", __DIR__);

    while (!in_array("root.ap", scandir($current_directory))) {
        $current_directory = substr($current_directory, 0, strrpos($current_directory, "/"));
    }

    //principales constantes
    define("WEB_URL", (overSecureHTTP()?"https://":"http://").$SERVER_NAME);
    define("PROJECT_FOLDER", substr($current_directory, strlen($_SERVER["DOCUMENT_ROOT"])));
    define("WEB_ROOT", PROJECT_FOLDER);            //check if there could be problems with this constant and .htaccess rwrite url rules
    define("PROGRESSIVE_REQUEST_TOKENS", "PROGRESSIVE_REQUEST_TOKENS");
    define("PROJECT_WEB_ROOT", $_SERVER["DOCUMENT_ROOT"].PROJECT_FOLDER."/assets");
    define("PROJECT_SYSTEM_ROOT", $_SERVER["DOCUMENT_ROOT"].PROJECT_FOLDER."/local");
    define("CLOUD_ROUTE", PROJECT_SYSTEM_ROOT."/storage/admin-panel/cloud/");
    define("TMP_FILES", PROJECT_SYSTEM_ROOT."/storage/admin-panel/tmp/");
    define("JS_SECTIONS_FOLDER", PROJECT_WEB_ROOT."/js/sections/");
    define("SYSTEM_TERMS_FOLDER", PROJECT_WEB_ROOT."/js/__languages__/");
    define("SYSTEM_AUDIO_NOTIFICATIONS_FOLDER", PROJECT_WEB_ROOT."/audio/notifications/");
    define("PROJECT_VIEWS_ROOT", PROJECT_SYSTEM_ROOT."/resources/views/");
    define("MODALS_ROUTE", PROJECT_VIEWS_ROOT."app/modals/");
    define("SYSTEM_DIR_PROFILE_IMGS", PROJECT_WEB_ROOT."/images/profile/");
    define("SYSTEM_DIR_CLIENT_IMGS", PROJECT_WEB_ROOT."/images/client/");
    define("SYSTEM_DIR_IMGS", PROJECT_WEB_ROOT."/images/");
    define("PUBLIC_IMAGES_FOLDER", WEB_ROOT."/assets/images/");
    define("PUBLIC_PROFILE_IMAGES_FOLER", PUBLIC_IMAGES_FOLDER."profile/");
    define("SYSTEM_DIR_LOGOS_IMGS", PROJECT_WEB_ROOT."/images/logos/");
    define("SYSTEM_DIR_TAB_ICONS_IMGS", PROJECT_WEB_ROOT."/images/tab_icons/");
    define("SYSTEM_DIR_ORGANIZATION_IMGS", PROJECT_WEB_ROOT."/images/organization/");
    define("FILE_ADMIN_PANEL_SETTINGS", PROJECT_SYSTEM_ROOT."/admin-panel-settings.php");
    define("LOADING_ICON", WEB_ROOT."/assets/img/loading.gif");
    define("FORBIDDEN_ACCESS_VIEW", PROJECT_SYSTEM_ROOT."/resources/views/app/sections/include/forbidden-access.blade.php");
    define("SEARCH_CONTROLS_VIEW", PROJECT_SYSTEM_ROOT."/resources/views/app/sections/include/search-controls.blade.php");
    define("MESSAGE_ITEMS_VIEW", PROJECT_SYSTEM_ROOT."/resources/views/app/sections/include/message_items.blade.php");
    define("FILES_EXPORT_DICTIONARY_FOLDER", PROJECT_SYSTEM_ROOT."/storage/admin-panel/auxiliar/export-dictionary/");
    define("DEFAULT_PROFILE_IMG", WEB_ROOT . "/assets/images/profile/default.jpg");
    include FILE_ADMIN_PANEL_SETTINGS;
    define("DEFAULT_LANGUAGE", $globalSettings["default_language_system"]);
    define("FILE_URL_ROUTES", PROJECT_SYSTEM_ROOT."/app/Http/routes_sections_url.php");
    define("DEFAULT_CLIENT_IMAGE", WEB_ROOT . "/assets/images/client/default.jpg");

    function sanitize($s)
    {
        return $s;
    }

    function rand_string($flag=true)
    {
        if ($flag) {
            $random_id = uniqid(mt_rand());
            $s = md5($random_id);
        } else {
            $s = "";

            for ($i = 0; $i < 16; $i++) {
                $s.=rand(0, 9);
            }
        }

        return $s;
    }


    function dbLanguages()
    {
        return MasterLanguage::all();
    }

    function arrayLanguagesKeys($arr)
    {
        $v = array();

        foreach ($arr as $key => $value) {
            $v[$value["code"]] = "";
        }

        return $v;
    }

    /*
        get an base64 string, and create and image file based on it
    */
    function base64_to_img($base64_string, $output_file)
    {
        $ifp = @fopen($output_file, "wb");
        $data = explode(',', $base64_string);
        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);
    }

    /*
        print the tree of sections under an specific root section (a parent section)
    */
    function print_root_menu_content($idsection)
    {
        echo "<ul>";

        function print_item_menu($item, $pre_route)
        {
            $children = PanelAdminSection::where("id_parent", "=", $item->id)->orderBy("position", "asc")->get();
            $n = $children->count(); ?>
			<li>
				<a onclick = "$('#lateral_menu').find('*[data-id=<?php echo $item->id; ?>]').trigger('click')" href="javascript:;" data-id="<?php echo $item->id; ?>" data-route="<?php echo $pre_route.$item->route_name; ?>">
					<h5 class="title" style = "display:inline !important;"> <strong><?php echo translate($item->name); ?> </strong></h5><?php echo $n>0?'<i class="icon-arrow"></i>':""; ?>
				</a>
				<?php
                if ($n>0) {
                    ?>
					<ul class="sub-menu">
						<?php
                        foreach ($children as $key => $value) {
                            print_item_menu($value, $pre_route.$item->route_name."/");
                        } ?>
					</ul>
					<?php

                } ?>
			</li>
			<?php

        }

        $items = PanelAdminSection::where("id", "=", $idsection)->orderBy("position", "asc")->get();

        foreach ($items as $key => $value) {
            print_item_menu($value, WEB_ROOT."/");
        }

        echo "</ul>";
    }

    /*
        return a date in sql format
    */
    function sqldate()
    {
        return @date('Y-m-d H:i:s');
    }

    /*
        check if a given array is associative
    */
    function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /*
        send an email using the phpmailer library, and [for the moment] the Gmail SMTP
    */
    function sendEmail($config)
    {
        include PROJECT_SYSTEM_ROOT."/app/Http/Controllers/__phpmailer/class.phpmailer.php";
        include PROJECT_SYSTEM_ROOT."/app/Http/Controllers/__phpmailer/class.smtp.php";
        include FILE_ADMIN_PANEL_SETTINGS;

        $mail = new \PHPMailer(true);
        $mail->IsSMTP();
        $mail->PluginDir = "includes/";

        if ($globalSettings["smtp_secure"]) {
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
        }

        $mail->Host = $globalSettings["smtp_host"];
        $mail->Port = $globalSettings["smtp_port"];
        $mail->Username = $globalSettings["smtp_email_from"];
        $mail->Password = $globalSettings["smtp_password_from"];
        $mail->Timeout=5;
        $mail->set('X-Priority', '1');
        $email_from = $mail->Username;
        $name_from = $globalSettings["smtp_fullname_from"];
        $mail->AddAddress($config["email"], $config["fullname"]);
        $mail->SetFrom($email_from, $name_from);
        $mail->Subject = $config["title"];
        $mail->Body = $config["content"];

        $mail->IsHTML(true);

        try {
            $envi=  $mail->Send();
        } catch (Exception $e) {
        }

        $mail->ClearAddresses();
    }

    /*
        generate an array where each field is referenced with a key that is a language code

        the value of each field is an empty string
    */
    function generateMultilingual($str)
    {
        $tknsLanguages = arrayLanguagesKeys(dbLanguages());
        foreach ($tknsLanguages as $key => $value) {
            $tknsLanguages[$key] = $str;
        }
        return json_encode($tknsLanguages);
    }

    /*
        set the value of a multilingual field [of an entity of a model] in a specific language
    */
    function setFieldMultilingual($fields, $str, $lng = null)
    {
        if ($lng == null) {
            $lng = __LNG__;
        }

        $arr = array();
        $fields = json_decode($fields);

        foreach ($fields as $key => $value) {
            $arr[$key] = $value;
        }

        $arr[$lng] = $str;

        return json_encode($arr);
    }

    /*
        return the list of multilingual terms of a section, if the parameter is null, returns the general terms
    */
    function terms($idsection = null)
    {
        $id=$idsection;

        if (file_exists(SYSTEM_TERMS_FOLDER."section-".$id."-terms.json")) {
            $terms = file_get_contents(SYSTEM_TERMS_FOLDER."section-".$id."-terms.json");
        } else {
            $terms = '[]';
        }

        $terms = json_decode($terms);
        $packed_terms = array();

        foreach ($terms as $key => $value) {
            $packed_terms[$key] = ($value->value);
        }


        if (file_exists(SYSTEM_TERMS_FOLDER."section--terms.json")) {
            $terms = file_get_contents(SYSTEM_TERMS_FOLDER."section--terms.json");
        } else {
            $terms = '[]';
        }

        $terms = json_decode($terms);
        $general_terms = array();

        foreach ($terms as $key => $value) {
            $general_terms[$key] = ($value->value);
        }

        $packed_terms["__GENERAL__"] = $general_terms;
        return $packed_terms;
    }

    /*
        create a session, settling the database registers and Laravel session variables
    */
    function createSession($data, $request)
    {
        $session = new UserSession;
        $session->id_user = $data->id;
        $session->info = json_encode(array(
            "ip"=>$request->ip(),
            "user_agent"=>$request->header('User-Agent')
        ));
        $session->start = sqldate();
        $session->end = null;
        $session->__create__();

        $preferences = UserPreferences::where("id_user", "=", $data->id)->get()[0];
        $request->session()->put('iduser', $data->id);
        $request->session()->put('lng', $data->default_language_session);
        $request->session()->put('amount_items_per_request', $preferences["amount_items_per_request"]);
        $request->session()->put('datauser', $data->toJson());
        $request->session()->put('idsession', $session->id);
        $request->session()->put(PROGRESSIVE_REQUEST_TOKENS, array());

        $request->session()->put("started_at", sqldate());
        $request->session()->put("use_session_duration", $preferences["use_session_duration"]);
        $request->session()->put("format_show_items", $preferences["format_show_items"]);
        $request->session()->put("format_edit_items", $preferences["format_edit_items"]);
        $request->session()->put("session_duration_limit", intval($preferences["session_duration_amount_val"]) * [
            "seconds" => 1,
            "minutes" => 60,
            "hours" => 3600,
            "days" => 86400,
            "weeks" => 604800
        ][$preferences["session_duration_amount_type"]]);

        $request->session()->put("inactivity_time_limit_action", $preferences["use_inactivity_time_limit_as"]);
        $request->session()->put("inactivity_time_limit", intval($preferences["inactivity_time_limit_amount_val"]) * [
            "seconds" => 1,
            "minutes" => 60,
            "hours" => 3600,
            "days" => 86400,
            "weeks" => 604800
        ][$preferences["inactivity_time_limit_amount_type"]]);
    }

    /*
        given two dates, returns the difference between them in seconds
    */
    function s_datediff($str_interval, $dt_menor, $dt_maior, $relative=false)
    {
        if (is_string($dt_menor)) {
            $dt_menor = date_create($dt_menor);
        }

        if (is_string($dt_maior)) {
            $dt_maior = date_create($dt_maior);
        }
        
        $diff = date_diff($dt_menor, $dt_maior);//, ! $relative);

        switch ($str_interval) {
            case "y":{
                $total = $diff->y + $diff->m / 12 + $diff->d / 365.25;
            }break;

            case "m":{
                $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
            }break;

            case "d":{
                $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
            }break;

            case "h":{
                $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
            }break;

            case "i":{
                $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
            }break;

            case "s":{
                $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
            }break;
        }

        if ($diff->invert) {
            return -1 * $total;
        } else {
            return $total;
        }
    }

        function difftime($date1, $date2)
        {
            return s_datediff("s", $date1, $date2);
        }

    /*
        check if there is still a session opened for the user that has sent the given request as parameter
    */
    function session_verification($request)
    {
        if (!$request->session()->has("idsession") ||
            count(UserSession::where("id", "=", $request->session()->get("idsession"))->get()) == 0) {
            return \Response::json([
                "session" => "finished"
            ], 401);
        }

        if ($request->session()->get("use_session_duration") == "1" &&
            difftime($request->session()->get("started_at"), sqldate()) > intval($request->session()->get("session_duration_limit"))) {
            $request->session()->forget(PROGRESSIVE_REQUEST_TOKENS);
            $request->session()->forget('idsession');
            $request->session()->forget('iduser');
            $request->session()->forget('datauser');
            return \Response::json([
                "session" => "finished"
            ], 401);
        }

        return true;
    }

    /*
        remove a folder and its content
    */
    function deleteDir($dirPath)
    {
        if (substr($dirPath, strlen($dirPath) - 1, 1) != "/") {
            $dirPath .= "/";
        }

        $files =glob($dirPath . "*", GLOB_MARK);

        foreach ($files as $file) {
            if (is_dir($file)) {
                deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    /*
        compress a folder and its content
    */
    function compressFolder($folder, $filename, $basefolder = CLOUD_ROUTE)
    {
        $zip = new \ZipArchive;
        $zip->open($basefolder.$filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $archivos = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder), \RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($archivos as $namearchivo => $filearchivo) {
            if (!$filearchivo->isDir()) {
                $filePath = $filearchivo->getRealPath();
                $relativePath = substr($filePath, strlen($folder));
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
    }

    /*
        depending of some logic, eval if a given value calificate as true or false
    */
    function isTrue($s)
    {
        switch (gettype($s)) {
            case "integer":{
                return $s == 1;
            }break;
            case "float":{
                return $s == 1;
            }break;
            case "real":{
                return $s == 1;
            }break;
            case "double":{
                return $s == 1;
            }break;
            case "string":{
                return strtolower($s) == "true" || strtolower($s) == "1";
            }break;
            case "boolean":{
                return $s;
            }break;
            case "object":{
                return $s != null;
            }break;
            default:{
                return false;
            }
        }
    }

    /*
        print the public url of an assets (.css, .js, an image, etc...) using Laravel helpers
        depending of the connection (if it's secure or not)
    */
    function AP_Asset($str)
    {
        if (overSecureHTTP()) {
            return secure_asset($str);
        } else {
            return asset($str);
        }
    }

    /*
        a kind of special term of the system dictionary, used as message field of JSON http responses
    */
    function HTTP_message($code)
    {
        return translate($GLOBALS["terms"][$code]);
    }

    /*
        print the base pagination controls
    */
    function print_pagination_skeleton($terms)
    {
        ?>
		<div align = "center">
			<table style = "width:;" class = "">
				<tr>
					<td class = "pagination-cell" style = "display:none;" data-pagination-page = "first" title = "<?php echo translate($terms["__GENERAL__"]["str_first_page"]); ?>">
						<i class = "fa fa-chevron-left"></i>
						<i class = "fa fa-chevron-left"></i>
					</td>
					<td class = "pagination-cell" style = "display:none;" data-pagination-page = "previous" title = "<?php echo translate($terms["__GENERAL__"]["str_previous_page"]); ?>">
						<i class = "fa fa-chevron-left"></i>
					</td>
					<td class = "pagination-cell" style = "display:none;" data-pagination-page = "next" title = "<?php echo translate($terms["__GENERAL__"]["str_next_page"]); ?>">
						<i class = "fa fa-chevron-right"></i>
					</td>
					<td class = "pagination-cell" style = "display:none;" data-pagination-page = "last" title = "<?php echo translate($terms["__GENERAL__"]["str_last_page"]); ?>">
						<i class = "fa fa-chevron-right"></i>
						<i class = "fa fa-chevron-right"></i>
					</td>
				</tr>
			</table>
		</div>
		<br>
		<?php

    }

    /*
        get a list of items of a model 'availables for user'
    */
    function GetForUse($nameClass)
    {
        $nameClass = "App\\Models\\".$nameClass;
        return $nameClass::where("available_for_use", "=", "1")->get();
    }

    /*
        save global settings
    */

    function saveGlobalSettings($arr)
    {
        file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalSettings = ' . var_export($arr, true) . ';?>');
    }
?>