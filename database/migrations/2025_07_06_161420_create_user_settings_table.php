<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('key');                // e.g., 'dark_mode'
            $table->text('value')->nullable();    // 'true', 'false', 'compact', etc.
            $table->string('type')->default('string');
            $table->string('group')->nullable();  // UI, Notifications, Media, etc.

            $table->timestamps();

            $table->unique(['user_id', 'key']); // Prevent duplicate keys per user
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
}
