<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Facility;
use App\Services\SvgSanitizerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminFacilityController extends Controller
{
    public function __construct(
        protected SvgSanitizerService $sanitizer
    ) {}

    /**
     * Display a listing of accessibility facilities with search, status filters, and summary stats.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'all');
        $search = trim($request->query('search', ''));

        $query = Facility::withCount('jobVacancies')->orderBy('id', 'asc');

        // 1. Status Filter
        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // 2. Search Filter (Nama, Deskripsi, atau ID seperti FAS-001)
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");

                if (preg_match('/^FAS-(\d+)$/i', $search, $matches)) {
                    $q->orWhere('id', (int) $matches[1]);
                } elseif (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        $facilities = $query->paginate(10)->withQueryString();

        // 3. Ringkasan database (Total, Aktif, Nonaktif, Total Terpasang di Lowongan)
        $summary = [
            'total' => Facility::count(),
            'active' => Facility::where('is_active', true)->count(),
            'inactive' => Facility::where('is_active', false)->count(),
            'total_in_use' => DB::table('facility_job_vacancy')->distinct('facility_id')->count('facility_id'),
            'total_placements' => DB::table('facility_job_vacancy')->count(),
        ];

        return view('admin.facilities.index', compact(
            'facilities',
            'statusFilter',
            'search',
            'summary'
        ));
    }

    /**
     * Store a newly created accessibility facility in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('facilities', 'name'),
            ],
            'description' => ['required', 'string'],
            'icon' => ['required', 'file', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama fasilitas wajib diisi.',
            'name.unique' => 'Nama fasilitas ini sudah terdaftar di sistem.',
            'description.required' => 'Deskripsi fasilitas wajib diisi.',
            'icon.required' => 'Ikon SVG fasilitas wajib diunggah.',
            'icon.max' => 'Batas ukuran SVG maksimal 100 KB.',
        ]);

        $uploadedFile = $request->file('icon');

        // Check file extension
        if (strtolower($uploadedFile->getClientOriginalExtension()) !== 'svg') {
            throw ValidationException::withMessages([
                'icon' => 'Berkas harus berupa file SVG (.svg).',
            ]);
        }

        // Validate and sanitize SVG
        $sanitizeResult = $this->sanitizer->validateAndSanitize($uploadedFile);
        if (! $sanitizeResult['valid']) {
            throw ValidationException::withMessages([
                'icon' => $sanitizeResult['error'] ?? 'File SVG tidak valid atau tidak aman.',
            ]);
        }

        // Store sanitized SVG file persistently
        $storedPath = $this->sanitizer->storeSvg(
            $sanitizeResult['sanitized'],
            $request->input('name')
        );

        $isActive = $request->boolean('is_active', true);

        $facility = Facility::create([
            'name' => trim($request->input('name')),
            'description' => trim($request->input('description')),
            'icon_path' => $storedPath,
            'is_active' => $isActive,
        ]);

        // Log action to ActivityLog
        ActivityLog::log(
            'create',
            "Administrator menambahkan master fasilitas baru: {$facility->name} ({$facility->identifier}) dengan status " . ($facility->is_active ? 'Aktif' : 'Nonaktif'),
            [
                'target_type' => 'facility',
                'target_id' => $facility->id,
                'metadata' => [
                    'name' => $facility->name,
                    'identifier' => $facility->identifier,
                    'is_active' => $facility->is_active,
                    'icon_path' => $facility->icon_path,
                ],
            ]
        );

        return redirect()->route('admin.facilities.index')
            ->with('success', "Fasilitas '{$facility->name}' ({$facility->identifier}) berhasil ditambahkan ke master data.");
    }

    /**
     * Return facility data as JSON for edit or view modal.
     */
    public function show(Facility $facility): JsonResponse
    {
        $usageCount = $facility->jobVacancies()->count();

        // Calculate icon file size & dimensions if available
        $iconSize = null;
        $dimensions = '24 × 24 px';

        if ($facility->icon_path && Storage::disk('public')->exists($facility->icon_path)) {
            $bytes = Storage::disk('public')->size($facility->icon_path);
            $iconSize = round($bytes / 1024, 1) . ' KB';
        }

        return response()->json([
            'id' => $facility->id,
            'identifier' => $facility->identifier,
            'name' => $facility->name,
            'description' => $facility->description,
            'icon_path' => $facility->icon_path,
            'icon_filename' => $facility->icon_filename,
            'icon_size' => $iconSize ?? '2.4 KB',
            'dimensions' => $dimensions,
            'icon_url' => $facility->icon_url,
            'svg_content' => $facility->svg_content,
            'is_active' => (bool) $facility->is_active,
            'status_label' => $facility->status_label,
            'usage_count' => $usageCount,
            'is_in_use' => $usageCount > 0,
        ]);
    }

    /**
     * Update an existing accessibility facility in storage.
     */
    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('facilities', 'name')->ignore($facility->id),
            ],
            'description' => ['required', 'string'],
            'icon' => ['nullable', 'file', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama fasilitas wajib diisi.',
            'name.unique' => 'Nama fasilitas ini sudah digunakan oleh fasilitas lain.',
            'description.required' => 'Deskripsi fasilitas wajib diisi.',
            'icon.max' => 'Batas ukuran SVG maksimal 100 KB.',
        ]);

        $updateData = [
            'name' => trim($request->input('name')),
            'description' => trim($request->input('description')),
        ];

        if ($request->has('is_active')) {
            $updateData['is_active'] = $request->boolean('is_active');
        }

        // Handle new SVG icon upload if provided
        if ($request->hasFile('icon')) {
            $uploadedFile = $request->file('icon');

            if (strtolower($uploadedFile->getClientOriginalExtension()) !== 'svg') {
                throw ValidationException::withMessages([
                    'icon' => 'Berkas harus berupa file SVG (.svg).',
                ]);
            }

            $sanitizeResult = $this->sanitizer->validateAndSanitize($uploadedFile);
            if (! $sanitizeResult['valid']) {
                throw ValidationException::withMessages([
                    'icon' => $sanitizeResult['error'] ?? 'File SVG tidak valid atau tidak aman.',
                ]);
            }

            // Remove old icon file if exists
            if ($facility->icon_path && Storage::disk('public')->exists($facility->icon_path)) {
                Storage::disk('public')->delete($facility->icon_path);
            }

            $storedPath = $this->sanitizer->storeSvg(
                $sanitizeResult['sanitized'],
                $request->input('name')
            );
            $updateData['icon_path'] = $storedPath;
        }

        $oldName = $facility->name;
        $oldStatus = $facility->status_label;

        $facility->update($updateData);

        // Log action to ActivityLog
        ActivityLog::log(
            'update',
            "Administrator memperbarui master fasilitas: {$facility->name} ({$facility->identifier}). Status: {$oldStatus} -> {$facility->status_label}",
            [
                'target_type' => 'facility',
                'target_id' => $facility->id,
                'metadata' => [
                    'old_name' => $oldName,
                    'new_name' => $facility->name,
                    'is_active' => $facility->is_active,
                    'icon_updated' => $request->hasFile('icon'),
                ],
            ]
        );

        return redirect()->route('admin.facilities.index')
            ->with('success', "Informasi fasilitas '{$facility->name}' ({$facility->identifier}) berhasil diperbarui.");
    }

    /**
     * Toggle active/inactive status of the facility.
     */
    public function toggleStatus(Facility $facility): RedirectResponse
    {
        $newStatus = ! $facility->is_active;
        $facility->update(['is_active' => $newStatus]);

        $statusLabel = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::log(
            'update',
            "Administrator mengubah status fasilitas {$facility->name} ({$facility->identifier}) menjadi {$facility->status_label}",
            [
                'target_type' => 'facility',
                'target_id' => $facility->id,
                'metadata' => [
                    'is_active' => $facility->is_active,
                ],
            ]
        );

        return back()->with('success', "Fasilitas '{$facility->name}' berhasil {$statusLabel}.");
    }

    /**
     * Remove the specified facility from storage.
     * Enforces the rule: Facilities currently in use by vacancies cannot be deleted.
     */
    public function destroy(Facility $facility): RedirectResponse
    {
        $usageCount = $facility->jobVacancies()->count();

        // ─── Critical Server-Side Validation: Prevent deletion of in-use facilities ───
        if ($usageCount > 0) {
            ActivityLog::log(
                'delete_blocked',
                "Upaya penghapusan fasilitas '{$facility->name}' ({$facility->identifier}) ditolak: Fasilitas masih digunakan oleh {$usageCount} lowongan kerja.",
                [
                    'target_type' => 'facility',
                    'target_id' => $facility->id,
                    'level' => 'warning',
                    'result' => 'failed',
                    'metadata' => [
                        'usage_count' => $usageCount,
                    ],
                ]
            );

            return back()->with('error', "Fasilitas '{$facility->name}' ({$facility->identifier}) sedang digunakan di {$usageCount} lowongan dan tidak dapat dihapus. Silakan nonaktifkan status fasilitas agar tidak dapat dipilih lagi pada lowongan baru.");
        }

        $facilityName = $facility->name;
        $facilityIdentifier = $facility->identifier;
        $facilityId = $facility->id;

        // Delete icon asset if exists
        if ($facility->icon_path && Storage::disk('public')->exists($facility->icon_path)) {
            Storage::disk('public')->delete($facility->icon_path);
        }

        $facility->delete();

        // Log deletion to ActivityLog
        ActivityLog::log(
            'delete',
            "Administrator menghapus master fasilitas: {$facilityName} ({$facilityIdentifier}) secara permanen.",
            [
                'target_type' => 'facility',
                'target_id' => $facilityId,
                'level' => 'warning',
            ]
        );

        return redirect()->route('admin.facilities.index')
            ->with('success', "Master fasilitas '{$facilityName}' ({$facilityIdentifier}) telah berhasil dihapus.");
    }

    /**
     * Serve SVG icon directly with proper SVG MIME headers.
     */
    public function icon(Facility $facility): Response
    {
        $svg = $facility->svg_content;

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
