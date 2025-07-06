<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditColumnsToExistingTables extends Migration
{
    public function up()
    {
        $tables = [
            'users',
            'files',
            'folders',
            'galleries',
            'photos',
            'user_profiles',
            'user_settings',
            'site_settings',
            'shared_items',
            'media_visibility',
            'contact_messages',
            'download_logs',
            'login_activities',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->softDeletes();
                }

                if (!Schema::hasColumn($tableName, 'created_by')) {
                    $table->unsignedBigInteger('created_by')->nullable();
                }

                if (!Schema::hasColumn($tableName, 'updated_by')) {
                    $table->unsignedBigInteger('updated_by')->nullable();
                }

                if (!Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->unsignedBigInteger('deleted_by')->nullable();
                }

                if (!Schema::hasColumn($tableName, 'restored_at')) {
                    $table->timestamp('restored_at')->nullable();
                }

                if (!Schema::hasColumn($tableName, 'restored_by')) {
                    $table->unsignedBigInteger('restored_by')->nullable();
                }
            });
        }
    }

    public function down()
    {
        $tables = [
            'users',
            'files',
            'folders',
            'galleries',
            'photos',
            'user_profiles',
            'user_settings',
            'site_settings',
            'shared_items',
            'media_visibility',
            'contact_messages',
            'download_logs',
            'login_activities',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes();
                $table->dropColumn([
                    'created_by', 'updated_by', 'deleted_by', 'restored_at', 'restored_by'
                ]);
            });
        }
    }
}
