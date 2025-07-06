<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // owner
            $table->foreignId('gallery_id')->nullable()->constrained()->onDelete('set null');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('file_path');         // full-size image
            $table->string('thumbnail_path')->nullable(); // preview

            $table->text('description')->nullable();

            $table->enum('visibility', ['private', 'public'])->default('private');

            $table->string('mime_type');
            $table->unsignedBigInteger('file_size'); // in bytes

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
