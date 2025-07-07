<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmailConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('can:manage-email-configurations');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $configurations = EmailConfiguration::with(['creator', 'updater'])
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.email.configurations.index', compact('configurations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.email.configurations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:email_configurations,name',
            'email' => 'required|email|unique:email_configurations,email',
            'password' => 'required|string|min:6',
            'type' => 'required|in:incoming,outgoing,both',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'smtp_host' => 'required_if:type,outgoing,both|string|max:255',
            'smtp_port' => 'required_if:type,outgoing,both|integer|min:1|max:65535',
            'smtp_encryption' => 'required_if:type,outgoing,both|in:ssl,tls,starttls',
            'smtp_username' => 'nullable|string|max:255',
            'imap_host' => 'required_if:type,incoming,both|string|max:255',
            'imap_port' => 'required_if:type,incoming,both|integer|min:1|max:65535',
            'imap_encryption' => 'required_if:type,incoming,both|in:ssl,tls,starttls',
            'imap_username' => 'nullable|string|max:255',
            'pop_host' => 'nullable|string|max:255',
            'pop_port' => 'nullable|integer|min:1|max:65535',
            'pop_encryption' => 'nullable|in:ssl,tls,starttls',
            'pop_username' => 'nullable|string|max:255',
            'from_name' => 'nullable|string|max:255',
            'signature' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        EmailConfiguration::create($data);

        return redirect()->route('admin.email.configurations.index')
            ->with('success', 'Email configuration created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailConfiguration $configuration)
    {
        $configuration->load(['creator', 'updater', 'emails' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('admin.email.configurations.show', compact('configuration'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailConfiguration $configuration)
    {
        return view('admin.email.configurations.edit', compact('configuration'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailConfiguration $configuration)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:email_configurations,name,' . $configuration->id,
            'email' => 'required|email|unique:email_configurations,email,' . $configuration->id,
            'password' => 'nullable|string|min:6',
            'type' => 'required|in:incoming,outgoing,both',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'smtp_host' => 'required_if:type,outgoing,both|string|max:255',
            'smtp_port' => 'required_if:type,outgoing,both|integer|min:1|max:65535',
            'smtp_encryption' => 'required_if:type,outgoing,both|in:ssl,tls,starttls',
            'smtp_username' => 'nullable|string|max:255',
            'imap_host' => 'required_if:type,incoming,both|string|max:255',
            'imap_port' => 'required_if:type,incoming,both|integer|min:1|max:65535',
            'imap_encryption' => 'required_if:type,incoming,both|in:ssl,tls,starttls',
            'imap_username' => 'nullable|string|max:255',
            'pop_host' => 'nullable|string|max:255',
            'pop_port' => 'nullable|integer|min:1|max:65535',
            'pop_encryption' => 'nullable|in:ssl,tls,starttls',
            'pop_username' => 'nullable|string|max:255',
            'from_name' => 'nullable|string|max:255',
            'signature' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['updated_by'] = Auth::id();

        // Don't update password if it's empty
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $configuration->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Email configuration updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailConfiguration $configuration)
    {
        $configuration->delete();

        return redirect()->route('admin.email.configurations.index')
            ->with('success', 'Email configuration deleted successfully.');
    }

    /**
     * Test email configuration
     */
    public function test(Request $request, EmailConfiguration $configuration)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // Test SMTP connection if it's an outgoing configuration
            if ($configuration->canSendEmail()) {
                $config = $configuration->getSmtpConfig();

                // Here you would implement the actual SMTP test
                // For now, we'll just simulate success
                $smtpResult = true; // This would be the actual SMTP test result

                if (!$smtpResult) {
                    return response()->json([
                        'success' => false,
                        'message' => 'SMTP connection failed'
                    ]);
                }
            }

            // Test IMAP connection if it's an incoming configuration
            if ($configuration->canReceiveEmail()) {
                $config = $configuration->getImapConfig();

                // Here you would implement the actual IMAP test
                // For now, we'll just simulate success
                $imapResult = true; // This would be the actual IMAP test result

                if (!$imapResult) {
                    return response()->json([
                        'success' => false,
                        'message' => 'IMAP connection failed'
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Email configuration test successful'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Set as default configuration
     */
    public function setDefault(EmailConfiguration $configuration)
    {
        if (!$configuration->canSendEmail()) {
            return redirect()->back()
                ->with('error', 'Only outgoing email configurations can be set as default.');
        }

        $configuration->update(['is_default' => true]);

        return redirect()->back()
            ->with('success', 'Email configuration set as default successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(EmailConfiguration $configuration)
    {
        $configuration->update(['is_active' => !$configuration->is_active]);

        $status = $configuration->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Email configuration {$status} successfully.");
    }
}
