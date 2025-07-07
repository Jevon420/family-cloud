<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_id');
            $table->string('filename'); // Original filename
            $table->string('stored_filename'); // Stored filename on disk
            $table->string('mime_type');
            $table->bigInteger('size'); // File size in bytes
            $table->string('content_id')->nullable(); // For inline attachments
            $table->boolean('is_inline')->default(false);
            $table->string('storage_path'); // Path to stored file
            $table->string('hash')->nullable(); // File hash for integrity
            $table->timestamps();

            // Foreign keys
            $table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');

            // Indexes
            $table->index(['email_id', 'is_inline']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_attachments');
    }
}
