<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HrdPortalTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $hrdUser;
    protected CompanyProfile $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hrdUser = User::where('email', 'hrd@kerjasetara.id')->first() ?? User::factory()->create([
            'name' => 'Budi HRD',
            'email' => 'hrd@kerjasetara.id',
            'role' => 'hrd',
            'password' => Hash::make('password'),
        ]);

        $this->company = $this->hrdUser->companyProfile ?? CompanyProfile::create([
            'user_id' => $this->hrdUser->id,
            'company_name' => 'PT Solusi Inklusif Indonesia',
            'industry' => 'Teknologi Informasi',
            'location' => 'Jakarta Selatan',
            'description' => 'Profil perusahaan inklusif.',
            'accessibility_features' => ['ramp', 'toilet_khusus', 'screen_reader'],
            'is_inclusive_certified' => true,
        ]);
    }

    public function test_hrd_can_login_via_main_login_and_redirects_to_hrd_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'hrd@kerjasetara.id',
            'password' => 'password',
            'role' => 'hrd',
        ]);

        $response->assertRedirect(route('hrd.dashboard'));
        $this->assertAuthenticatedAs($this->hrdUser);
    }

    public function test_hrd_with_role_company_can_login_and_access_hrd_dashboard(): void
    {
        $companyUser = User::create([
            'name' => 'Recruiter PT Maju',
            'email' => 'recruiter@ptmaju.test',
            'role' => 'company',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'recruiter@ptmaju.test',
            'password' => 'password',
            'role' => 'company',
        ]);

        $response->assertRedirect(route('hrd.dashboard'));
        $this->assertAuthenticatedAs($companyUser);

        // Akses dashboard langsung
        $dashResponse = $this->actingAs($companyUser)->get(route('hrd.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Ringkasan');
    }

    public function test_hrd_without_company_profile_does_not_404_and_auto_initializes(): void
    {
        $freshHrd = User::create([
            'name' => 'New HRD User',
            'email' => 'newhrd@test.id',
            'role' => 'hrd',
            'password' => Hash::make('password'),
        ]);

        // Pastikan companyProfile belum ada
        $this->assertNull($freshHrd->companyProfile);

        // Akses settings / dashboard tidak boleh 404
        $response = $this->actingAs($freshHrd)->get(route('hrd.dashboard'));
        $response->assertStatus(200);

        $settingsResponse = $this->actingAs($freshHrd)->get(route('hrd.pengaturan.index'));
        $settingsResponse->assertStatus(200);

        // Verifikasi CompanyProfile otomatis terbuat
        $freshHrd->refresh();
        $this->assertNotNull($freshHrd->companyProfile);
    }

    public function test_hrd_can_access_dashboard_metrics(): void
    {
        $response = $this->actingAs($this->hrdUser)->get(route('hrd.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Ringkasan');
        $response->assertSee('WORKSPACE EMPLOYER');
        $response->assertSee('Kelola Lowongan');
        $response->assertSee('Kelola Pelamar');
        $response->assertSee('Pengaturan');
    }

    public function test_hrd_can_create_new_job_vacancy(): void
    {
        $response = $this->actingAs($this->hrdUser)->post(route('hrd.lowongan.store'), [
            'title' => 'Quality Assurance (Inklusif)',
            'job_type' => 'Full-Time',
            'location' => 'Jakarta Selatan',
            'salary_estimate' => 'Rp 8-12 juta / bulan',
            'description' => 'Memastikan kualitas platform inklusif.',
            'accessibility_tags' => ['screen_reader', 'kursi_roda'],
            'action' => 'publish',
        ]);

        $response->assertRedirect(route('hrd.lowongan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_vacancies', [
            'company_id' => $this->company->id,
            'title' => 'Quality Assurance (Inklusif)',
            'job_type' => 'Full-Time',
            'is_active' => true,
        ]);
    }

    public function test_hrd_can_toggle_and_update_vacancy(): void
    {
        $vacancy = JobVacancy::create([
            'company_id' => $this->company->id,
            'title' => 'DevOps Engineer',
            'description' => 'Deskripsi pekerjaan',
            'location' => 'Jakarta',
            'job_type' => 'Remote',
            'category' => 'digital',
            'accessibility_tags' => ['wfh_fleksibel'],
            'is_active' => true,
        ]);

        // Toggle status
        $toggleResponse = $this->actingAs($this->hrdUser)->post(route('hrd.lowongan.toggle-status', $vacancy));
        $toggleResponse->assertRedirect(route('hrd.lowongan.index'));

        $vacancy->refresh();
        $this->assertFalse($vacancy->is_active);

        // Update vacancy
        $updateResponse = $this->actingAs($this->hrdUser)->put(route('hrd.lowongan.update', $vacancy), [
            'title' => 'Senior DevOps Engineer',
            'job_type' => 'Hybrid',
            'salary_estimate' => 'Rp 15-25 juta / bulan',
        ]);
        $updateResponse->assertRedirect(route('hrd.lowongan.index'));

        $vacancy->refresh();
        $this->assertEquals('Senior DevOps Engineer', $vacancy->title);
    }

    public function test_hrd_can_update_applicant_status(): void
    {
        $vacancy = JobVacancy::firstOrCreate(
            ['company_id' => $this->company->id, 'title' => 'Frontend Engineer'],
            [
                'description' => 'Deskripsi',
                'location' => 'Jakarta',
                'job_type' => 'Remote',
                'category' => 'digital',
                'accessibility_tags' => ['screen_reader'],
                'is_active' => true,
            ]
        );

        $candidate = User::where('role', 'kandidat')->first();
        $application = JobApplication::create([
            'user_id' => $candidate->id,
            'job_vacancy_id' => $vacancy->id,
            'status' => 'pending',
            'resume_name' => 'CV_Nadia.pdf',
        ]);

        $response = $this->actingAs($this->hrdUser)->post(route('hrd.pelamar.updateStatus', $application), [
            'status' => 'interview',
            'interview_method' => 'Online via Zoom Meeting',
            'interview_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $application->refresh();
        $this->assertEquals('interview', $application->status);
    }

    public function test_hrd_can_update_company_profile(): void
    {
        $response = $this->actingAs($this->hrdUser)->post(route('hrd.pengaturan.updateProfile'), [
            'company_name' => 'PT Solusi Inklusif Indonesia Hebat',
            'location' => 'Jakarta Pusat',
            'phone' => '+62 21 888 9999',
            'website' => 'https://solusi-hebat.id',
        ]);

        $response->assertRedirect(route('hrd.pengaturan.index', ['tab' => 'profil']));
        $this->company->refresh();
        $this->assertEquals('PT Solusi Inklusif Indonesia Hebat', $this->company->company_name);
        $this->assertEquals('Jakarta Pusat', $this->company->location);
    }

    public function test_perusahaan_route_aliases_redirect_to_hrd(): void
    {
        $response = $this->actingAs($this->hrdUser)->get('/perusahaan/dashboard');
        $response->assertRedirect(route('hrd.dashboard'));

        $response2 = $this->get('/perusahaan/login');
        $response2->assertRedirect(route('hrd.login'));
    }

    public function test_non_hrd_user_cannot_access_hrd_dashboard(): void
    {
        $candidate = User::where('role', 'kandidat')->first();

        $response = $this->actingAs($candidate)->get(route('hrd.dashboard'));
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');
    }
}
