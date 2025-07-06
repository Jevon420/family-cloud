<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSiteSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('description')->nullable()->after('group');
            $table->json('validation_rules')->nullable()->after('description');
            $table->string('access_level')->default('global_admin')->after('validation_rules'); // developer, global_admin, admin
            $table->boolean('is_public')->default(false)->after('access_level'); // can be viewed by all users
        });
    }

    public function down()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['description', 'validation_rules', 'access_level', 'is_public']);
        });
    }
}
