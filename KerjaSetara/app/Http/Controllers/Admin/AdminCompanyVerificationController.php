<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CompanyProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminCompanyVerificationController extends Controller
{
    /**
     * Display the Company Verification List view (View 1 · Verifikasi Perusahaan).
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $search = trim($request->query('search', ''));
        $sort = $request->query('sort', 'latest');
        $perPage = (int) $request->query('per_page', 6);

        if ($perPage < 1 || $perPage > 50) {
            $perPage = 6;
        }

        $query = CompanyProfile::with(['user', 'verifiedBy']);

        // 1. Status Tab Filter
        if ($statusFilter !== 'all') {
            $query->status($statusFilter);
        }

        // 2. Search Filter
        if (! empty($search)) {
            $query->search($search);
        }

        // 3. Sorting
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'name') {
            $query->orderBy('company_name', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $companies = $query->paginate($perPage)->withQueryString();

        // 4. Dynamic Summary Counts directly from database
        $counts = [
            'all' => CompanyProfile::count(),
            'pending' => CompanyProfile::where(function ($q) {
                $q->where('verification_status', 'pending')
                    ->orWhereNull('verification_status');
            })->count(),
            'verified' => CompanyProfile::where('verification_status', 'verified')->count(),
            'rejected' => CompanyProfile::where('verification_status', 'rejected')->count(),
            'needs_revision' => CompanyProfile::whereIn('verification_status', ['needs_revision', 'revision'])->count(),
        ];

        return view('admin.companies.verification', compact(
            'companies',
            'counts',
            'statusFilter',
            'search',
            'sort'
        ));
    }

    /**
     * Return company verification details as JSON for interactive modals.
     */
    public function show(CompanyProfile $company): JsonResponse
    {
        $company->load(['user', 'verifiedBy']);

        return response()->json([
            'id' => $company->id,
            'identifier' => $company->identifier,
            'company_name' => $company->company_name,
            'industry' => $company->industry ?? 'Umum',
            'location' => $company->location ?? 'Indonesia',
            'description' => $company->description,
            'phone' => $company->phone,
            'website' => $company->website,
            'status' => $company->verification_status ?? 'pending',
            'status_label' => $company->status_label,
            'status_subtitle' => $company->status_subtitle,
            'registered_date' => $company->formatted_registered_date,
            'registered_time' => $company->formatted_registered_time,
            'verification_notes' => $company->verification_notes,
            'rejection_reason' => $company->rejection_reason,
            'revision_reason' => $company->revision_reason,
            'verified_at' => $company->verified_at?->format('d M Y H:i'),
            'verified_by' => $company->verifiedBy?->name,
            'nib_file' => $company->nib_file_name,
            'npwp_file' => $company->npwp_file_name,
            'has_nib' => $company->hasNibDocument(),
            'has_npwp' => $company->hasNpwpDocument(),
            'nib_doc_url' => route('admin.companies.document', ['company' => $company->id, 'type' => 'nib']),
            'npwp_doc_url' => route('admin.companies.document', ['company' => $company->id, 'type' => 'npwp']),
        ]);
    }

    /**
     * Approve Company Legal Verification.
     */
    public function verify(Request $request, CompanyProfile $company): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $admin = Auth::user();

        $company->update([
            'verification_status' => 'verified',
            'verification_notes' => $validated['notes'] ?? $company->verification_notes,
            'verified_at' => now(),
            'verified_by' => $admin?->id,
            'rejection_reason' => null,
            'revision_reason' => null,
            'is_inclusive_certified' => true,
        ]);

        // Centralized server audit log
        ActivityLog::log(
            'verify',
            "Memverifikasi legalitas {$company->company_name} ({$company->identifier}); berkas disetujui.",
            [
                'level' => 'info',
                'result' => 'Berhasil',
                'target_type' => 'company',
                'target_id' => $company->id,
                'metadata' => [
                    'company_name' => $company->company_name,
                    'identifier' => $company->identifier,
                    'verified_by' => $admin?->name,
                    'notes' => $validated['notes'] ?? null,
                ],
            ]
        );

        $message = "Legalitas perusahaan '{$company->company_name}' ({$company->identifier}) berhasil diverifikasi dan disetujui.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => 'verified',
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Reject Company Legal Verification with mandatory reason.
     */
    public function reject(Request $request, CompanyProfile $company): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'alasan' => ['required', 'string', 'min:5', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'alasan.required' => 'Alasan penolakan verifikasi wajib diisi agar perusahaan memahami alasan keputusan.',
            'alasan.min' => 'Alasan penolakan minimal terdiri dari 5 karakter.',
        ]);

        $admin = Auth::user();

        $company->update([
            'verification_status' => 'rejected',
            'rejection_reason' => trim($validated['alasan']),
            'verification_notes' => $validated['notes'] ?? $company->verification_notes,
            'verified_at' => now(),
            'verified_by' => $admin?->id,
            'revision_reason' => null,
            'is_inclusive_certified' => false,
        ]);

        // Centralized server audit log
        ActivityLog::log(
            'reject',
            "Menolak legalitas {$company->company_name} ({$company->identifier}). Alasan: {$validated['alasan']}",
            [
                'level' => 'warning',
                'result' => 'Ditolak',
                'target_type' => 'company',
                'target_id' => $company->id,
                'metadata' => [
                    'company_name' => $company->company_name,
                    'identifier' => $company->identifier,
                    'rejection_reason' => $validated['alasan'],
                    'verified_by' => $admin?->name,
                ],
            ]
        );

        $message = "Verifikasi legalitas perusahaan '{$company->company_name}' telah ditolak dengan alasan yang tercatat.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => 'rejected',
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Request Document Revision with mandatory instruction/reason.
     */
    public function requestRevision(Request $request, CompanyProfile $company): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'alasan' => ['required', 'string', 'min:5', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'alasan.required' => 'Instruksi atau catatan revisi dokumen wajib diisi agar perusahaan mengetahui bagian yang perlu diperbaiki.',
            'alasan.min' => 'Instruksi revisi minimal terdiri dari 5 karakter.',
        ]);

        $admin = Auth::user();

        $company->update([
            'verification_status' => 'needs_revision',
            'revision_reason' => trim($validated['alasan']),
            'verification_notes' => $validated['notes'] ?? $company->verification_notes,
            'verified_at' => now(),
            'verified_by' => $admin?->id,
            'rejection_reason' => null,
        ]);

        // Centralized server audit log
        ActivityLog::log(
            'revision',
            "Meminta revisi dokumen legalitas {$company->company_name} ({$company->identifier}). Catatan: {$validated['alasan']}",
            [
                'level' => 'info',
                'result' => 'Berhasil',
                'target_type' => 'company',
                'target_id' => $company->id,
                'metadata' => [
                    'company_name' => $company->company_name,
                    'identifier' => $company->identifier,
                    'revision_reason' => $validated['alasan'],
                    'verified_by' => $admin?->name,
                ],
            ]
        );

        $message = "Permintaan revisi berkas untuk '{$company->company_name}' berhasil dikirimkan.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => 'needs_revision',
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Save/update review notes without changing the company's verification status.
     */
    public function updateNotes(Request $request, CompanyProfile $company): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $company->update([
            'verification_notes' => $validated['notes'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Catatan peninjauan berhasil disimpan.',
                'verification_notes' => $company->verification_notes,
            ]);
        }

        return redirect()->back()->with('success', 'Catatan peninjauan berhasil disimpan.');
    }

    /**
     * Serve or download legal document safely for Admin review (Protected Access).
     */
    public function document(Request $request, CompanyProfile $company, string $type): Response
    {
        $type = strtolower($type);
        $isNib = in_array($type, ['nib', 'surat_izin', 'nib_file', 'izin'], true);
        $fileName = $isNib ? $company->nib_file : $company->npwp_file;
        $docLabel = $isNib ? 'Surat Izin Usaha / NIB' : 'NPWP Perusahaan';

        // Check if file exists on disk
        $possiblePaths = [];
        if ($fileName) {
            $possiblePaths[] = storage_path('app/private/company_documents/'.$fileName);
            $possiblePaths[] = storage_path('app/public/company_documents/'.$fileName);
            $possiblePaths[] = storage_path('app/'.$fileName);
            $possiblePaths[] = public_path('storage/company_documents/'.$fileName);
        }

        foreach ($possiblePaths as $path) {
            if (file_exists($path) && is_file($path)) {
                $mime = mime_content_type($path) ?: 'application/pdf';
                if ($request->boolean('download')) {
                    return response()->download($path, basename($path));
                }

                return response(file_get_contents($path), 200, [
                    'Content-Type' => $mime,
                    'Content-Disposition' => 'inline; filename="'.basename($path).'"',
                    'X-Content-Type-Options' => 'nosniff',
                ]);
            }
        }

        // If physical file doesn't exist, generate an authentic rendered preview matching the Figma design:
        // "RINGKASAN IDENTITAS USAHA / NOMOR INDUK BERUSAHA (NIB)"
        $svgContent = $this->generateDocumentPreviewHtml($company, $isNib);

        return response($svgContent, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Generate an authentic document preview matching Figma State · Document Viewer Modal.
     */
    protected function generateDocumentPreviewHtml(CompanyProfile $company, bool $isNib): string
    {
        $title = $isNib ? 'RINGKASAN IDENTITAS USAHA' : 'KARTU IDENTITAS PERPAJAKAN';
        $subTitle = $isNib ? 'NOMOR INDUK BERUSAHA (NIB)' : 'NOMOR POKOK WAJIB PAJAK (NPWP)';
        $docNum = $isNib ? ('NIB-DEMO-'.$company->identifier) : ('NPWP-'.$company->identifier.'-000');
        $companyName = htmlspecialchars($company->company_name, ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($company->location ?? 'Jl. Contoh Digital No. 12, Jakarta', ENT_QUOTES, 'UTF-8');
        $industry = htmlspecialchars($company->industry ?? 'Layanan teknologi informasi (ilustratif)', ENT_QUOTES, 'UTF-8');
        $regDate = $company->formatted_registered_date;

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Dokumen {$companyName}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 24px;
            min-height: 100vh;
        }
        .document-card {
            background: #ffffff;
            width: 100%;
            max-width: 640px;
            border-radius: 8px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            padding: 36px 40px;
        }
        .badge-header {
            background: #fef3c7;
            color: #92400e;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 24px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .doc-watermark {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .doc-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 6px;
            letter-spacing: -0.3px;
        }
        .doc-number {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-top: 4px;
            margin-bottom: 20px;
        }
        .divider {
            height: 2px;
            background: #1e3a8a;
            margin: 16px 0 28px 0;
            border-radius: 2px;
        }
        .field-group {
            margin-bottom: 18px;
        }
        .field-label {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            text-transform: capitalize;
            margin-bottom: 3px;
        }
        .field-value {
            font-size: 13px;
            color: #0f172a;
            font-weight: 700;
        }
        .field-desc {
            font-size: 13px;
            color: #334155;
            font-weight: 500;
        }
        .footer-note {
            margin-top: 32px;
            padding-top: 20px;
            border-top: 1px dashed #cbd5e1;
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .seal-mark {
            display: inline-block;
            margin-top: 12px;
            font-size: 10px;
            color: #64748b;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="document-card">
        <div class="badge-header">
            CONTOH ILUSTRATIF · BUKAN DOKUMEN RESMI
        </div>

        <div class="text-center">
            <div class="doc-watermark">KERJASETARA / DATA DEMONSTRASI</div>
            <div class="doc-title">{$title}</div>
            <div class="doc-number">Nomor contoh: {$docNum}</div>
        </div>

        <div class="divider"></div>

        <div class="field-group">
            <div class="field-label">Nama perusahaan</div>
            <div class="field-value">{$companyName}</div>
        </div>

        <div class="field-group">
            <div class="field-label">Alamat pusat</div>
            <div class="field-desc">{$address}</div>
        </div>

        <div class="field-group">
            <div class="field-label">Bidang usaha</div>
            <div class="field-desc">{$industry}</div>
        </div>

        <div class="field-group">
            <div class="field-label">Identitas perpajakan / pendaftaran</div>
            <div class="field-value">{$docNum}</div>
        </div>

        <div class="field-group">
            <div class="field-label">Tanggal dokumen</div>
            <div class="field-desc">{$regDate}</div>
        </div>

        <div class="footer-note">
            <p>Seluruh nomor, alamat, dan isi di atas adalah placeholder untuk pratinjau antarmuka. Tidak memiliki kekuatan hukum.</p>
            <div class="seal-mark">Tidak ada tanda tangan, stempel, atau kode verifikasi resmi. Tersimpan dalam arsip audit internal.</div>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
