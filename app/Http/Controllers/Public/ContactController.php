<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\EmailConfiguration;
use App\Models\User;
use App\Services\EmailConfigurationService;
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

        // Configure mail to use support email (which we know is working)
        $supportEmailConfig = EmailConfigurationService::configureSupportEmail();

        // Fetch users with roles Developer and Global Admin for CC
        $ccUsers = User::role(['Developer', 'Global Admin'])->pluck('email')->toArray();

        if ($supportEmailConfig) {
            $mailable = new CustomEmail(
                $request->subject,
                $request->message,
                $request->email,
                $request->name
            );

            // Send to both support and contact emails with CC to admins/developers
            Mail::to(['support@jevonredhead.com', 'contact@jevonredhead.com'])
                ->cc($ccUsers)
                ->send($mailable);

            // Send response email to sender
            $responseMailable = new CustomEmail(
                'Thank you for contacting us',
                '<p>Dear ' . $request->name . ',</p><p>Thank you for reaching out to us. We have received your message and will get back to you shortly.</p><p>Best regards,<br>Family Cloud Team</p>',
                $supportEmailConfig->email,
                'Family Cloud'
            );

            Mail::to($request->email)->send($responseMailable);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message. We will get back to you soon!'
            ]);
        }

        return redirect()->route('contact')->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
