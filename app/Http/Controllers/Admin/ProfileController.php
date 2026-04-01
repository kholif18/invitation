<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = $request->user();
        
        $user->fill($request->only(['name', 'email', 'phone', 'address']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Record activity
        $user->recordActivity('update', 'profile', 'Updated profile information');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $user
            ]);
        }

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update profile photo
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = $request->user();

        // Delete old avatar
        if ($user->avatar) {
            Storage::delete('public/avatars/' . $user->avatar);
        }

        // Upload new avatar
        $avatar = $request->file('avatar');
        $filename = time() . '_' . $avatar->getClientOriginalName();
        $avatar->storeAs('public/avatars', $filename);
        
        $user->update(['avatar' => $filename]);

        // Record activity
        $user->recordActivity('update', 'profile', 'Updated profile photo');

        return response()->json([
            'success' => true,
            'message' => 'Profile photo updated successfully',
            'avatar_url' => $user->avatar_url
        ]);
    }

    /**
     * Update user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = $request->user();

        // Save to password history
        $user->passwordHistory()->create([
            'password' => $user->password
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Record activity
        $user->recordActivity('password_change', 'profile', 'Changed password');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        }

        return redirect()->route('admin.profile.edit')->with('success', 'Password updated successfully.');
    }

    /**
     * Update user's settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'timezone' => 'required|string|timezone',
            'notifications' => 'boolean',
        ]);

        $user = $request->user();
        
        $settings = $user->settings ?? [];
        $settings['notifications'] = $request->notifications ?? false;
        
        $user->update([
            'timezone' => $request->timezone,
            'settings' => $settings,
        ]);

        // Record activity
        $user->recordActivity('update', 'profile', 'Updated settings');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully'
            ]);
        }

        return redirect()->route('admin.profile.edit')->with('success', 'Settings updated successfully.');
    }

    /**
     * Display activity log
     */
    public function activityLog(Request $request): View
    {
        $logs = $request->user()
            ->activityLogs()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.profile.activity', compact('logs'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Record activity before deletion
        $user->recordActivity('delete', 'profile', 'Deleted account');

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully'
            ]);
        }

        return redirect('/');
    }
}