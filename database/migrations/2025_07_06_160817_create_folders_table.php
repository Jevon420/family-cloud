<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoldersTable extends Migration
{
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // owner
            $table->unsignedBigInteger('parent_id')->nullable(); // for nested folders
            $table->string('name');
            $table->string('slug')->unique(); // URL-safe folder name
            $table->text('description')->nullable(); // folder description
            $table->string('cover_image')->nullable(); // path to cover image
            $table->unsignedBigInteger('folder_size')->default(0); // in bytes
            $table->enum('visibility', ['private', 'public'])->default('private');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('folders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('folders');
    }
}
