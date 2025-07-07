<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_configuration_id');
            $table->string('message_id')->unique(); // Email message ID
            $table->string('type')->default('received'); // 'received', 'sent', 'draft'
            $table->string('status')->default('unread'); // 'unread', 'read', 'archived', 'deleted'

            // Email Headers
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->text('to_emails'); // JSON array of recipients
            $table->text('cc_emails')->nullable(); // JSON array of CC recipients
            $table->text('bcc_emails')->nullable(); // JSON array of BCC recipients
            $table->string('reply_to')->nullable();
            $table->string('subject');
            $table->timestamp('sent_at')->nullable();

            // Email Content
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            $table->json('headers')->nullable(); // Full email headers as JSON

            // Flags and Status
            $table->boolean('has_attachments')->default(false);
            $table->boolean('is_important')->default(false);
            $table->boolean('is_spam')->default(false);
            $table->integer('priority')->default(3); // 1-5 priority

            // Threading
            $table->string('thread_id')->nullable();
            $table->string('in_reply_to')->nullable();
            $table->text('references')->nullable();

            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('email_configuration_id')->references('id')->on('email_configurations')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['email_configuration_id', 'type', 'status']);
            $table->index(['sent_at', 'status']);
            $table->index(['thread_id']);
            $table->index(['from_email', 'sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
}
