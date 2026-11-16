<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCompanyVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $candidate;

    protected User $hrd;

    protected CompanyProfile $companyPending;

    protected CompanyProfile $companyVerified;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
            'name' => 'Admin Fakhri',
            'email' => 'admin.fakhri@kerjasetara.test',
        ]);

        $this->candidate = User::factory()->create([
            'role' => 'kandidat',
            'status' => 'active',
            'name' => 'Nadia Saraswati',
            'email' => 'nadia.saraswati@kerjasetara.test',
        ]);

        $this->hrd = User::factory()->create([
            'role' => 'hrd',
            'status' => 'active',
            'name' => 'Dina Pratama',
            'email' => 'hrd.dina@teknologiinovatif.test',
        ]);

        $this->companyPending = CompanyProfile::create([
            'user_id' => $this->hrd->id,
            'company_name' => 'PT Teknologi Inovatif',
            'industry' => 'Teknologi',
            'location' => 'Jl. Contoh Digital No. 12, Jakarta Selatan',
            'nib_file' => 'nib_teknologi_inovatif_contoh.pdf',
            'npwp_file' => 'npwp_teknologi_inovatif_contoh.pdf',
            'verification_status' => 'pending',
            'employee_count' => 150,
        ]);

        $hrd2 = User::factory()->create([
            'role' => 'hrd',
            'status' => 'active',
            'name' => 'Bambang Pratama',
            'email' => 'hrd.arunika@arunika.test',
        ]);

        $this->companyVerified = CompanyProfile::create([
            'user_id' => $hrd2->id,
            'company_name' => 'PT Arunika Finance',
            'industry' => 'Keuangan',
            'location' => 'Jl. Contoh Usaha No. 21, Surabaya',
            'nib_file' => 'nib_arunika_finance_contoh.pdf',
            'npwp_file' => 'npwp_arunika_finance_contoh.pdf',
            'verification_status' => 'verified',
            'verified_at' => now()->subDay(),
            'employee_count' => 200,
        ]);
    }

    public function test_guest_is_redirected_from_verification_page(): void
    {
        $response = $this->get(route('admin.companies.verification'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_candidate_cannot_access_company_verification(): void
    {
        $response = $this->actingAs($this->candidate)->get(route('admin.companies.verification'));
        $response->assertRedirect(route('home'));
    }

    public function test_hrd_cannot_access_company_verification(): void
    {
        $response = $this->actingAs($this->hrd)->get(route('admin.companies.verification'));
        $response->assertRedirect(route('home'));
    }

    public function test_blocked_admin_cannot_access_company_verification(): void
    {
        $blockedAdmin = User::factory()->create([
            'role' => 'admin',
            'status' => 'blocked',
        ]);

        $response = $this->actingAs($blockedAdmin)->get(route('admin.companies.verification'));
        $response->assertRedirect(route('admin.login'));

        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'access_denied',
            'level' => 'error',
            'result' => 'Ditolak',
        ]);
    }

    public function test_admin_can_access_verification_page_and_see_companies_and_summary(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.companies.verification'));

        $response->assertOk();
        $response->assertSee('Verifikasi Legalitas Perusahaan.');
        $response->assertSee('perusahaan menunggu pemeriksaan dokumen');
        $response->assertSee('PT Teknologi Inovatif');
        $response->assertSee('PT Arunika Finance');
        $response->assertSee('Tinjau dokumen');
    }

    public function test_admin_can_filter_companies_by_status(): void
    {
        // Filter pending
        $response = $this->actingAs($this->admin)->get(route('admin.companies.verification', ['status' => 'pending']));
        $response->assertOk();
        $response->assertSee('PT Teknologi Inovatif');
        $response->assertDontSee('PT Arunika Finance');

        // Filter verified
        $responseVerified = $this->actingAs($this->admin)->get(route('admin.companies.verification', ['status' => 'verified']));
        $responseVerified->assertOk();
        $responseVerified->assertSee('PT Arunika Finance');
        $responseVerified->assertDontSee('PT Teknologi Inovatif');
    }

    public function test_admin_can_search_companies(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.companies.verification', ['search' => 'Arunika']));
        $response->assertOk();
        $response->assertSee('PT Arunika Finance');
        $response->assertDontSee('PT Teknologi Inovatif');
    }

    public function test_admin_can_view_company_show_json(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.companies.show', $this->companyPending));

        $response->assertOk();
        $response->assertJson([
            'id' => $this->companyPending->id,
            'company_name' => 'PT Teknologi Inovatif',
            'status' => 'pending',
            'identifier' => 'KS-C'.sprintf('%03d', $this->companyPending->id),
        ]);
    }

    public function test_admin_can_view_protected_document(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.companies.document', [
            'company' => $this->companyPending->id,
            'type' => 'nib',
        ]));

        $response->assertOk();
        $response->assertSee('RINGKASAN IDENTITAS USAHA');
        $response->assertSee('PT Teknologi Inovatif');
    }

    public function test_guest_and_candidate_cannot_access_protected_document(): void
    {
        // Guest
        $guestResponse = $this->get(route('admin.companies.document', [
            'company' => $this->companyPending->id,
            'type' => 'nib',
        ]));
        $guestResponse->assertRedirect(route('admin.login'));

        // Candidate
        $candidateResponse = $this->actingAs($this->candidate)->get(route('admin.companies.document', [
            'company' => $this->companyPending->id,
            'type' => 'nib',
        ]));
        $candidateResponse->assertRedirect(route('home'));
    }

    public function test_admin_can_approve_company_and_it_logs_activity(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.companies.verify', $this->companyPending), [
            'notes' => 'Dokumen NIB dan NPWP valid sesuai OSS.',
        ]);

        $response->assertRedirect();
        $this->companyPending->refresh();

        $this->assertEquals('verified', $this->companyPending->verification_status);
        $this->assertTrue($this->companyPending->is_inclusive_certified);
        $this->assertEquals($this->admin->id, $this->companyPending->verified_by);
        $this->assertEquals('Dokumen NIB dan NPWP valid sesuai OSS.', $this->companyPending->verification_notes);

        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'verify',
            'target_type' => 'company',
            'target_id' => $this->companyPending->id,
            'level' => 'info',
            'result' => 'Berhasil',
        ]);
    }

    public function test_admin_can_reject_company_with_mandatory_reason_and_it_logs_activity(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.companies.reject', $this->companyPending), [
            'alasan' => 'Nomor izin usaha tidak valid dan masa berlaku telah habis.',
        ]);

        $response->assertRedirect();
        $this->companyPending->refresh();

        $this->assertEquals('rejected', $this->companyPending->verification_status);
        $this->assertEquals('Nomor izin usaha tidak valid dan masa berlaku telah habis.', $this->companyPending->rejection_reason);
        $this->assertFalse($this->companyPending->is_inclusive_certified);

        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'reject',
            'target_type' => 'company',
            'target_id' => $this->companyPending->id,
            'level' => 'warning',
            'result' => 'Ditolak',
        ]);
    }

    public function test_admin_cannot_reject_company_without_reason(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.companies.reject', $this->companyPending), [
            'alasan' => '',
        ]);

        $response->assertSessionHasErrors('alasan');
        $this->companyPending->refresh();
        $this->assertEquals('pending', $this->companyPending->verification_status);
    }

    public function test_admin_can_request_revision_with_mandatory_reason_and_it_logs_activity(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.companies.revision', $this->companyPending), [
            'alasan' => 'Scan dokumen NPWP buram, mohon unggah ulang berkas asli PDF.',
        ]);

        $response->assertRedirect();
        $this->companyPending->refresh();

        $this->assertEquals('needs_revision', $this->companyPending->verification_status);
        $this->assertEquals('Scan dokumen NPWP buram, mohon unggah ulang berkas asli PDF.', $this->companyPending->revision_reason);

        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'revision',
            'target_type' => 'company',
            'target_id' => $this->companyPending->id,
            'level' => 'info',
            'result' => 'Berhasil',
        ]);
    }

    public function test_admin_cannot_request_revision_without_reason(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.companies.revision', $this->companyPending), [
            'alasan' => '',
        ]);

        $response->assertSessionHasErrors('alasan');
        $this->companyPending->refresh();
        $this->assertEquals('pending', $this->companyPending->verification_status);
    }

    public function test_admin_can_update_review_notes_without_changing_status(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.companies.notes', $this->companyPending), [
            'notes' => 'Catatan sementara: sedang menunggu pengecekan data di Kemenkumham.',
        ]);

        $response->assertRedirect();
        $this->companyPending->refresh();

        $this->assertEquals('pending', $this->companyPending->verification_status);
        $this->assertEquals('Catatan sementara: sedang menunggu pengecekan data di Kemenkumham.', $this->companyPending->verification_notes);
    }

    public function test_hrd_sees_updated_status_and_reasons_in_settings(): void
    {
        $this->companyPending->update([
            'verification_status' => 'needs_revision',
            'revision_reason' => 'Perbaiki scan lembar kedua NIB.',
        ]);

        $response = $this->actingAs($this->hrd)->get(route('hrd.pengaturan.index', ['tab' => 'dokumen']));

        $response->assertOk();
        $response->assertSee('Perlu Revisi Dokumen');
        $response->assertSee('Perbaiki scan lembar kedua NIB.');
    }

    public function test_hrd_document_reupload_resets_status_to_pending(): void
    {
        $this->companyPending->update([
            'verification_status' => 'needs_revision',
            'revision_reason' => 'Perbaiki scan lembar kedua NIB.',
        ]);

        $response = $this->actingAs($this->hrd)->post(route('hrd.pengaturan.updateDocument'), [
            'document_type' => 'nib',
        ]);

        $response->assertRedirect();
        $this->companyPending->refresh();

        $this->assertEquals('pending', $this->companyPending->verification_status);

        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'update',
            'target_type' => 'company',
            'target_id' => $this->companyPending->id,
            'level' => 'info',
            'result' => 'Berhasil',
        ]);
    }
}
