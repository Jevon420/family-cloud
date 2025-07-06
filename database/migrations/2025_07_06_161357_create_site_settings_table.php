<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();             // e.g., 'max_upload_size'
            $table->text('value')->nullable();           // stored as string or JSON
            $table->string('type')->default('string');   // string, boolean, json, number
            $table->string('group')->nullable();         // group by UI section (e.g., 'storage', 'branding')
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_settings');
    }
}
