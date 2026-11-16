<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    /**
     * Display the Administrator Profile page.
     */
    public function edit(): View
    {
        $admin = Auth::user();

        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update the Administrator Profile information, avatar, and password.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $admin */
        $admin = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($admin->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:25',
                'regex:/^(\+?62|0)[0-9\s\-\(\)]+$/',
            ],
            'avatar' => [
                'nullable',
                'file',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
            'current_password' => [
                'nullable',
                'string',
                'required_with:new_password',
            ],
            'new_password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'required_with:current_password',
                'different:current_password',
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email admin wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'phone.regex' => 'Format nomor telepon tidak valid. Gunakan format diawali 08 atau +62.',
            'avatar.image' => 'Berkas foto profil harus berupa gambar.',
            'avatar.mimes' => 'Format foto profil harus JPG atau PNG.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
            'current_password.required_with' => 'Kata sandi saat ini wajib diisi untuk mengubah kata sandi.',
            'new_password.required_with' => 'Kata sandi baru wajib diisi jika kata sandi saat ini dimasukkan.',
            'new_password.min' => 'Kata sandi baru minimal terdiri dari 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'new_password.different' => 'Kata sandi baru harus berbeda dari kata sandi saat ini.',
        ]);

        $updatedFields = [];
        $passwordChanged = false;
        $avatarChanged = false;

        // 1. Password Verification & Update
        if (! empty($validated['current_password'])) {
            if (! Hash::check($validated['current_password'], $admin->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Kata sandi saat ini yang Anda masukkan salah.',
                ]);
            }

            $admin->password = Hash::make($validated['new_password']);
            $passwordChanged = true;
            $updatedFields[] = 'password';
        }

        // 2. Avatar Upload
        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');

            // Delete old avatar if exists and not external URL
            if ($admin->avatar && ! str_starts_with($admin->avatar, 'http') && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
                @unlink(public_path('storage/'.$admin->avatar));
            }

            $storedPath = $avatarFile->store('avatars', 'public');

            // Mirror to public directory for direct serving
            $publicTargetDir = public_path('storage/avatars');
            if (! is_dir($publicTargetDir)) {
                @mkdir($publicTargetDir, 0755, true);
            }
            @file_put_contents(public_path('storage/'.$storedPath), file_get_contents($avatarFile->getRealPath()));

            $admin->avatar = $storedPath;
            $avatarChanged = true;
            $updatedFields[] = 'avatar';
        }

        // 3. Information Fields
        if ($admin->name !== $validated['name']) {
            $admin->name = trim($validated['name']);
            $admin->full_name = trim($validated['name']);
            $updatedFields[] = 'name';
        }

        if ($admin->email !== $validated['email']) {
            $admin->email = trim($validated['email']);
            $updatedFields[] = 'email';
        }

        $newPhone = ! empty($validated['phone']) ? trim($validated['phone']) : null;
        if ($admin->phone !== $newPhone) {
            $admin->phone = $newPhone;
            $updatedFields[] = 'phone';
        }

        $admin->save();

        // 4. Centralized Activity Logging (Never log passwords or tokens)
        if (! empty($updatedFields)) {
            $descParts = [];
            if (in_array('name', $updatedFields) || in_array('email', $updatedFields) || in_array('phone', $updatedFields)) {
                $descParts[] = 'informasi akun';
            }
            if ($avatarChanged) {
                $descParts[] = 'foto profil';
            }
            if ($passwordChanged) {
                $descParts[] = 'kata sandi';
            }
            $descString = implode(', ', $descParts);

            ActivityLog::log(
                'update',
                "Administrator {$admin->name} ({$admin->email}) memperbarui {$descString}.",
                [
                    'target_type' => 'user',
                    'target_id' => $admin->id,
                    'level' => 'info',
                    'result' => 'success',
                    'metadata' => [
                        'updated_fields' => $updatedFields,
                        'password_changed' => $passwordChanged,
                        'avatar_changed' => $avatarChanged,
                    ],
                ]
            );
        }

        return redirect()->route('admin.profile')
            ->with('success', 'Profil Administrator berhasil diperbarui.');
    }
}
