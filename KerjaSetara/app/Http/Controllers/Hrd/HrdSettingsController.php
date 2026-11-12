<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class HrdSettingsController extends Controller
{
    /**
     * Display the company settings page.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->companyProfile;

        if (! $company) {
            abort(404, 'Data profil perusahaan belum tersedia.');
        }

        $activeTab = $request->query('tab', 'profil');

        $availableFacilities = [
            'kursi_roda' => [
                'name' => 'Akses Kursi Roda (Ramp, Lift, Pintu Lebar)',
                'icon' => 'wheelchair',
            ],
            'toilet_disabilitas' => [
                'name' => 'Toilet Aksesibel (Pegangan rambat & ruang luas)',
                'icon' => 'toilet',
            ],
            'bisindo' => [
                'name' => 'Penerjemah Bahasa Isyarat (Tersedia saat Onboarding/Meeting)',
                'icon' => 'hands',
            ],
            'screen_reader' => [
                'name' => 'Software Pembaca Layar / Screen Reader Friendly Workstation',
                'icon' => 'monitor',
            ],
            'ruang_tenang' => [
                'name' => 'Ruang Tenang (Quiet Room untuk kebutuhan Neurodiverse)',
                'icon' => 'quiet',
            ],
        ];

        return view('hrd.settings.index', compact('user', 'company', 'activeTab', 'availableFacilities'));
    }

    /**
     * Update Company Profile Information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $company = $user->companyProfile;

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
        ]);

        $company->update([
            'company_name' => $validated['company_name'],
            'location' => $validated['location'],
            'phone' => $validated['phone'] ?? null,
            'website' => $validated['website'] ?? null,
        ]);

        return redirect()->route('hrd.pengaturan.index', ['tab' => 'profil'])
            ->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    /**
     * Update Company Accessibility Facilities.
     */
    public function updateFacilities(Request $request)
    {
        $user = Auth::user();
        $company = $user->companyProfile;

        $validated = $request->validate([
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['string'],
        ]);

        $company->update([
            'accessibility_features' => $validated['facilities'] ?? [],
        ]);

        return redirect()->route('hrd.pengaturan.index', ['tab' => 'fasilitas'])
            ->with('success', 'Daftar fasilitas aksesibilitas kantor berhasil diperbarui dan disinkronkan ke MatchingEngine.');
    }

    /**
     * Handle Company Legal Documents Upload / Update.
     */
    public function updateDocument(Request $request)
    {
        $user = Auth::user();
        $company = $user->companyProfile;

        $request->validate([
            'document_type' => ['required', 'string', 'in:nib,npwp'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,png', 'max:5120'],
        ]);

        $docType = $request->input('document_type');
        $fileName = $docType === 'nib'
            ? 'NIB_'.str_replace(' ', '_', $company->company_name).'.pdf'
            : 'NPWP_'.str_replace(' ', '_', $company->company_name).'.pdf';

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('company_documents', 'public');
            $fileName = basename($path);
        }

        if ($docType === 'nib') {
            $company->update(['nib_file' => $fileName]);
        } else {
            $company->update(['npwp_file' => $fileName]);
        }

        return redirect()->route('hrd.pengaturan.index', ['tab' => 'dokumen'])
            ->with('success', 'Dokumen legalitas berhasil diperbarui.');
    }

    /**
     * Update HR Account Password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('hrd.pengaturan.index', ['tab' => 'keamanan'])
            ->with('success', 'Kata sandi akun HR berhasil diperbarui.');
    }
}
