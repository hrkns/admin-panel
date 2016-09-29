<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\UserPreferences;
use App\Models\MasterStatus;
use App\Models\UserSession;

class Preferences extends Controller{
    /*global settings*/
        public function formatEditItems(Request $request){
            $format = $request->input("data.format");
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["format_edit_items"] = $format;
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_DEFAULT_FORMAT_EDIT_ITEMS"]
            ]);
            return \Response::json(array(), 204);
        }

        public function formatShowItems(Request $request){
            $format = $request->input("data.format");
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["format_show_items"] = $format;
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_DEFAULT_FORMAT_SHOW_ITEMS"]
            ]);
            return \Response::json(array(), 204);
        }

        public function updateLogo(Request $request){
            $img = $request->input("data.img");
            $name_img = rand_string(true).".jpeg";
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["logo"] = (WEB_ROOT."/assets/images/logos/".$name_img);
            $globalPreferences["logo_updated_by"] = $request->session()->get("iduser");
            base64_to_img($img, SYSTEM_DIR_LOGOS_IMGS.$name_img);
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["CHANGE_GENERAL_LOGO"]
            ]);
            return \Response::json(array(), 204);
        }

        public function setLetRegisterUser(Request $request){
            $val = $request->input("data.val");
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["let_register_user"] = $val;
            $globalPreferences["let_register_user_updated_by"] = $request->session()->get("iduser");
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_LET_REGISTER_USER"]
            ]);
            return \Response::json(array(), 204);
        }

        public function recoverAccountMechanism(Request $request){
            $val = $request->input("data.val");
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["recover_account"] = $val;
            $globalPreferences["recover_account_updated_by"] = $request->session()->get("iduser");
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            return \Response::json(array(), 204);
        }

        public function updateTabIcon(Request $request){
            $img = $request->input("data.img");
            $name_img = rand_string(true).".jpeg";
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["tab_icon"] = (WEB_ROOT."/assets/images/tab_icons/".$name_img);
            $globalPreferences["tab_icon_updated_by"] = $request->session()->get("iduser");
            base64_to_img($img, SYSTEM_DIR_TAB_ICONS_IMGS.$name_img);
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_GENERAL_ICON"]
            ]);
            return \Response::json(array(), 204);
        }

        public function termsAndPrivacy(Request $request){
            $val = $request->input("data.val");
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["terms_of_use_and_privacy_policy"][__LNG__] = $val;
            $globalPreferences["terms_of_use_and_privacy_policy_updated_by"] = $request->session()->get("iduser");
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_TERMS_OF_USE_AND_PRIVACY_POLICY"]
            ]);
            return \Response::json(array(), 204);
        }

        public function typeContentSignupEmail(Request $request){
            $val = $request->input("data.val");
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["content_registration_email"] = $val;
            $globalPreferences["content_registration_email_updated_by"] = $request->session()->get("iduser");
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_TYPE_CONTENT_SIGNUP_EMAIL"]
            ]);
            return \Response::json(array(), 204);
        }

        public function accountRecoveringMechanism(Request $request){
            $val = $request->input("data.val");
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["account_recovering_mechanism"] = $val;
            $globalPreferences["account_recovering_mechanism_updated_by"] = $request->session()->get("iduser");
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATED_ACCOUNT_RECOVERING_MECHANISM"]
            ]);
            return \Response::json(array(), 204);
        }

        public function accountRecoveringMechanismAutomatic(Request $request){
            $val = $request->input("data.val");
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["account_recovering_mechanism_automatic"] = $val;
            $globalPreferences["account_recovering_mechanism_automatic_updated_by"] = $request->session()->get("iduser");
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATED_ACCOUNT_RECOVERING_MECHANISM_AUTOMATIC"]
            ]);
            return \Response::json(array(), 204);
        }

        public function generalSessionDuration(Request $request){
            $apply = $request->input("data.apply");
            $amount = $request->input("data.amount");
            $type = $request->input("data.type");

            include FILE_ADMIN_PANEL_SETTINGS;

            $globalPreferences["apply_general_session_duration"] = $apply;
            $globalPreferences["general_session_duration_amount_val"] = $amount;
            $globalPreferences["general_session_duration_amount_type"] = $type;
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_GENERAL_SESSION_DURATION"]
            ]);
            return \Response::json(array(), 204);
        }

        public function defaultConfigInactivityTimeLimit(Request $request){
            $use_as = $request->input("data.use_as");
            $amount = $request->input("data.amount");
            $type = $request->input("data.type");

            include FILE_ADMIN_PANEL_SETTINGS;

            $globalPreferences["apply_default_config_inactivity_time_limit"] = $use_as;
            $globalPreferences["default_config_inactivity_time_limit_amount_val"] = $amount;
            $globalPreferences["default_config_inactivity_time_limit_amount_type"] = $type;
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_DEFAULT_CONFIG_INACTIVITY_TIME_LIMIT"]
            ]);
            return \Response::json(array(), 204);
        }

        public function defaultLanguageSystem(Request $request){
            $lng = $request->input("data.lng");
            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["default_language_system"] = $lng;
            $globalPreferences["default_language_system_updated_by"] = $request->session()->get("iduser");
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_DEFAULT_LANGUAGE_SYSTEM"]
            ]);
            return \Response::json(array(), 204);
        }



    /*custom settings*/
        public function customFormatEditItems(Request $request){
            $format = $request->input("data.format");
            $userPreferences = UserPreferences::where("id_user", "=", $request->session()->get("iduser"))->get()[0];
            $userPreferences->format_edit_items = $format;
            $userPreferences->__update__();
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_CUSTOM_FORMAT_EDIT_ITEMS"]
            ]);
            return \Response::json(array(), 204);
        }

        public function customFormatShowItems(Request $request){
            $format = $request->input("data.format");
            $userPreferences = UserPreferences::where("id_user", "=", $request->session()->get("iduser"))->get()[0];
            $userPreferences->format_show_items = $format;
            $userPreferences->__update__();
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_CUSTOM_FORMAT_SHOW_ITEMS"]
            ]);
            return \Response::json(array(), 204);
        }

        public function personalLogo(Request $request, $id){
            $userPreferences = UserPreferences::where("id_user", "=", $id)->get()[0];
            $img = $request->input("data.img");
            $name_img = rand_string(true).".jpeg";
            base64_to_img($img, SYSTEM_DIR_LOGOS_IMGS.$name_img);
            $userPreferences->logo = $name_img;
            $userPreferences->__update__();
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["CHANGE_CUSTOM_LOGO"]
            ]);

            return \Response::json(array(), 204);
        }

        public function amountItemsProgressiveRequests(Request $request, $id){
            $userPreferences = UserPreferences::where("id_user", "=", $id)->get()[0];
            $userPreferences->amount_items_per_request = $request->input("data.val");
            $userPreferences->__update__();
            $request->session()->put('amount_items_per_request', $userPreferences->amount_items_per_request );
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_AMOUNT_ITEMS_PROGRESSIVE_REQUEST"]
            ]);
            return \Response::json(array(), 204);
        }

        public function tabTitle(Request $request){
            $val_global = $request->input("data.val_global");
            $val_personal = $request->input("data.val_personal");
            $option = $request->input("data.option");
            $option = intval($option);

            include FILE_ADMIN_PANEL_SETTINGS;
            $globalPreferences["name_of_system"] = $val_global;
            $globalPreferences["name_of_system_updated_by"] = $request->session()->get("iduser");
            file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_NAME_OF_SYSTEM"]
            ]);
            return \Response::json(array(), 204);
        }

        public function personalTabIcon(Request $request, $id){
            $userPreferences = UserPreferences::where("id_user", "=", $id)->get()[0];
            $img = $request->input("data.img");
            $name_img = rand_string(true).".jpeg";
            base64_to_img($img, SYSTEM_DIR_TAB_ICONS_IMGS.$name_img);
            $userPreferences->tab_icon = $name_img;
            $userPreferences->__update__();
            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_CUSTOM_ICON"]
            ]);

            return \Response::json(array(), 204);
        }

        public function chatSoundAlert(Request $request){
            $type = $request->input("data.type");
            $general = $request->input("data.general");
            $customized = $request->input("data.customized");
            $election = $request->input("data.election");


            if($type == "global"){
                include FILE_ADMIN_PANEL_SETTINGS;
                $globalPreferences["chat_alert_sound"] = $general;
                $globalPreferences["chat_alert_sound_updated_by"] = $request->session()->get("iduser");
                file_put_contents(FILE_ADMIN_PANEL_SETTINGS, '<?php $globalPreferences = ' . var_export($globalPreferences, true) . ';?>');
            }else{
                $userPreferences = UserPreferences::where("id_user", "=", $request->session()->get("iduser"))->get()[0];
                $userPreferences->chat_alert_sound = $customized;
                $userPreferences->use_general_chat_alert_sound = $election;
                $userPreferences->__update__();
            }

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_TERMS_OF_USE_AND_PRIVACY_POLICY"]
            ]);

            return \Response::json(array(), 204);
        }

        public function customSessionDuration(Request $request){
            $use = $request->input("data.use");
            $amount = $request->input("data.amount");
            $type = $request->input("data.type");
            $userPreferences = UserPreferences::where("id_user", "=", $request->session()->get("iduser"))->get()[0];

            $request->session()->put("use_session_duration", $use);
            $userPreferences->use_session_duration = $use;
            $userPreferences->session_duration_amount_val = $amount;
            $userPreferences->session_duration_amount_type = $type;
            $userPreferences->__update__();

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_CUSTOM_SESSION_DURATION"]
            ]);
            return \Response::json(array(), 204);
        }

        public function customConfigInactivityTimeLimit(Request $request){
            $use_as = $request->input("data.use_as");
            $amount = $request->input("data.amount");
            $type = $request->input("data.type");
            $userPreferences = UserPreferences::where("id_user", "=", $request->session()->get("iduser"))->get()[0];

            $userPreferences->use_inactivity_time_limit_as = $use_as;
            $userPreferences->inactivity_time_limit_amount_val = $amount;
            $userPreferences->inactivity_time_limit_amount_type = $type;
            $userPreferences->__update__();


            $request->session()->put("inactivity_time_limit_action", $userPreferences->use_inactivity_time_limit_as);
            $request->session()->put("inactivity_time_limit", intval($userPreferences->inactivity_time_limit_amount_val) * [
                "seconds" => 1,
                "minutes" => 60,
                "hours" => 3600,
                "days" => 86400,
                "weeks" => 604800
            ][$userPreferences->inactivity_time_limit_amount_type]);

            __ACTIVITY__([
                "operation" => $GLOBALS["__OPERATION__"]["UPDATE_CUSTOM_CONFIG_INACTIVITY_TIME_LIMIT"]
            ]);
            return \Response::json(array(), 204);
        }
}