<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatLamaranTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $kandidat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kandidat = User::where('email', 'nadia@kerjasetara.id')->first()
            ?? User::where('role', 'kandidat')->first();
    }

    public function test_kandidat_can_access_riwayat_lamaran_page(): void
    {
        $response = $this->actingAs($this->kandidat)->get('/kandidat/lamaran');

        $response->assertStatus(200);
        $response->assertSee('Riwayat Lamaran Saya');
        $response->assertSee('Pantau status lamaran pekerjaan dan tahapan seleksi rekrutmen Anda di sini.');

        // Memastikan 4 filter kategori lamaran tersedia
        $response->assertSee('Semua Lamaran');
        $response->assertSee('Menunggu Review');
        $response->assertSee('Wawancara');
        $response->assertSee('Diterima');
        $response->assertSee('Ditolak');

        // Memastikan posisi pekerjaan & perusahaan dari 4 baris data tampil
        $response->assertSee('UI/UX Designer');
        $response->assertSee('PT Teknologi Inovatif');
        $response->assertSee('Tahap Wawancara');
        $response->assertSee('Lihat Detail Jadwal');

        $response->assertSee('Frontend Developer');
        $response->assertSee('Nusa Digital');
        $response->assertSee('Menunggu Review HR');
        $response->assertSee('Batalkan Lamaran');

        $response->assertSee('Customer Support');
        $response->assertSee('PT Solusi Bersama');
        $response->assertSee('Tawaran Kerja Diterima');
        $response->assertSee('Lihat Penawaran');

        $response->assertSee('Data Analyst');
        $response->assertSee('Arunika Finance');
        $response->assertSee('Tidak Dilanjutkan');
        $response->assertSee('Lihat Catatan');

        // Memastikan modal dan teks aksesibilitas tersedia di HTML DOM
        // (Interview assistive_facilities dikirim via Alpine @click JSON, tidak muncul sebagai teks DOM)
        $response->assertSee('Fasilitas asistif yang disiapkan perusahaan');
        $response->assertSee('Buka Link Meeting Zoom');
        $response->assertSee('Terima Penawaran Kerja');
        $response->assertSee('Ya, Batalkan Lamaran');
    }

    public function test_riwayat_lamaran_alias_redirects_to_lamaran(): void
    {
        $response = $this->actingAs($this->kandidat)->get('/kandidat/riwayat-lamaran');

        $response->assertRedirect('/kandidat/lamaran');
    }

    public function test_filter_status_returns_matching_results(): void
    {
        // Filter wawancara
        $response = $this->actingAs($this->kandidat)->get('/kandidat/lamaran?status=interview');
        $response->assertStatus(200);
        $response->assertSee('UI/UX Designer');

        // Filter diterima
        $responseAccept = $this->actingAs($this->kandidat)->get('/kandidat/lamaran?status=accepted');
        $responseAccept->assertStatus(200);
        $responseAccept->assertSee('Customer Support');
    }

    public function test_kandidat_can_cancel_pending_application(): void
    {
        $app = JobApplication::where('user_id', $this->kandidat->id)
            ->where('status', 'pending')
            ->first();

        $this->assertNotNull($app);

        $response = $this->actingAs($this->kandidat)
            ->post("/kandidat/lamaran/{$app->id}/batal", [
                'reason' => 'Menemukan peluang lain yang lebih cocok.',
            ]);

        $response->assertRedirect('/kandidat/lamaran');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_applications', [
            'id' => $app->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_kandidat_can_accept_job_offer(): void
    {
        $app = JobApplication::where('user_id', $this->kandidat->id)
            ->where('status', 'accepted')
            ->first();

        $this->assertNotNull($app);

        $response = $this->actingAs($this->kandidat)
            ->post("/kandidat/lamaran/{$app->id}/terima-tawaran");

        $response->assertRedirect('/kandidat/lamaran');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_applications', [
            'id' => $app->id,
            'offer_status' => 'accepted',
        ]);
    }

    public function test_kandidat_can_reject_job_offer(): void
    {
        $app = JobApplication::where('user_id', $this->kandidat->id)
            ->where('status', 'accepted')
            ->first();

        $this->assertNotNull($app);

        $response = $this->actingAs($this->kandidat)
            ->post("/kandidat/lamaran/{$app->id}/tolak-tawaran");

        $response->assertRedirect('/kandidat/lamaran');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_applications', [
            'id' => $app->id,
            'offer_status' => 'rejected',
        ]);
    }
}
