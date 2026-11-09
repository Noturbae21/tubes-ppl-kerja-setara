<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MultiRoleLoginTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $adminUser;

    protected User $hrdUser;

    protected User $candidateUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('email', 'admin@kerjasetara.id')->first()
            ?? User::factory()->create([
                'email' => 'admin_test@kerjasetara.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);

        $this->hrdUser = User::where('email', 'hrd@example.com')->first()
            ?? User::factory()->create([
                'email' => 'hrd_test@example.com',
                'password' => Hash::make('password'),
                'role' => 'hrd',
            ]);

        $this->candidateUser = User::where('email', 'nadia@kerjasetara.id')->first()
            ?? User::where('email', 'kandidat@example.com')->first()
            ?? User::factory()->create([
                'email' => 'kandidat_test@example.com',
                'password' => Hash::make('password'),
                'role' => 'kandidat',
            ]);
    }

    public function test_login_page_renders_with_role_selector_and_demo_shortcuts(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Pilih Peran Anda');
        $response->assertSee('Talenta');
        $response->assertSee('HRD / Mitra');
        $response->assertSee('Admin');
        $response->assertSee('Masuk sebagai Pencari Kerja');
        $response->assertSee('nadia@kerjasetara.id');
        $response->assertSee('hrd@example.com');
        $response->assertSee('admin@kerjasetara.id');
    }

    public function test_candidate_can_login_via_user_role(): void
    {
        $response = $this->post('/login', [
            'email' => $this->candidateUser->email,
            'password' => 'password',
            'role' => 'user',
        ]);

        $this->assertAuthenticatedAs($this->candidateUser);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_hrd_can_login_via_hrd_role(): void
    {
        $response = $this->post('/login', [
            'email' => $this->hrdUser->email,
            'password' => 'password',
            'role' => 'hrd',
        ]);

        $this->assertAuthenticatedAs($this->hrdUser);
        $response->assertRedirect(route('hrd.dashboard'));
    }

    public function test_admin_can_login_via_admin_role(): void
    {
        $response = $this->post('/login', [
            'email' => $this->adminUser->email,
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->assertAuthenticatedAs($this->adminUser);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_candidate_cannot_login_under_admin_role_tab(): void
    {
        $response = $this->post('/login', [
            'email' => $this->candidateUser->email,
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_cannot_login_under_candidate_role_tab(): void
    {
        $response = $this->post('/login', [
            'email' => $this->adminUser->email,
            'password' => 'password',
            'role' => 'user',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_invalid_password_is_rejected(): void
    {
        $response = $this->post('/login', [
            'email' => $this->candidateUser->email,
            'password' => 'wrong-password',
            'role' => 'user',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_role_middleware_blocks_unauthorized_user(): void
    {
        // Candidate trying to access Admin dashboard directly
        $response = $this->actingAs($this->candidateUser)->get('/admin/dashboard');
        $response->assertRedirect(route('home'));

        // Candidate trying to access HRD dashboard directly
        $hrdResponse = $this->actingAs($this->candidateUser)->get('/hrd/dashboard');
        $hrdResponse->assertRedirect(route('home'));
    }

    public function test_authenticated_user_can_logout(): void
    {
        $response = $this->actingAs($this->candidateUser)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }
}
