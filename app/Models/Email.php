<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email_configuration_id',
        'message_id',
        'type',
        'status',
        'from_email',
        'from_name',
        'to_emails',
        'cc_emails',
        'bcc_emails',
        'reply_to',
        'subject',
        'sent_at',
        'body_html',
        'body_text',
        'headers',
        'has_attachments',
        'is_important',
        'is_spam',
        'priority',
        'thread_id',
        'in_reply_to',
        'references',
        'created_by',
        'updated_by',
        'error_message',
    ];

    protected $casts = [
        'to_emails' => 'array',
        'cc_emails' => 'array',
        'bcc_emails' => 'array',
        'headers' => 'array',
        'sent_at' => 'datetime',
        'has_attachments' => 'boolean',
        'is_important' => 'boolean',
        'is_spam' => 'boolean',
        'priority' => 'integer',
    ];

    // Relationships
    public function emailConfiguration()
    {
        return $this->belongsTo(EmailConfiguration::class);
    }

    public function attachments()
    {
        return $this->hasMany(EmailAttachment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeReceived($query)
    {
        return $query->where('type', 'received');
    }

    public function scopeSent($query)
    {
        return $query->where('type', 'sent');
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }

    public function scopeByThread($query, $threadId)
    {
        return $query->where('thread_id', $threadId);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    public function markAsUnread()
    {
        $this->update(['status' => 'unread']);
    }

    public function markAsImportant()
    {
        $this->update(['is_important' => true]);
    }

    public function markAsSpam()
    {
        $this->update(['is_spam' => true]);
    }

    public function getToEmailsStringAttribute()
    {
        return is_array($this->to_emails) ? implode(', ', $this->to_emails) : $this->to_emails;
    }

    public function getCcEmailsStringAttribute()
    {
        return is_array($this->cc_emails) ? implode(', ', $this->cc_emails) : $this->cc_emails;
    }

    public function getBccEmailsStringAttribute()
    {
        return is_array($this->bcc_emails) ? implode(', ', $this->bcc_emails) : $this->bcc_emails;
    }

    public function getPreviewTextAttribute()
    {
        $text = strip_tags($this->body_text ?: $this->body_html);
        return strlen($text) > 100 ? substr($text, 0, 100) . '...' : $text;
    }
}
