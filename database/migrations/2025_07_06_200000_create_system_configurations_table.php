<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, json, number, password
            $table->string('group')->nullable(); // system, cache, queue, mail, storage, security
            $table->text('description')->nullable();
            $table->boolean('is_sensitive')->default(false); // for passwords, API keys
            $table->boolean('requires_restart')->default(false); // if changing requires app restart
            $table->json('validation_rules')->nullable(); // JSON validation rules
            $table->string('access_level')->default('developer'); // developer, global_admin, admin

            // Audit fields
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('restored_at')->nullable();
            $table->foreignId('restored_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_configurations');
    }
}
