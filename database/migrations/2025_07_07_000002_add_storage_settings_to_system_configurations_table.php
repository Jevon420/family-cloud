<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStorageSettingsToSystemConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            $table->decimal('total_storage_gb', 10, 2)->default(0)->after('value');
            $table->decimal('assigned_storage_gb', 10, 2)->default(0)->after('total_storage_gb');
            $table->decimal('storage_percentage', 5, 2)->default(80.00)->after('assigned_storage_gb');
            $table->boolean('auto_detect_storage')->default(true)->after('storage_percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_configurations', function (Blueprint $table) {
            $table->dropColumn(['total_storage_gb', 'assigned_storage_gb', 'storage_percentage', 'auto_detect_storage']);
        });
    }
}
