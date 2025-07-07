<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Notifications\RegistrationRequestNotification;
use App\Notifications\RegistrationRequestReceived;
use App\Notifications\NewRegistrationSiteNotification;
use Illuminate\Support\Facades\Notification;

class CustomRegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // Notify the user that their registration is pending approval
        $user->notify(new RegistrationRequestReceived($user));

        // Notify admins and developers about the new registration
        $admins = User::role(['Global Admin', 'Developer'])->get();
        Notification::send($admins, new RegistrationRequestNotification($user));

        // Notify the site email
        $siteEmail = config('mail.from.address');
        Notification::route('mail', $siteEmail)
            ->notify(new NewRegistrationSiteNotification($user));

        return redirect()->route('login')
            ->with('status', 'Your registration request has been received. You will be notified by email once an administrator approves your account.');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            // Set a temporary password hash - will be replaced when approved
            'password' => Hash::make(uniqid()),
            'status' => 'pending',
            'password_change_required' => true,
        ]);
    }
}
