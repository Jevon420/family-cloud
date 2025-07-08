<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordChangeNotification;
use App\Services\EmailConfigurationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the password change form.
     *
     * @return \Illuminate\View\View
     */
    public function showChangeForm()
    {
        return view('auth.password-change');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
            'password_change_required' => false,
        ]);

        EmailConfigurationService::configureSupportEmail();
        Mail::to($user->email)->send(new PasswordChangeNotification($user));

        return redirect()->intended('/family')
                         ->with('success', 'Password changed successfully!');
    }
}
