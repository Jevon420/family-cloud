<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\AccountApprovedNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserApprovalController extends Controller
{
    /**
     * Approve a user registration request
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        // Generate a random password
        $password = Str::random(10);
        
        // Update user status
        $user->update([
            'status' => 'active',
            'password' => Hash::make($password),
            'password_change_required' => true
        ]);
        
        // Assign the Family role if not already assigned
        if (!$user->hasRole('Family')) {
            $user->assignRole('Family');
        }
        
        // Send notification with credentials
        $user->notify(new AccountApprovedNotification($user, $password));
        
        return redirect()->route('admin.settings.users')
                         ->with('success', 'User has been approved and notified.');
    }
    
    /**
     * Reject a user registration request
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);
        
        // Delete the user account
        $user->delete();
        
        return redirect()->route('admin.settings.users')
                         ->with('success', 'User registration has been rejected.');
    }
}
