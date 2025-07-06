<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedItemsTable extends Migration
{
    public function up()
    {
        Schema::create('shared_items', function (Blueprint $table) {
            $table->id();

            $table->morphs('shared'); // shared_id + shared_type (file, photo, gallery, etc.)
            $table->foreignId('shared_with_user_id')->constrained('users')->onDelete('cascade');

            $table->enum('access_level', ['view', 'download'])->default('view');
            $table->timestamp('expires_at')->nullable(); // Optional expiry date

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shared_items');
    }
}
