<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleriesTable extends Migration
{
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // owner

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->string('cover_image')->nullable(); // one photo preview
            $table->enum('visibility', ['private', 'public'])->default('private');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('galleries');
    }
}
