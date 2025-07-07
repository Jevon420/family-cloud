<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\EmailConfiguration;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;

class ContactController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'contact')->first();

        if (!$page) {
            $page = new Page();
        }

        return view('contact.index', compact('page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'g-recaptcha-response' => 'sometimes|required',
        ]);

        $contactMessage = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Fetch email configurations for support and contact
        $emailConfigs = EmailConfiguration::whereIn('id', [1, 3])->get();

        // Fetch users with roles Developer and Global Admin for CC
        $ccUsers = User::role(['Developer', 'Global Admin'])->pluck('email')->toArray();

        // Send email to support and contact
        foreach ($emailConfigs as $config) {
            config([
                'mail.mailers.smtp.host' => $config->smtp_host,
                'mail.mailers.smtp.port' => $config->smtp_port,
                'mail.mailers.smtp.encryption' => $config->smtp_encryption,
                'mail.mailers.smtp.username' => $config->smtp_username ?: $config->email,
                'mail.mailers.smtp.password' => $config->password,
                'mail.from.address' => $config->email,
                'mail.from.name' => $config->name,
            ]);

            $mailable = new CustomEmail(
                $request->subject,
                $request->message,
                $request->email,
                $request->name
            );

            Mail::to([$config->email])->cc($ccUsers)->send($mailable);
        }

        // Send response email to sender
        $responseMailable = new CustomEmail(
            'Thank you for contacting us',
            '<p>Dear ' . $request->name . ',</p><p>Thank you for reaching out to us. We have received your message and will get back to you shortly.</p><p>Best regards,<br>Family Cloud Team</p>',
            'no-reply@jevonredhead.com',
            'Family Cloud'
        );

        Mail::to($request->email)->send($responseMailable);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message. We will get back to you soon!'
            ]);
        }

        return redirect()->route('contact')->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
