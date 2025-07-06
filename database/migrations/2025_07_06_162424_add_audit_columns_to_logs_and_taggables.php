<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditColumnsToLogsAndTaggables extends Migration
{
    public function up()
    {
        $tables = [
            'login_activities',
            'download_logs',
            'taggables',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes();

                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('restored_at')->nullable();
                $table->foreignId('restored_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down()
    {
        $tables = [
            'login_activities',
            'download_logs',
            'taggables',
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
