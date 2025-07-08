<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_media', function (Blueprint $table) {
            $table->id();
            $table->string('media_type');
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('shared_by');
            $table->unsignedBigInteger('shared_with');
            $table->string('share_token')->unique();
            $table->json('permissions')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('restored_at')->nullable();
            $table->unsignedBigInteger('restored_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['media_type', 'media_id']);
            $table->index(['shared_with', 'expires_at']);
            $table->index(['shared_by', 'expires_at']);
            $table->index(['share_token']);

            $table->foreign('shared_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shared_with')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('restored_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_media');
    }
}
