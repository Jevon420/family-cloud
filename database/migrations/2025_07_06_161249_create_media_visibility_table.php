<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaVisibilityTable extends Migration
{
    public function up()
    {
        Schema::create('media_visibility', function (Blueprint $table) {
            $table->id();

            $table->morphs('media'); // media_id + media_type (e.g., Photo, File)

            $table->enum('visibility', ['private', 'public', 'shared', 'link'])->default('private');

            $table->boolean('allow_download')->default(true);
            $table->timestamp('expires_at')->nullable(); // if temporary access

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media_visibility');
    }
}
