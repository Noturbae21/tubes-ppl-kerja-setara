<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KandidatProfilTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $kandidat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kandidat = User::where('role', 'kandidat')->first() ?? User::factory()->create([
            'role' => 'kandidat',
            'name' => 'Nadia',
            'full_name' => 'Nadia Saraswati',
            'email' => 'nadia@kerjasetara.id',
            'headline' => 'UI/UX Designer & Accessibility Advocate',
            'phone' => '+62 812 3456 7890',
            'city' => 'Jakarta, Indonesia',
        ]);
    }

    public function test_kandidat_can_access_profil_page(): void
    {
        $response = $this->actingAs($this->kandidat)->get('/kandidat/profil');

        $response->assertStatus(200);
        $response->assertSee('Informasi Pribadi');
        $response->assertSee('Kebutuhan Aksesibilitas');
        $response->assertSee('Manajemen CV');
        $response->assertSee('Pengaturan Akun');
        $response->assertSee('Nadia Saraswati');
    }

    public function test_profil_menu_in_navbar_links_to_kandidat_profil(): void
    {
        $response = $this->actingAs($this->kandidat)->get('/kandidat/profil');

        $response->assertStatus(200);
        $response->assertSee(route('kandidat.profil'));
    }

    public function test_kandidat_can_update_personal_information(): void
    {
        $response = $this->actingAs($this->kandidat)->post('/kandidat/profil', [
            '_section'  => 'personal',
            'name'      => 'Nadia Updated',
            'full_name' => 'Nadia Saraswati Updated',
            'headline'  => 'Senior Product Designer',
            'phone'     => '+62 811 9999 8888',
            'city'      => 'Bandung, Indonesia',
            'bio'       => 'Desainer produk fokus inklusif.',
        ]);

        $response->assertRedirect('/kandidat/profil?tab=personal');
        $response->assertSessionHas('success', 'Profil berhasil diperbarui!');

        $this->assertDatabaseHas('users', [
            'id'        => $this->kandidat->id,
            'name'      => 'Nadia Updated',
            'full_name' => 'Nadia Saraswati Updated',
            'headline'  => 'Senior Product Designer',
            'city'      => 'Bandung, Indonesia',
        ]);
    }

    public function test_kandidat_can_update_accessibility_preferences(): void
    {
        $newPreferences = ['kursi_roda', 'bisindo', 'quiet_room'];

        $response = $this->actingAs($this->kandidat)->post('/kandidat/profil', [
            '_section'                  => 'preferensi',
            'accessibility_preferences' => $newPreferences,
            'accessibility_notes'       => 'Memerlukan ruangan tenang saat jam istirahat.',
        ]);

        $response->assertRedirect('/kandidat/profil?tab=accessibility');
        $response->assertSessionHas('success', 'Preferensi aksesibilitas berhasil diperbarui!');

        $this->kandidat->refresh();
        $this->assertEquals($newPreferences, $this->kandidat->accessibility_preferences);
        $this->assertEquals('Memerlukan ruangan tenang saat jam istirahat.', $this->kandidat->accessibility_notes);
    }

    public function test_kandidat_can_update_cv_and_portfolio_links(): void
    {
        $response = $this->actingAs($this->kandidat)->post('/kandidat/profil', [
            '_section'      => 'cv',
            'portfolio_url' => 'https://dribbble.com/nadiasaras',
            'linkedin_url'  => 'https://linkedin.com/in/nadiasaras-pro',
            'resume_file'   => 'CV_Nadia_2026_Updated.pdf',
        ]);

        $response->assertRedirect('/kandidat/profil?tab=cv');
        $response->assertSessionHas('success', 'Dokumen CV dan tautan portofolio berhasil disimpan!');

        $this->assertDatabaseHas('users', [
            'id'            => $this->kandidat->id,
            'portfolio_url' => 'https://dribbble.com/nadiasaras',
            'linkedin_url'  => 'https://linkedin.com/in/nadiasaras-pro',
            'resume_file'   => 'CV_Nadia_2026_Updated.pdf',
        ]);
    }
}
