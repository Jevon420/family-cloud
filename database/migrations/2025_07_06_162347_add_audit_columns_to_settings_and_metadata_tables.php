<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditColumnsToSettingsAndMetadataTables extends Migration
{
    public function up()
    {
        $tables = [
            'user_profiles',
            'user_settings',
            'site_settings',
            'announcements',
            'contact_messages',
            'shared_items',
            'media_visibility',
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
            'user_profiles',
            'user_settings',
            'site_settings',
            'announcements',
            'contact_messages',
            'shared_items',
            'media_visibility',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->dropSoftDeletes();
                }

                $columns = [
                    'created_by', 'updated_by', 'deleted_by', 'restored_at', 'restored_by'
                ];

                $columnsToRemove = [];
                foreach ($columns as $column) {
                    if (Schema::hasColumn($tableName, $column)) {
                        $columnsToRemove[] = $column;
                    }
                }

                if (!empty($columnsToRemove)) {
                    $table->dropColumn($columnsToRemove);
                }
            });
        }
    }
}
