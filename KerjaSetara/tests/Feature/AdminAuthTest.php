<?php

namespace Tests\Feature;

use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_admin_login_page_loads_successfully(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Otentikasi Administrator');
        $response->assertSee('Alamat Email Administrator');
        $response->assertSee('Masuk as Administrator');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@kerjasetara.id',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isAdmin());
    }

    public function test_non_admin_cannot_login_through_admin_portal(): void
    {
        // Try logging in with a candidate's credentials on /admin/login
        $response = $this->post('/admin/login', [
            'email' => 'nadia@kerjasetara.id',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_cannot_login_with_invalid_password(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@kerjasetara.id',
            'password' => 'wrongpassword123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    public function test_candidate_cannot_access_admin_dashboard(): void
    {
        $candidate = User::where('role', 'kandidat')->first() ?? User::factory()->create(['role' => 'kandidat']);

        $response = $this->actingAs($candidate)->get('/admin/dashboard');

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_admin_can_access_dashboard_and_view_metrics(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Ringkasan & Statistik Platform');
        $response->assertSee('Total Talenta');
        $response->assertSee('Perusahaan / HRD');
        $response->assertSee('Lowongan Aktif');
        $response->assertSee('Moderasi Lowongan Kerja Terbaru');
    }

    public function test_admin_can_access_users_management(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna Platform');
        $response->assertSee('Talenta / User');
        $response->assertSee('Perusahaan / HRD');
    }

    public function test_admin_can_access_vacancies_moderation(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/lowongan');

        $response->assertStatus(200);
        $response->assertSee('Moderasi & Verifikasi Lowongan');
        $response->assertSee('Semua Lowongan');
    }

    public function test_admin_can_toggle_vacancy_moderation_status(): void
    {
        $admin = User::where('role', 'admin')->first();
        $vacancy = JobVacancy::first();

        $initialStatus = $vacancy->is_active;

        $response = $this->actingAs($admin)->post("/admin/lowongan/{$vacancy->id}/toggle-status");

        $response->assertSessionHas('success');
        $vacancy->refresh();
        $this->assertEquals(! $initialStatus, $vacancy->is_active);
    }

    public function test_admin_can_access_settings_and_taxonomies(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/settings');

        $response->assertStatus(200);
        $response->assertSee('Standar Taksonomi Aksesibilitas KerjaSetara');
        $response->assertSee('Mobilitas Fisik & Lingkungan');
        $response->assertSee('Komunikasi Inklusif & Bahasa Isyarat');
        $response->assertSee('Dukungan Digital & Teknologi Asistif');
        $response->assertSee('Lingkungan Neurodiverse & Sensori');
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post('/admin/logout');

        $response->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_hrd_login_page_loads_successfully(): void
    {
        $response = $this->get('/hrd/login');

        $response->assertStatus(200);
        $response->assertSee('Portal HRD', false);
    }
}
