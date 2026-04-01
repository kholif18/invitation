<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy($request->sort ?? 'created_at', $request->order ?? 'desc')
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'status' => 'required|in:active,inactive,banned',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['password'] = ($request->password);

        unset($validated['password_confirmation']);

        // ✅ Handle upload avatar
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');

            if ($avatar->isValid()) {
                $filename = time() . '_' . $avatar->getClientOriginalName();

                // pastikan folder ada
                Storage::disk('public')->putFileAs('avatars', $avatar, $filename);

                $validated['avatar'] = $filename;
            }
        }

        $user = User::create($validated);

        // (optional) activity log
        if (auth()->check()) {
            auth()->user()->recordActivity('create', 'user', "Created user {$user->name}");
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::with('activityLogs')->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,banned',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['avatar', 'new_password', 'new_password_confirmation']);

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::delete('public/avatars/' . $user->avatar);
            }
            
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $avatar->getClientOriginalName();
            $avatar->storeAs('public/avatars', $filename);
            $data['avatar'] = $filename;
        }

        // Handle password change if provided
        if ($request->filled('new_password')) {
            $request->validate([
                'new_password' => ['required', 'confirmed', Password::min(8)],
            ]);
            
            // Save to password history - AKTIFKAN KEMBALI
            $user->passwordHistory()->create([
                'password' => $user->password
            ]);
            
            $data['password'] = Hash::make($request->new_password);
        }

        $user->update($data);

        // Record activity - AKTIFKAN KEMBALI
        if (auth()->check()) {
            auth()->user()->recordActivity('update', 'user', "Updated user {$user->name}");
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Don't allow deleting own account
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete your own account'
            ], 403);
        }

        $userName = $user->name;
        
        // Record activity BEFORE deleting (so we have user info)
        if (auth()->check()) {
            auth()->user()->recordActivity('delete', 'user', "Deleted user {$userName}");
        }
        
        $user->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Save to password history - AKTIFKAN KEMBALI
        $user->passwordHistory()->create([
            'password' => $user->password
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Record activity - AKTIFKAN KEMBALI
        if (auth()->check()) {
            auth()->user()->recordActivity('password_change', 'user', "Changed password for {$user->name}");
        }

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        $usersToDelete = User::whereIn('id', $request->ids)
            ->where('id', '!=', auth()->id())
            ->get();
            
        $count = $usersToDelete->count();
        
        // Record activity BEFORE deleting
        if (auth()->check() && $count > 0) {
            $userNames = $usersToDelete->pluck('name')->implode(', ');
            auth()->user()->recordActivity('bulk_delete', 'user', "Deleted {$count} users: {$userNames}");
        }
        
        // Delete users
        User::whereIn('id', $request->ids)
            ->where('id', '!=', auth()->id())
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "{$count} users deleted successfully"
        ]);
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $users = User::query()
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=users_' . date('Y-m-d') . '.csv',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            
            // Headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Status', 'Last Login', 'Created At']);
            
            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->status,
                    $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '-',
                    $user->created_at->format('d M Y')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}