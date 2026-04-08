<?php

use App\Support\Database\LegacySqlSnapshot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Phase4SchemaBootstrap extends Migration
{
    public function up()
    {
        if (Schema::hasTable('ap_user') && Schema::hasTable('ap_panel_admin_role')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            $this->importMysqlSnapshotSchema();
            return;
        }

        $this->createPortableCoreSchema();
    }

    public function down()
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            return;
        }

        $tables = array(
            'deleted_user_session_activity',
            'updated_user_session_activity',
            'created_user_session_activity',
            'deleted_user_session',
            'updated_user_session',
            'created_user_session',
            'ap_user_signup_confirmation',
            'ap_user_status',
            'ap_panel_admin_section_status',
            'ap_panel_admin_action_status',
            'ap_panel_admin_role_status',
            'ap_master_language_status',
            'ap_master_status_status',
            'ap_panel_admin_role_section_action',
            'ap_panel_admin_role_section',
            'ap_panel_admin_action',
            'ap_panel_admin_section',
            'ap_panel_admin_role',
            'ap_user_role',
            'ap_user_preferences',
            'ap_user_session_activity',
            'ap_user_session',
            'ap_user',
            'ap_master_language',
            'ap_master_status',
        );

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }

    private function importMysqlSnapshotSchema()
    {
        $dumpPath = base_path('../admin_panel.sql');

        if (!file_exists($dumpPath)) {
            throw new RuntimeException('Missing SQL snapshot file: '.$dumpPath);
        }

        $statements = LegacySqlSnapshot::extractSchemaStatements(file_get_contents($dumpPath));

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    private function createPortableCoreSchema()
    {
        Schema::create('ap_master_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('available_for_use', 1)->default('1');
            $table->text('name');
            $table->text('description');
            $table->string('code', 32);
            $table->string('show_default', 1)->default('1');
            $table->boolean('show_item')->default(true);
            $table->boolean('for_delete')->default(false);
        });

        Schema::create('ap_master_language', function (Blueprint $table) {
            $table->increments('id');
            $table->string('available_for_use', 1)->default('1');
            $table->string('code', 16);
            $table->text('name');
            $table->text('description');
        });

        Schema::create('ap_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('available_for_use', 1)->default('1');
            $table->string('nick', 100);
            $table->string('email', 320);
            $table->string('hash_pass', 512);
            $table->string('default_language_session', 16)->default('en');
            $table->string('fullname', 256);
            $table->string('profile_img', 512)->default('default.jpg');
        });

        Schema::create('ap_user_session', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->text('info');
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
        });

        Schema::create('ap_user_session_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user_session')->unsigned();
            $table->integer('id_operation')->unsigned()->nullable();
            $table->string('hash_operation', 256)->nullable();
            $table->text('info');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('ap_user_preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned()->nullable();
            $table->string('logo', 512)->default('default.jpeg');
            $table->boolean('use_global_logo')->default(false);
            $table->integer('amount_items_per_request')->default(10);
            $table->string('tab_icon', 512)->default('default.png');
            $table->integer('use_global_tab_icon')->default(0);
            $table->string('tab_title', 512)->default('Admin Panel');
            $table->boolean('use_global_tab_title')->default(false);
            $table->integer('chat_alert_sound')->unsigned()->default(1);
            $table->boolean('use_general_chat_alert_sound')->default(false);
            $table->boolean('use_session_duration')->default(false);
            $table->integer('session_duration_amount_val')->unsigned()->default(30);
            $table->string('session_duration_amount_type', 16)->default('minutes');
            $table->string('use_inactivity_time_limit_as', 16)->default('no');
            $table->integer('inactivity_time_limit_amount_val')->unsigned()->default(30);
            $table->string('inactivity_time_limit_amount_type', 16)->default('minutes');
            $table->string('format_show_items', 16)->default('pagination');
            $table->string('format_edit_items', 16)->default('modal');
        });

        Schema::create('ap_user_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned()->nullable();
            $table->integer('id_role')->unsigned()->nullable();
            $table->string('value', 512)->default('');
        });

        Schema::create('ap_panel_admin_role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('available_for_use', 1)->default('1');
            $table->text('name');
            $table->text('description');
            $table->string('code', 128);
        });

        Schema::create('ap_panel_admin_section', function (Blueprint $table) {
            $table->increments('id');
            $table->string('available_for_use', 1)->default('1');
            $table->text('name');
            $table->string('route_name', 256);
            $table->integer('id_parent')->unsigned()->nullable();
            $table->integer('position')->default(0);
            $table->integer('last_activity_by')->default(0);
            $table->string('icon', 64)->default('fa fa-folder');
            $table->boolean('use_statuses')->default(true);
            $table->string('statuses_by_default', 2048)->default('[]');
            $table->string('permitted_statuses', 2048)->default('[]');
            $table->boolean('multiple_statuses')->default(true);
        });

        Schema::create('ap_panel_admin_action', function (Blueprint $table) {
            $table->increments('id');
            $table->string('available_for_use', 1)->default('1');
            $table->text('name');
            $table->text('description');
            $table->string('code', 32);
        });

        Schema::create('ap_panel_admin_role_section', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_panel_admin_role')->unsigned()->nullable();
            $table->integer('id_section')->unsigned()->nullable();
        });

        Schema::create('ap_panel_admin_role_section_action', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_panel_admin_role_section')->unsigned()->nullable();
            $table->integer('id_action')->unsigned()->nullable();
        });

        Schema::create('ap_master_status_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned()->nullable();
            $table->integer('id_status')->unsigned()->nullable();
        });

        Schema::create('ap_master_language_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned()->nullable();
            $table->integer('id_status')->unsigned()->nullable();
        });

        Schema::create('ap_panel_admin_role_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned()->nullable();
            $table->integer('id_status')->unsigned()->nullable();
        });

        Schema::create('ap_panel_admin_action_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned()->nullable();
            $table->integer('id_status')->unsigned()->nullable();
        });

        Schema::create('ap_panel_admin_section_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned()->nullable();
            $table->integer('id_status')->unsigned()->nullable();
        });

        Schema::create('ap_user_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned()->nullable();
            $table->integer('id_status')->unsigned()->nullable();
        });

        Schema::create('ap_user_signup_confirmation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user')->unsigned();
            $table->string('code', 2048);
        });

        Schema::create('created_user_session', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->unsigned()->nullable();
            $table->text('info');
            $table->string('hash_operation', 256);
        });

        Schema::create('updated_user_session', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned();
            $table->timestamp('updated_at')->useCurrent();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->text('info');
            $table->string('hash_operation', 256);
        });

        Schema::create('deleted_user_session', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned();
            $table->timestamp('deleted_at')->useCurrent();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->text('info');
            $table->string('hash_operation', 256);
        });

        Schema::create('created_user_session_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned();
            $table->timestamp('created_at')->useCurrent();
            $table->integer('created_by')->unsigned()->nullable();
            $table->text('info');
            $table->string('hash_operation', 256);
        });

        Schema::create('updated_user_session_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned();
            $table->timestamp('updated_at')->useCurrent();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->text('info');
            $table->string('hash_operation', 256);
        });

        Schema::create('deleted_user_session_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_item')->unsigned();
            $table->timestamp('deleted_at')->useCurrent();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->text('info');
            $table->string('hash_operation', 256);
        });
    }
}
