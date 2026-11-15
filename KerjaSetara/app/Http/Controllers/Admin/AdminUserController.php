<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CompanyProfile;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use App\Models\UserAccessibilityPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display the User Management panel with search, filters, and pagination.
     */
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role', 'all');
        $statusFilter = $request->query('status', 'all');
        $search = trim($request->query('search', ''));

        $query = User::with(['companyProfile', 'accessibilityPreference'])->latest();

        // 1. Role Filter
        if ($roleFilter !== 'all') {
            if ($roleFilter === 'kandidat' || $roleFilter === 'user') {
                $query->whereIn('role', ['kandidat', 'user']);
            } elseif ($roleFilter === 'hrd' || $roleFilter === 'perusahaan' || $roleFilter === 'company') {
                $query->whereIn('role', ['hrd', 'company']);
            } elseif ($roleFilter === 'admin') {
                $query->where('role', 'admin');
            }
        }

        // 2. Status Filter
        if ($statusFilter === 'active') {
            $query->where(function ($q) {
                $q->whereNull('status')->orWhere('status', 'active');
            });
        } elseif ($statusFilter === 'blocked') {
            $query->where('status', 'blocked');
        }

        // 3. Search Filter (nama, email, role, atau ID)
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('role', 'LIKE', "%{$search}%");

                // Check identifier match (e.g. KS-U001, KS-C001, KS-A001 or raw ID)
                if (preg_match('/^KS-[UCA](\d+)$/i', $search, $matches)) {
                    $rawId = (int) $matches[1];
                    $q->orWhere('id', $rawId);
                } elseif (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        $users = $query->paginate(10)->withQueryString();

        $roleCounts = [
            'all' => User::count(),
            'kandidat' => User::whereIn('role', ['kandidat', 'user'])->count(),
            'hrd' => User::whereIn('role', ['hrd', 'company'])->count(),
            'admin' => User::where('role', 'admin')->count(),
        ];

        $statusCounts = [
            'all' => User::count(),
            'active' => User::where(function ($q) {
                $q->whereNull('status')->orWhere('status', 'active');
            })->count(),
            'blocked' => User::where('status', 'blocked')->count(),
        ];

        return view('admin.users.index', compact(
            'users',
            'roleFilter',
            'statusFilter',
            'search',
            'roleCounts',
            'statusCounts'
        ));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'in:kandidat,user,hrd,company,perusahaan,admin'],
            'status' => ['required', 'string', 'in:active,blocked'],
            'password' => ['nullable', 'string', 'min:8'],
            'must_change_password' => ['nullable', 'boolean'],
            'accessibility_category' => ['nullable', 'string', 'in:fisik,komunikasi,digital,neurodiverse'],
        ], [
            'name.required' => 'Nama lengkap atau nama perusahaan wajib diisi.',
            'email.required' => 'Alamat email pengguna wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar di sistem.',
            'role.required' => 'Peran pengguna wajib dipilih.',
            'role.in' => 'Peran pengguna tidak valid.',
            'status.required' => 'Status akun wajib ditentukan.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
        ]);

        // Map label Perusahaan to existing role 'hrd'
        $role = $validated['role'];
        if ($role === 'perusahaan' || $role === 'company') {
            $role = 'hrd';
        } elseif ($role === 'user') {
            $role = 'kandidat';
        }

        // Password handling: if empty, create temporary secure password
        $rawPassword = $validated['password'] ?? Str::password(12, true, true, false, false);
        $isTempPassword = empty($validated['password']) || $request->boolean('must_change_password');

        $user = User::create([
            'name' => $validated['name'],
            'full_name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($rawPassword),
            'role' => $role,
            'status' => $validated['status'],
            'must_change_password' => $isTempPassword,
        ]);

        // Auto initialize related profile
        if ($user->isHrd()) {
            CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => $user->name,
                'industry' => 'Teknologi & Layanan',
                'location' => 'Indonesia',
                'description' => 'Profil perusahaan inklusif terdaftar di KerjaSetara.',
                'accessibility_features' => [],
                'employee_count' => 50,
                'is_inclusive_certified' => false,
            ]);
        } elseif ($user->isKandidat()) {
            $category = $request->input('accessibility_category', 'fisik');
            UserAccessibilityPreference::create([
                'user_id' => $user->id,
                'category' => $category,
            ]);
        }

        // Log action to ActivityLog
        ActivityLog::log(
            'create',
            "Administrator membuat akun baru: {$user->name} ({$user->email}) dengan peran {$user->role_label}",
            [
                'target_type' => 'user',
                'target_id' => $user->id,
                'metadata' => [
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'must_change_password' => $user->must_change_password,
                ],
            ]
        );

        $tempMsg = $isTempPassword ? " Kata sandi sementara telah dibuat (Wajib diganti saat login pertama)." : "";

        return redirect()->route('admin.users')
            ->with('success', "Akun pengguna {$user->name} berhasil ditambahkan ke sistem.{$tempMsg}");
    }

    /**
     * Display the specified user as JSON for viewing/detail modal.
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['companyProfile', 'accessibilityPreference']);

        return response()->json([
            'id' => $user->id,
            'identifier' => $user->identifier,
            'name' => $user->name,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'role' => $user->role,
            'role_label' => $user->role_label,
            'status' => $user->status ?? 'active',
            'status_label' => $user->status_label,
            'must_change_password' => (bool) $user->must_change_password,
            'phone' => $user->phone ?? '—',
            'city' => $user->city ?? 'Indonesia',
            'created_at' => $user->created_at->format('d M Y H:i'),
            'updated_at' => $user->updated_at->format('d M Y H:i'),
            'company_name' => $user->companyProfile?->company_name,
            'accessibility_category' => $user->accessibilityPreference?->category,
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', 'in:kandidat,user,hrd,company,perusahaan,admin'],
            'status' => ['nullable', 'string', 'in:active,blocked'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email pengguna wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'role.required' => 'Peran pengguna wajib ditentukan.',
            'role.in' => 'Peran pengguna tidak valid.',
        ]);

        // Normalize status from request (either status string or toggle switch is_active)
        $newStatus = $request->input('status');
        if (! $newStatus) {
            $newStatus = $request->boolean('is_active', true) ? 'active' : 'blocked';
        }

        // Map label Perusahaan to existing role 'hrd'
        $newRole = $validated['role'];
        if ($newRole === 'perusahaan' || $newRole === 'company') {
            $newRole = 'hrd';
        } elseif ($newRole === 'user') {
            $newRole = 'kandidat';
        }

        // Security Check 1: Prevent Admin from blocking themselves
        if (Auth::id() === $user->id && $newStatus === 'blocked') {
            return back()->with('error', 'Anda tidak dapat memblokir akun Administrator Anda sendiri.');
        }

        // Security Check 2: Prevent deactivating the last active Admin
        if ($user->isAdmin() && $newStatus === 'blocked') {
            $activeAdminCount = User::where('role', 'admin')
                ->where(function ($q) {
                    $q->whereNull('status')->orWhere('status', 'active');
                })
                ->where('id', '!=', $user->id)
                ->count();

            if ($activeAdminCount === 0) {
                return back()->with('error', 'Tidak dapat menonaktifkan akun Administrator aktif terakhir pada sistem.');
            }
        }

        // Security Check 3: Prevent demoting the last active Admin
        if ($user->isAdmin() && $newRole !== 'admin') {
            $otherAdminCount = User::where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->count();

            if ($otherAdminCount === 0) {
                return back()->with('error', 'Tidak dapat mengubah peran Administrator aktif terakhir menjadi peran lain.');
            }
        }

        $oldRole = $user->role_label;
        $oldStatus = $user->status_label;

        $user->update([
            'name' => $validated['name'],
            'full_name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $newRole,
            'status' => $newStatus,
        ]);

        // Synchronize company name if HRD
        if ($user->isHrd() && $user->companyProfile) {
            $user->companyProfile->update([
                'company_name' => $validated['name'],
            ]);
        }

        // Log to ActivityLog
        ActivityLog::log(
            'update',
            "Administrator memperbarui data akun: {$user->name} ({$user->email}). Peran: {$oldRole} -> {$user->role_label}, Status: {$oldStatus} -> {$user->status_label}",
            [
                'target_type' => 'user',
                'target_id' => $user->id,
                'metadata' => [
                    'updated_fields' => ['name', 'email', 'role', 'status'],
                ],
            ]
        );

        return redirect()->route('admin.users')
            ->with('success', "Informasi pengguna {$user->name} berhasil diperbarui.");
    }

    /**
     * Block / Deactivate user account.
     */
    public function block(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat memblokir akun Administrator Anda sendiri.');
        }

        if ($user->isAdmin()) {
            $activeAdminCount = User::where('role', 'admin')
                ->where(function ($q) {
                    $q->whereNull('status')->orWhere('status', 'active');
                })
                ->where('id', '!=', $user->id)
                ->count();

            if ($activeAdminCount === 0) {
                return back()->with('error', 'Tidak dapat memblokir akun Administrator aktif terakhir pada sistem.');
            }
        }

        $user->update(['status' => 'blocked']);

        ActivityLog::log(
            'block',
            "Administrator memblokir akun pengguna {$user->name} ({$user->email})",
            [
                'target_type' => 'user',
                'target_id' => $user->id,
                'level' => 'warning',
            ]
        );

        return back()->with('success', "Akun pengguna {$user->name} telah berhasil dinonaktifkan/diblokir.");
    }

    /**
     * Activate / Unblock user account.
     */
    public function activate(User $user): RedirectResponse
    {
        $user->update(['status' => 'active']);

        ActivityLog::log(
            'activate',
            "Administrator mengaktifkan kembali akun pengguna {$user->name} ({$user->email})",
            [
                'target_type' => 'user',
                'target_id' => $user->id,
            ]
        );

        return back()->with('success', "Akun pengguna {$user->name} telah berhasil diaktifkan kembali.");
    }

    /**
     * Toggle status between active and blocked.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->status === 'blocked') {
            return $this->activate($user);
        }

        return $this->block($user);
    }

    /**
     * Remove the specified user from storage with safe relational cleanup.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Administrator Anda sendiri.');
        }

        if ($user->isAdmin()) {
            $otherAdminCount = User::where('role', 'admin')
                ->where('id', '!=', $user->id)
                ->count();

            if ($otherAdminCount === 0) {
                return back()->with('error', 'Tidak dapat menghapus Administrator aktif terakhir pada sistem.');
            }
        }

        $userName = $user->name;
        $userEmail = $user->email;
        $userId = $user->id;

        DB::transaction(function () use ($user) {
            // 1. Safe Candidate Cleanup
            JobApplication::where('user_id', $user->id)->delete();
            DB::table('saved_jobs')->where('user_id', $user->id)->delete();
            UserAccessibilityPreference::where('user_id', $user->id)->delete();

            // 2. Safe HRD / Company Cleanup
            if ($user->companyProfile) {
                $vacancies = JobVacancy::where('company_id', $user->companyProfile->id)->get();
                foreach ($vacancies as $vacancy) {
                    JobApplication::where('job_vacancy_id', $vacancy->id)->delete();
                    DB::table('facility_job_vacancy')->where('job_vacancy_id', $vacancy->id)->delete();
                    DB::table('saved_jobs')->where('job_vacancy_id', $vacancy->id)->delete();
                    $vacancy->delete();
                }
                $user->companyProfile->delete();
            }

            // 3. Delete user record
            $user->delete();
        });

        ActivityLog::log(
            'delete',
            "Administrator menghapus akun pengguna {$userName} ({$userEmail}) beserta data relasi terkait.",
            [
                'target_type' => 'user',
                'target_id' => $userId,
                'level' => 'warning',
            ]
        );

        return back()->with('success', "Akun pengguna {$userName} dan seluruh relasi data terkait telah berhasil dihapus secara aman.");
    }
}
