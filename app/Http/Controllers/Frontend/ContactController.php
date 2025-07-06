<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\Page;

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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message. We will get back to you soon!'
            ]);
        }

        return redirect()->route('contact')->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
