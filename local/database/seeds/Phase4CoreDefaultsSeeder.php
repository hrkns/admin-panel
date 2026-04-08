<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Phase4CoreDefaultsSeeder extends Seeder
{
    public function run()
    {
        $this->seedMasterStatus();
        $this->seedLanguages();
        $this->seedActions();
        $this->seedRoles();
        $this->seedSections();
        $this->seedUsers();
    }

    private function seedMasterStatus()
    {
        if (!Schema::hasTable('ap_master_status')) {
            return;
        }

        $rows = array(
            array('id' => 1, 'available_for_use' => '1', 'name' => '{"es":"Habilitado","en":"Enabled"}', 'description' => '{"es":"Disponible para uso","en":"Available for use"}', 'code' => 'ENABLED', 'show_default' => '1', 'show_item' => 1, 'for_delete' => 1),
            array('id' => 2, 'available_for_use' => '1', 'name' => '{"es":"Inhabilitado","en":"Disabled"}', 'description' => '{"es":"No disponible para uso","en":"Unavailable for use"}', 'code' => 'UNABLED', 'show_default' => '0', 'show_item' => 0, 'for_delete' => 0),
            array('id' => 3, 'available_for_use' => '1', 'name' => '{"es":"Eliminar","en":"Delete"}', 'description' => '{"es":"Marcado para eliminar","en":"Marked for delete"}', 'code' => 'DELETE', 'show_default' => '1', 'show_item' => 1, 'for_delete' => 1),
            array('id' => 6, 'available_for_use' => '1', 'name' => '{"es":"Pendiente por confirmación de registro","en":"Pending signup confirmation"}', 'description' => '{"es":"","en":""}', 'code' => 'SIGNUP_CONFIRMATION', 'show_default' => '1', 'show_item' => 0, 'for_delete' => 1),
            array('id' => 7, 'available_for_use' => '1', 'name' => '{"es":"Recuperación de cuenta","en":"Account recovery"}', 'description' => '{"es":"","en":""}', 'code' => 'ACCOUNT_RECOVERING', 'show_default' => '1', 'show_item' => 1, 'for_delete' => 1),
        );

        foreach ($rows as $row) {
            $this->upsertById('ap_master_status', $row);
        }

        $this->upsertStatusLink('ap_master_status_status', 1, 1);
        $this->upsertStatusLink('ap_master_status_status', 1, 2);
        $this->upsertStatusLink('ap_master_status_status', 1, 3);
        $this->upsertStatusLink('ap_master_status_status', 2, 1);
        $this->upsertStatusLink('ap_master_status_status', 3, 1);
        $this->upsertStatusLink('ap_master_status_status', 3, 3);
        $this->upsertStatusLink('ap_master_status_status', 6, 1);
        $this->upsertStatusLink('ap_master_status_status', 7, 1);
    }

    private function seedLanguages()
    {
        if (!Schema::hasTable('ap_master_language')) {
            return;
        }

        $rows = array(
            array('id' => 4, 'available_for_use' => '1', 'code' => 'es', 'name' => '{"es":"Español","en":"Spanish"}', 'description' => '{"es":"","en":""}'),
            array('id' => 5, 'available_for_use' => '1', 'code' => 'en', 'name' => '{"es":"Inglés","en":"English"}', 'description' => '{"es":"","en":""}'),
        );

        foreach ($rows as $row) {
            $this->upsertById('ap_master_language', $row);
        }

        $this->upsertStatusLink('ap_master_language_status', 4, 1);
        $this->upsertStatusLink('ap_master_language_status', 5, 1);
    }

    private function seedActions()
    {
        if (!Schema::hasTable('ap_panel_admin_action')) {
            return;
        }

        $rows = array(
            array('id' => 28, 'available_for_use' => '1', 'name' => '{"es":"Ver","en":"Read"}', 'description' => '{"es":"","en":""}', 'code' => 'read'),
            array('id' => 29, 'available_for_use' => '1', 'name' => '{"es":"Agregar","en":"Create"}', 'description' => '{"es":"","en":""}', 'code' => 'create'),
            array('id' => 30, 'available_for_use' => '1', 'name' => '{"es":"Editar","en":"Update"}', 'description' => '{"es":"","en":""}', 'code' => 'update'),
            array('id' => 32, 'available_for_use' => '1', 'name' => '{"es":"Eliminar","en":"Delete"}', 'description' => '{"es":"","en":""}', 'code' => 'delete'),
        );

        foreach ($rows as $row) {
            $this->upsertById('ap_panel_admin_action', $row);
            $this->upsertStatusLink('ap_panel_admin_action_status', $row['id'], 1);
        }
    }

    private function seedRoles()
    {
        if (!Schema::hasTable('ap_panel_admin_role')) {
            return;
        }

        $roles = array(
            array('id' => 42, 'available_for_use' => '1', 'name' => '{"es":"Administrador","en":"Manager"}', 'description' => '{"es":"","en":""}', 'code' => 'admin'),
            array('id' => 50, 'available_for_use' => '1', 'name' => '{"es":"Invitado","en":"Guest"}', 'description' => '{"es":"","en":""}', 'code' => 'guest'),
            array('id' => 51, 'available_for_use' => '1', 'name' => '{"es":"Programador","en":"Developer"}', 'description' => '{"es":"","en":""}', 'code' => 'developer'),
        );

        foreach ($roles as $role) {
            $this->upsertById('ap_panel_admin_role', $role);
            $this->upsertStatusLink('ap_panel_admin_role_status', $role['id'], 1);
        }
    }

    private function seedSections()
    {
        if (!Schema::hasTable('ap_panel_admin_section')) {
            return;
        }

        if ($this->importLegacyPanelAclFromSnapshot()) {
            return;
        }

        $sections = array(
            array('id' => 327, 'available_for_use' => '1', 'name' => '{"es":"Inicio","en":"Welcome"}', 'route_name' => 'home', 'id_parent' => null, 'position' => 0, 'last_activity_by' => 57, 'icon' => 'icon ion-android-home', 'use_statuses' => 0, 'statuses_by_default' => '[]', 'permitted_statuses' => '[]', 'multiple_statuses' => 0),
            array('id' => 346, 'available_for_use' => '1', 'name' => '{"es":"Usuarios","en":"Users"}', 'route_name' => 'users-management', 'id_parent' => null, 'position' => 2, 'last_activity_by' => 57, 'icon' => 'icon ion-android-people', 'use_statuses' => 1, 'statuses_by_default' => '["1","2","3"]', 'permitted_statuses' => '["1","2","3","6","7"]', 'multiple_statuses' => 1),
            array('id' => 351, 'available_for_use' => '1', 'name' => '{"es":"Preferencias","en":"Preferences"}', 'route_name' => 'settings', 'id_parent' => null, 'position' => 12, 'last_activity_by' => 57, 'icon' => 'icon ion-android-options', 'use_statuses' => 0, 'statuses_by_default' => '[]', 'permitted_statuses' => '[]', 'multiple_statuses' => 0),
            array('id' => 352, 'available_for_use' => '1', 'name' => '{"es":"Salir","en":"Logout"}', 'route_name' => 'logout', 'id_parent' => null, 'position' => 15, 'last_activity_by' => 57, 'icon' => 'icon ion-log-out', 'use_statuses' => 0, 'statuses_by_default' => '[]', 'permitted_statuses' => '[]', 'multiple_statuses' => 0),
            array('id' => 366, 'available_for_use' => '1', 'name' => '{"es":"Mi Perfil","en":"My Profile"}', 'route_name' => 'my-profile', 'id_parent' => null, 'position' => 1, 'last_activity_by' => 57, 'icon' => 'icon ion-android-happy', 'use_statuses' => 0, 'statuses_by_default' => '[]', 'permitted_statuses' => '[]', 'multiple_statuses' => 0),
        );

        foreach ($sections as $section) {
            $this->upsertById('ap_panel_admin_section', $section);
            $this->upsertStatusLink('ap_panel_admin_section_status', $section['id'], 1);
        }

        if (Schema::hasTable('ap_panel_admin_role_section')) {
            $bindings = array();
            $rolesWithBaselineMenu = array(42, 51);
            $baselineSections = array(327, 346, 351, 352, 366);

            foreach ($rolesWithBaselineMenu as $roleId) {
                foreach ($baselineSections as $sectionId) {
                    $bindings[] = array(
                        'id_panel_admin_role' => $roleId,
                        'id_section' => $sectionId,
                    );
                }
            }

            foreach ($bindings as $binding) {
                $this->upsertByPair('ap_panel_admin_role_section', 'id_panel_admin_role', $binding['id_panel_admin_role'], 'id_section', $binding['id_section'], $binding);
            }
        }

        if (Schema::hasTable('ap_panel_admin_role_section_action') && Schema::hasTable('ap_panel_admin_role_section')) {
            $roleSections = DB::table('ap_panel_admin_role_section')->whereIn('id_panel_admin_role', array(42, 51))->get();

            foreach ($roleSections as $roleSection) {
                foreach (array(28, 29, 30, 32) as $actionId) {
                    $this->upsertByPair(
                        'ap_panel_admin_role_section_action',
                        'id_panel_admin_role_section',
                        $roleSection->id,
                        'id_action',
                        $actionId,
                        array(
                            'id_panel_admin_role_section' => $roleSection->id,
                            'id_action' => $actionId,
                        )
                    );
                }
            }
        }
    }

    private function seedUsers()
    {
        if (!Schema::hasTable('ap_user')) {
            return;
        }

        $users = array(
            array('id' => 44, 'available_for_use' => '1', 'nick' => 'admin', 'email' => 'admin@email.com', 'hash_pass' => '$2y$10$ipRm72ADPlc4hYyFFnrqreTSSDWCs.B12lCa8eslEj3hnU7HBRbHy', 'default_language_session' => 'es', 'fullname' => 'Administrator', 'profile_img' => 'default.jpg'),
            array('id' => 47, 'available_for_use' => '1', 'nick' => 'guest', 'email' => 'guest@email.com', 'hash_pass' => '$2y$10$eLMHHQQ2lrxw5.14KObv4.ebSN09a0DGKETDzEzdGoZIhHrM1oHm.', 'default_language_session' => 'es', 'fullname' => 'Guest', 'profile_img' => 'default.jpg'),
            array('id' => 57, 'available_for_use' => '1', 'nick' => 'developer', 'email' => 'developer@email.com', 'hash_pass' => '$2y$10$MM3AZID9DgI//qPiCMmppu8aobQ62Lw/CctP3psKhMsUUtuP.SJfO', 'default_language_session' => 'es', 'fullname' => 'Developer', 'profile_img' => 'default.jpg'),
        );

        foreach ($users as $user) {
            $this->upsertById('ap_user', $user);
            $this->upsertStatusLink('ap_user_status', $user['id'], 1);
        }

        if (Schema::hasTable('ap_user_preferences')) {
            $preferences = array(
                array('id' => 44, 'id_user' => 44, 'logo' => 'default.jpeg', 'use_global_logo' => 0, 'amount_items_per_request' => 10, 'tab_icon' => 'default.png', 'use_global_tab_icon' => 0, 'tab_title' => 'Admin Panel', 'use_global_tab_title' => 0, 'chat_alert_sound' => 1, 'use_general_chat_alert_sound' => 0, 'use_session_duration' => 0, 'session_duration_amount_val' => 12, 'session_duration_amount_type' => 'hours', 'use_inactivity_time_limit_as' => 'no', 'inactivity_time_limit_amount_val' => 5, 'inactivity_time_limit_amount_type' => 'seconds', 'format_show_items' => 'progressive', 'format_edit_items' => 'inline'),
                array('id' => 47, 'id_user' => 47, 'logo' => 'default.jpeg', 'use_global_logo' => 0, 'amount_items_per_request' => 10, 'tab_icon' => 'default.png', 'use_global_tab_icon' => 0, 'tab_title' => 'Admin Panel', 'use_global_tab_title' => 0, 'chat_alert_sound' => 1, 'use_general_chat_alert_sound' => 0, 'use_session_duration' => 0, 'session_duration_amount_val' => 12, 'session_duration_amount_type' => 'hours', 'use_inactivity_time_limit_as' => 'no', 'inactivity_time_limit_amount_val' => 5, 'inactivity_time_limit_amount_type' => 'seconds', 'format_show_items' => 'progressive', 'format_edit_items' => 'inline'),
                array('id' => 57, 'id_user' => 57, 'logo' => 'default.jpeg', 'use_global_logo' => 0, 'amount_items_per_request' => 10, 'tab_icon' => 'default.png', 'use_global_tab_icon' => 0, 'tab_title' => 'Admin Panel', 'use_global_tab_title' => 0, 'chat_alert_sound' => 1, 'use_general_chat_alert_sound' => 0, 'use_session_duration' => 0, 'session_duration_amount_val' => 30, 'session_duration_amount_type' => 'seconds', 'use_inactivity_time_limit_as' => 'no', 'inactivity_time_limit_amount_val' => 30, 'inactivity_time_limit_amount_type' => 'seconds', 'format_show_items' => 'pagination', 'format_edit_items' => 'modal'),
            );

            foreach ($preferences as $row) {
                $this->upsertById('ap_user_preferences', $row);
            }
        }

        if (Schema::hasTable('ap_user_role')) {
            $this->upsertByPair('ap_user_role', 'id_user', 44, 'id_role', 42, array('id_user' => 44, 'id_role' => 42, 'value' => ''));
            $this->upsertByPair('ap_user_role', 'id_user', 47, 'id_role', 50, array('id_user' => 47, 'id_role' => 50, 'value' => ''));
            $this->upsertByPair('ap_user_role', 'id_user', 57, 'id_role', 51, array('id_user' => 57, 'id_role' => 51, 'value' => ''));
        }
    }

    private function upsertStatusLink($table, $idItem, $idStatus)
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $this->upsertByPair(
            $table,
            'id_item',
            $idItem,
            'id_status',
            $idStatus,
            array(
                'id_item' => $idItem,
                'id_status' => $idStatus,
            )
        );
    }

    private function upsertById($table, $data)
    {
        if (DB::table($table)->where('id', '=', $data['id'])->exists()) {
            DB::table($table)->where('id', '=', $data['id'])->update($data);
            return;
        }

        DB::table($table)->insert($data);
    }

    private function upsertByPair($table, $keyA, $valueA, $keyB, $valueB, $data)
    {
        $query = DB::table($table)->where($keyA, '=', $valueA)->where($keyB, '=', $valueB);

        if ($query->exists()) {
            $query->update($data);
            return;
        }

        DB::table($table)->insert($data);
    }

    private function importLegacyPanelAclFromSnapshot()
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return false;
        }

        $dumpPath = base_path('../admin_panel.sql');

        if (!file_exists($dumpPath)) {
            return false;
        }

        $sql = file_get_contents($dumpPath);

        if ($sql === false || trim($sql) === '') {
            return false;
        }

        $tables = array(
            'ap_panel_admin_role_section_action',
            'ap_panel_admin_role_section',
            'ap_panel_admin_section_status',
            'ap_panel_admin_role_status',
            'ap_panel_admin_action_status',
            'ap_panel_admin_section',
            'ap_panel_admin_role',
            'ap_panel_admin_action',
        );

        $existingTables = array();

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $existingTables[] = $table;
            }
        }

        if (count($existingTables) === 0) {
            return false;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($existingTables as $table) {
            DB::table($table)->delete();
        }

        foreach ($existingTables as $table) {
            $statements = $this->extractInsertStatementsForTable($sql, $table);

            foreach ($statements as $statement) {
                DB::unprepared($statement);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return true;
    }

    private function extractInsertStatementsForTable($sql, $table)
    {
        $pattern = '/INSERT INTO `'.preg_quote($table, '/').'`[\s\S]*?;\s*(?:\r?\n|$)/i';
        preg_match_all($pattern, $sql, $matches);

        if (!isset($matches[0])) {
            return array();
        }

        return $matches[0];
    }
}
