<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KerjaSetaraTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_landing_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('KerjaSetara');
        $response->assertSee('Platform Karir Inklusif No. 1 di Indonesia');
        $response->assertSee('Temukan lowongan', false);
    }

    public function test_login_page_loads_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali!');
        $response->assertSee('Email / Nama Pengguna');
        $response->assertSee('Kata Sandi');
        $response->assertSee('Masuk');
    }

    public function test_register_page_loads_successfully(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Buat Akun Baru');
        $response->assertSee('Pencari Kerja');
        $response->assertSee('Perusahaan / HR');
        $response->assertSee('Kebutuhan Aksesibilitas');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'kandidat@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'kandidat@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_new_user_can_register_as_candidate(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'kandidat',
            'accessibility_category' => 'fisik',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'budi.santoso@example.com',
            'role' => 'kandidat',
            'accessibility_category' => 'fisik',
        ]);
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::where('email', 'kandidat@example.com')->first();
        if (! $user) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Halo,');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::where('email', 'kandidat@example.com')->first();
        if (! $user) {
            $user = User::factory()->create();
        }

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
