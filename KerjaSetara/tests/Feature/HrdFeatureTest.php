<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HrdFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $hrUser;

    protected CompanyProfile $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hrUser = User::where('email', 'hrd@teknologiinovatif.test')->first()
            ?? User::where('role', 'hrd')->first();
        $this->company = $this->hrUser->companyProfile;
    }

    public function test_guest_cannot_access_hrd_portal(): void
    {
        $response = $this->get('/hrd/dashboard');
        $response->assertRedirect('/hrd/login');
    }

    public function test_kandidat_cannot_access_hrd_portal(): void
    {
        $kandidat = User::where('role', 'kandidat')->first();

        $response = $this->actingAs($kandidat)->get('/hrd/dashboard');
        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_hrd_can_access_dashboard_overview(): void
    {
        $response = $this->actingAs($this->hrUser)->get('/hrd/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Ringkasan');
        $response->assertSee('Lowongan aktif');
        $response->assertSee('Total pelamar');
        $response->assertSee('Menunggu ditinjau');
        $response->assertSee('Fasilitas terisi');
        $response->assertSee('Aktivitas pelamar');
        $response->assertSee('PT Teknologi Inovatif');
    }

    public function test_hrd_can_view_vacancies_management_page(): void
    {
        $response = $this->actingAs($this->hrUser)->get('/hrd/lowongan');

        $response->assertStatus(200);
        $response->assertSee('Kelola Lowongan Kerja');
        $response->assertSee('UI/UX Designer');
        $response->assertSee('Semua');
        $response->assertSee('Aktif');
        $response->assertSee('Kedaluwarsa');
    }

    public function test_hrd_can_create_and_publish_new_vacancy(): void
    {
        $data = [
            'title' => 'Accessibility QA Specialist',
            'job_type' => 'Full-Time',
            'location' => 'Jakarta, Hybrid',
            'salary_estimate' => 'Rp 10-15 juta / bulan',
            'description' => 'Menguji kompatibilitas software dengan standar WCAG 2.1.',
            'qualifications' => 'Memiliki pemahaman mendalam tentang assistive technology.',
            'accessibility_tags' => ['kursi_roda', 'screen_reader'],
            'deadline' => '2026-11-30',
            'is_active' => '1',
            'action' => 'publish',
        ];

        $response = $this->actingAs($this->hrUser)->post('/hrd/lowongan', $data);

        $response->assertRedirect('/hrd/lowongan');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_vacancies', [
            'company_id' => $this->company->id,
            'title' => 'Accessibility QA Specialist',
            'is_active' => true,
        ]);

        // Verifikasi lowongan langsung tampil di pencarian publik kandidat
        $candidateResponse = $this->get('/');
        $candidateResponse->assertSee('Accessibility QA Specialist');
    }

    public function test_hrd_can_save_vacancy_as_draft(): void
    {
        $data = [
            'title' => 'Draft Inklusif Role',
            'job_type' => 'Part-Time',
            'location' => 'Bandung, Remote',
            'description' => 'Posisi draft yang belum dipublikasikan.',
            'action' => 'draft',
        ];

        $response = $this->actingAs($this->hrUser)->post('/hrd/lowongan', $data);

        $response->assertRedirect('/hrd/lowongan');

        $this->assertDatabaseHas('job_vacancies', [
            'company_id' => $this->company->id,
            'title' => 'Draft Inklusif Role',
            'is_active' => false,
        ]);
    }

    public function test_hrd_can_edit_existing_vacancy(): void
    {
        $vacancy = $this->company->vacancies()->first();

        $response = $this->actingAs($this->hrUser)->put("/hrd/lowongan/{$vacancy->id}", [
            'title' => 'Senior UI/UX Inklusif Specialist',
            'job_type' => 'Hybrid',
            'salary_estimate' => 'Rp 12-18 juta / bulan',
            'accessibility_tags' => ['kursi_roda', 'bisindo', 'ruang_tenang'],
        ]);

        $response->assertRedirect('/hrd/lowongan');
        $this->assertDatabaseHas('job_vacancies', [
            'id' => $vacancy->id,
            'title' => 'Senior UI/UX Inklusif Specialist',
        ]);
    }

    public function test_hrd_can_toggle_vacancy_active_status(): void
    {
        $vacancy = $this->company->vacancies()->where('is_active', true)->first();

        $response = $this->actingAs($this->hrUser)->post("/hrd/lowongan/{$vacancy->id}/toggle-status");

        $response->assertRedirect('/hrd/lowongan');
        $this->assertDatabaseHas('job_vacancies', [
            'id' => $vacancy->id,
            'is_active' => false,
        ]);
    }

    public function test_hrd_can_view_applicants_list(): void
    {
        $response = $this->actingAs($this->hrUser)->get('/hrd/pelamar');

        $response->assertStatus(200);
        $response->assertSee('Kelola Pelamar Kerja');
        $response->assertSee('Tinjau Pelamar');
        $response->assertSee('Ubah Status');
    }

    public function test_hrd_can_update_applicant_status(): void
    {
        $application = JobApplication::whereHas('jobVacancy', function ($q) {
            $q->where('company_id', $this->company->id);
        })->first();

        $response = $this->actingAs($this->hrUser)->post("/hrd/pelamar/{$application->id}/status", [
            'status' => 'interview',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'status' => 'interview',
        ]);
    }

    public function test_hrd_cannot_modify_other_company_vacancy(): void
    {
        $otherCompany = CompanyProfile::where('id', '!=', $this->company->id)->first();
        $otherVacancy = $otherCompany->vacancies()->first();

        if ($otherVacancy) {
            $response = $this->actingAs($this->hrUser)->put("/hrd/lowongan/{$otherVacancy->id}", [
                'title' => 'Hacked Title',
                'job_type' => 'Full-Time',
            ]);

            $response->assertStatus(403);
        }
    }

    public function test_hrd_can_update_company_profile_and_facilities(): void
    {
        $response = $this->actingAs($this->hrUser)->post('/hrd/pengaturan/profil', [
            'company_name' => 'PT Teknologi Inovatif Nusantara',
            'location' => 'Jakarta Selatan, DKI Jakarta',
            'phone' => '021-99887766',
            'website' => 'https://teknologiinovatif.id',
        ]);

        $response->assertRedirect('/hrd/pengaturan?tab=profil');
        $this->assertDatabaseHas('company_profiles', [
            'id' => $this->company->id,
            'company_name' => 'PT Teknologi Inovatif Nusantara',
            'phone' => '021-99887766',
        ]);

        // Update facilities
        $facilitiesResponse = $this->actingAs($this->hrUser)->post('/hrd/pengaturan/fasilitas', [
            'facilities' => ['kursi_roda', 'toilet_disabilitas', 'bisindo', 'screen_reader'],
        ]);

        $facilitiesResponse->assertRedirect('/hrd/pengaturan?tab=fasilitas');
    }
}
