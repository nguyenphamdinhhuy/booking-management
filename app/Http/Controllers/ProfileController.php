<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';

        return view('profile.edit', [
            'user' => $user,
            'isAdmin' => $isAdmin,
            'layout' => $isAdmin ? 'admin.layouts.master' : 'layouts.app',
        ]);
    }



    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';

        // Validation rules based on role
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ];

        // Add admin-specific fields
        if ($isAdmin) {
            $rules = array_merge($rules, [
                'phone' => ['nullable', 'string', 'max:20'],
                'birth_date' => ['nullable', 'date'],
                'address' => ['nullable', 'string', 'max:500'],
            ]);
        }

        $rules['avatar'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];

        $validated = $request->validate($rules);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && !str_contains($user->avatar, 'unsplash.com')) {
                Storage::delete('public/' . $user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->save();
        }

        $redirectRoute = $isAdmin ? 'admin.profile' : 'profile.edit';
        return Redirect::route($redirectRoute)->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the password change form.
     */
    public function password(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';

        return view('profile.password', [
            'user' => $user,
            'isAdmin' => $isAdmin,
            'layout' => $isAdmin ? 'admin.layouts.master' : 'layouts.app',
        ]);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $user = $request->user();
        $isAdmin = $user->role === 'admin';
        $redirectRoute = $isAdmin ? 'admin.password' : 'profile.password';

        return back()->with('status', 'password-updated');
    }

    /**
     * Display the user's orders.
     */
    public function orders(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';

        // TODO: Implement orders logic
        return view('profile.orders', [
            'user' => $user,
            'isAdmin' => $isAdmin,
            // 'layout' => $isAdmin ? 'admin.layouts.master' : 'layouts.app',
            'orders' => [], // Add orders data here
        ]);
    }

    /**
     * Display the user's favorites.
     */
    public function favorites(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';

        // TODO: Implement favorites logic
        return view('profile.favorites', [
            'user' => $user,
            'isAdmin' => $isAdmin,
            'favorites' => [], // Add favorites data here
        ]);
    }

    /**
     * Display the user's settings.
     */
    public function settings(Request $request): View
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';

        return view('profile.settings', [
            'user' => $user,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Update user settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $user = $request->user();
        $isAdmin = $user->role === 'admin';

        $rules = [
            'notification_email' => ['boolean'],
            'notification_sms' => ['boolean'],
            'language' => ['string', 'in:vi,en'],
            'timezone' => ['string'],
        ];

        // Add admin-specific settings
        if ($isAdmin) {
            $rules = array_merge($rules, [
                'site_name' => ['required', 'string', 'max:255'],
                'site_description' => ['nullable', 'string', 'max:500'],
                'contact_email' => ['required', 'email'],
                'contact_phone' => ['nullable', 'string', 'max:20'],
                'maintenance_mode' => ['boolean'],
                'registration_enabled' => ['boolean'],
                'email_verification_required' => ['boolean'],
            ]);
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        $redirectRoute = $isAdmin ? 'admin.settings' : 'profile.settings';
        return back()->with('status', 'settings-updated');
    }

    /**
     * Display system logs (Admin only)
     */
    public function logs(Request $request): View
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        // Get logs from storage/logs
        $logFiles = glob(storage_path('logs/*.log'));
        $logs = [];

        foreach ($logFiles as $logFile) {
            $logs[] = [
                'name' => basename($logFile),
                'size' => filesize($logFile),
                'modified' => filemtime($logFile),
            ];
        }

        return view('profile.logs', [
            'user' => $user,
            'isAdmin' => true,
            'logs' => $logs,
        ]);
    }

    /**
     * Display backup management (Admin only)
     */
    public function backup(Request $request): View
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        // Get backup files list
        $backupPath = storage_path('backups');
        $backups = [];

        if (is_dir($backupPath)) {
            $backupFiles = glob($backupPath . '/*.sql');
            foreach ($backupFiles as $backupFile) {
                $backups[] = [
                    'name' => basename($backupFile),
                    'size' => filesize($backupFile),
                    'created' => filemtime($backupFile),
                ];
            }
        }

        return view('profile.backup', [
            'user' => $user,
            'isAdmin' => true,
            'backups' => $backups,
        ]);
    }

    /**
     * Create database backup (Admin only)
     */
    public function createBackup(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        // TODO: Implement database backup
        return back()->with('status', 'backup-created');
    }

    /**
     * Display user management (Admin only)
     */
    public function users(Request $request): View
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $users = User::orderBy('created_at', 'desc')
            ->paginate(15);

        return view('profile.users', [
            'user' => $user,
            'isAdmin' => true,
            'users' => $users,
        ]);
    }

    /**
     * Update user status (Admin only)
     */
    public function updateUserStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,locked'],
        ]);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Không thể đổi trạng thái chính mình.');
        }

        $user->status = $validated['status'];
        $user->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công.');
    }





    /**
     * Delete user (Admin only)
     */
    // public function deleteUser(Request $request, User $targetUser): RedirectResponse
    // {
    //     $user = $request->user();

    //     if ($user->role !== 'admin') {
    //         abort(403);
    //     }

    //     // Prevent admin from deleting themselves
    //     if ($targetUser->id === $user->id) {
    //         return back()->withErrors(['error' => 'Không thể xóa tài khoản của chính mình']);
    //     }

    //     $targetUser->delete();

    //     return back()->with('status', 'user-deleted');
    // }

    /**
     * Display statistics (Admin only)
     */
    public function statistics(Request $request): View
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        // Get basic statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('profile.statistics', [
            'user' => $user,
            'isAdmin' => true,
            'stats' => $stats,
        ]);
    }
    public function updatesetting(Request $request)
    {
        // Nếu bạn lưu setting vào bảng settings (dạng key-value)
        setting(['enable_email_verification' => $request->has('enable_email_verification')]);
        setting()->save();

        return back()->with('success', 'Đã cập nhật cấu hình!');
    }
    public function toggleEmailVerification(User $user)
    {
        // Kiểm tra quyền nếu cần (admin)

        if ($user->email_verified_at) {
            $user->email_verified_at = null; // Tắt xác thực
        } else {
            $user->email_verified_at = now(); // Bật xác thực (coi như đã xác thực)
        }

        $user->save();

        return back()->with('success', 'Cập nhật trạng thái xác thực email thành công!');
    }   


}
