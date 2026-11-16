<?php

namespace Tests\Feature;

use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestAccessTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected JobVacancy $job;

    protected function setUp(): void
    {
        parent::setUp();
        $this->job = JobVacancy::first();
    }

    public function test_guest_can_access_cari_kerja_page_without_auto_login(): void
    {
        $response = $this->get('/kandidat/home');

        $response->assertStatus(200);
        $this->assertGuest();
        $response->assertSee('Cari Kerja');
        $response->assertSee('Masuk');
        $response->assertSee('Daftar Kandidat');
        $response->assertDontSee('Halo, Nadia');
    }

    public function test_guest_can_view_job_detail_and_sees_login_prompt_for_applying(): void
    {
        $response = $this->get("/kandidat/lowongan/{$this->job->id}");

        $response->assertStatus(200);
        $this->assertGuest();
        $response->assertSee('Masuk untuk Melamar');
        $response->assertSee(route('login'));
    }

    public function test_guest_cannot_access_profil_page_and_is_redirected_to_login(): void
    {
        $response = $this->get('/kandidat/profil');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_cannot_access_riwayat_lamaran_page_and_is_redirected_to_login(): void
    {
        $response = $this->get('/kandidat/lamaran');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_cannot_submit_application_and_is_redirected_to_login(): void
    {
        $response = $this->post("/kandidat/lowongan/{$this->job->id}/lamar", [
            'resume_name' => 'CV_Guest.pdf',
            'consent_agreement' => '1',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertDatabaseMissing('job_applications', [
            'resume_name' => 'CV_Guest.pdf',
        ]);
    }

    public function test_guest_cannot_bookmark_and_is_redirected_to_login(): void
    {
        $response = $this->post("/kandidat/lowongan/{$this->job->id}/bookmark");

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
