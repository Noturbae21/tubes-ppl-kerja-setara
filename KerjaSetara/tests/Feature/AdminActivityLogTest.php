<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $candidate;

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

        ActivityLog::create([
            'user_id' => $this->candidate->id,
            'user_name' => 'Nadia Saraswati',
            'user_role' => 'kandidat',
            'action_type' => 'create',
            'description' => 'Melamar posisi UI/UX Designer di PT Teknologi Inovatif.',
            'level' => 'info',
            'result' => 'Berhasil',
            'ip_address' => '192.0.2.24',
        ]);

        ActivityLog::create([
            'user_id' => null,
            'user_name' => 'Aktor tidak dikenal',
            'user_role' => 'tamu',
            'action_type' => 'login_failed',
            'description' => 'Percobaan masuk ditolak karena kata sandi tidak sesuai.',
            'level' => 'warning',
            'result' => 'Gagal',
            'ip_address' => '198.51.100.72',
        ]);

        ActivityLog::create([
            'user_id' => null,
            'user_name' => 'Sistem',
            'user_role' => 'sistem',
            'action_type' => 'access_denied',
            'description' => 'Token sesi tidak valid; akses ke endpoint admin dibatalkan.',
            'level' => 'error',
            'result' => 'Ditolak',
            'ip_address' => '203.0.113.42',
        ]);
    }

    public function test_guest_is_redirected_from_activity_logs(): void
    {
        $response = $this->get(route('admin.logs.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_candidate_cannot_access_activity_logs(): void
    {
        $response = $this->actingAs($this->candidate)->get(route('admin.logs.index'));
        $response->assertRedirect(route('home'));
    }

    public function test_admin_can_view_activity_logs_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.logs.index'));

        $response->assertOk();
        $response->assertSee('Log Aktivitas Sistem & Keamanan');
        $response->assertSee('Melamar posisi UI/UX Designer di PT Teknologi Inovatif.');
        $response->assertSee('192.0.2.24');
        $response->assertSee('Berhasil');
        $response->assertSee('Ditolak');
    }

    public function test_admin_can_filter_activity_logs_by_level(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.logs.index', ['level' => 'error']));

        $response->assertOk();
        $response->assertSee('Token sesi tidak valid');
        $response->assertDontSee('Melamar posisi UI/UX Designer');
    }

    public function test_admin_can_search_activity_logs(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.logs.index', ['search' => 'Nadia']));

        $response->assertOk();
        $response->assertSee('Melamar posisi UI/UX Designer');
        $response->assertDontSee('Token sesi tidak valid');
    }
}
