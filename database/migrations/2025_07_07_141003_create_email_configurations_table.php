<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Friendly name for the email config
            $table->string('email')->unique(); // Email address
            $table->string('password')->nullable(); // Encrypted password
            $table->string('type')->default('both'); // 'incoming', 'outgoing', 'both'
            $table->boolean('is_default')->default(false); // Default email for outgoing
            $table->boolean('is_active')->default(true); // Active/inactive

            // SMTP Settings (Outgoing)
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_encryption')->nullable(); // ssl, tls, starttls
            $table->string('smtp_username')->nullable();

            // IMAP Settings (Incoming)
            $table->string('imap_host')->nullable();
            $table->integer('imap_port')->nullable();
            $table->string('imap_encryption')->nullable(); // ssl, tls, starttls
            $table->string('imap_username')->nullable();

            // POP3 Settings (Incoming)
            $table->string('pop_host')->nullable();
            $table->integer('pop_port')->nullable();
            $table->string('pop_encryption')->nullable(); // ssl, tls, starttls
            $table->string('pop_username')->nullable();

            // Additional Settings
            $table->string('from_name')->nullable(); // Display name
            $table->text('signature')->nullable(); // Email signature
            $table->json('settings')->nullable(); // Additional JSON settings

            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['type', 'is_active']);
            $table->index(['is_default', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_configurations');
    }
}
