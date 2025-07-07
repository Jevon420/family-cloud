<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\EmailConfiguration;
use App\Models\EmailAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('can:manage-emails');
    }

    /**
     * Display a listing of emails
     */
    public function index(Request $request)
    {
        $query = Email::with(['emailConfiguration', 'attachments'])
            ->notSpam()
            ->latest('sent_at');

        // Filter by email configuration
        if ($request->filled('configuration_id')) {
            $query->where('email_configuration_id', $request->configuration_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('from_email', 'like', "%{$search}%")
                  ->orWhere('from_name', 'like', "%{$search}%")
                  ->orWhere('body_text', 'like', "%{$search}%");
            });
        }

        $emails = $query->paginate(20);
        $configurations = EmailConfiguration::active()->get();

        return view('admin.email.index', compact('emails', 'configurations'));
    }

    /**
     * Display the specified email
     */
    public function show(Email $email)
    {
        $email->load(['emailConfiguration', 'attachments']);

        // Mark as read if it's unread
        if ($email->status === 'unread') {
            $email->markAsRead();
        }

        return view('admin.email.show', compact('email'));
    }

    /**
     * Compose new email
     */
    public function compose()
    {
        $configurations = EmailConfiguration::active()->outgoing()->get();

        // Pre-select the working configuration (Support Email)
        $defaultConfigId = null;
        $workingConfig = $configurations->firstWhere('email', 'support@jevonredhead.com');
        if ($workingConfig) {
            $defaultConfigId = $workingConfig->id;
        }

        return view('admin.email.compose', compact('configurations', 'defaultConfigId'));
    }

    /**
     * Send email
     */
    public function send(Request $request)
    {
        $request->validate([
            'configuration_id' => 'required|exists:email_configurations,id',
            'to' => 'required|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        $configuration = EmailConfiguration::findOrFail($request->configuration_id);

        if (!$configuration->canSendEmail()) {
            return redirect()->back()
                ->with('error', 'Selected configuration cannot send emails.');
        }

        // Parse email addresses
        $toEmails = $this->parseEmails($request->to);
        $ccEmails = $request->cc ? $this->parseEmails($request->cc) : [];
        $bccEmails = $request->bcc ? $this->parseEmails($request->bcc) : [];

        // Create email record
        $email = Email::create([
            'email_configuration_id' => $configuration->id,
            'message_id' => uniqid() . '@' . $configuration->email,
            'type' => 'sent',
            'status' => 'sending',
            'from_email' => $configuration->email,
            'from_name' => $configuration->from_name,
            'to_emails' => $toEmails,
            'cc_emails' => $ccEmails,
            'bcc_emails' => $bccEmails,
            'subject' => $request->subject,
            'body_html' => $request->body,
            'body_text' => strip_tags($request->body),
            'sent_at' => now(),
            'has_attachments' => $request->hasFile('attachments'),
            'created_by' => auth()->id(),
        ]);

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $this->storeAttachment($email, $file);
                $attachments[] = $file;
            }
        }

        try {
            // Configure mail settings dynamically
            $smtpConfig = $configuration->getSmtpConfig();

            // Set mailer configuration
            config([
                'mail.mailers.smtp.host' => $smtpConfig['host'],
                'mail.mailers.smtp.port' => $smtpConfig['port'],
                'mail.mailers.smtp.encryption' => $smtpConfig['encryption'],
                'mail.mailers.smtp.username' => $smtpConfig['username'],
                'mail.mailers.smtp.password' => $smtpConfig['password'],
            ]);

            // Send email using Laravel Mail
            $mailable = new \App\Mail\CustomEmail(
                $request->subject,
                $request->body,
                $configuration->email,
                $configuration->from_name,
                $attachments
            );

            // Send to recipients
            foreach ($toEmails as $toEmail) {
                \Mail::to($toEmail)->send($mailable);
            }

            // Send CC
            if (!empty($ccEmails)) {
                foreach ($ccEmails as $ccEmail) {
                    \Mail::to($ccEmail)->send($mailable);
                }
            }

            // Send BCC
            if (!empty($bccEmails)) {
                foreach ($bccEmails as $bccEmail) {
                    \Mail::to($bccEmail)->send($mailable);
                }
            }

            // Update email status to sent
            $email->update(['status' => 'sent']);

            return redirect()->route('admin.emails.index')
                ->with('success', 'Email sent successfully to ' . count($toEmails) . ' recipient(s).');

        } catch (\Exception $e) {
            // Update email status to failed
            $email->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            // Log the error for debugging
            Log::error('Email sending failed', [
                'email_id' => $email->id,
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to send email: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mark email as read
     */
    public function markAsRead(Email $email)
    {
        $email->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark email as unread
     */
    public function markAsUnread(Email $email)
    {
        $email->markAsUnread();

        return response()->json(['success' => true]);
    }

    /**
     * Mark email as important
     */
    public function markAsImportant(Email $email)
    {
        $email->markAsImportant();

        return response()->json(['success' => true]);
    }

    /**
     * Mark email as spam
     */
    public function markAsSpam(Email $email)
    {
        $email->markAsSpam();

        return response()->json(['success' => true]);
    }

    /**
     * Archive email
     */
    public function archive(Email $email)
    {
        $email->update(['status' => 'archived']);

        return response()->json(['success' => true]);
    }

    /**
     * Delete email
     */
    public function destroy(Email $email)
    {
        $email->delete();

        return redirect()->route('admin.emails.index')
            ->with('success', 'Email deleted successfully.');
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(EmailAttachment $attachment)
    {
        if (!Storage::exists($attachment->storage_path)) {
            abort(404, 'Attachment not found.');
        }

        return Storage::download($attachment->storage_path, $attachment->filename);
    }

    /**
     * Get emails via AJAX
     */
    public function getEmails(Request $request)
    {
        $query = Email::with(['emailConfiguration', 'attachments'])
            ->notSpam()
            ->latest('sent_at');

        // Apply filters
        if ($request->filled('configuration_id')) {
            $query->where('email_configuration_id', $request->configuration_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('from_email', 'like', "%{$search}%")
                  ->orWhere('from_name', 'like', "%{$search}%");
            });
        }

        $emails = $query->paginate(20);

        return response()->json($emails);
    }

    /**
     * Parse email addresses from string
     */
    private function parseEmails($emailString)
    {
        $emails = [];
        $parts = explode(',', $emailString);

        foreach ($parts as $part) {
            $email = trim($part);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $email;
            }
        }

        return $emails;
    }

    /**
     * Store attachment
     */
    private function storeAttachment(Email $email, $file)
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $storedName = uniqid() . '_' . $originalName;
        $path = $file->storeAs('email_attachments', $storedName, 'private');

        EmailAttachment::create([
            'email_id' => $email->id,
            'filename' => $originalName,
            'stored_filename' => $storedName,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'storage_path' => $path,
            'hash' => hash_file('sha256', $file->getPathname()),
        ]);
    }
}
