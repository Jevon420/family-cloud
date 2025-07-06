<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // owner
            $table->foreignId('folder_id')->nullable()->constrained()->onDelete('set null');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('file_path'); // actual storage path
            $table->string('thumbnail_path')->nullable(); // preview if generated

            $table->text('description')->nullable();

            $table->enum('visibility', ['private', 'public'])->default('private');

            $table->string('mime_type');
            $table->unsignedBigInteger('file_size'); // in bytes

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
}
