<?php

namespace Tests\Feature;

use App\Models\JobVacancy;
use App\Models\User;
use App\Services\MatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchingEngineTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $nadia;

    protected JobVacancy $uiuxJob;

    protected function setUp(): void
    {
        parent::setUp();

        $this->nadia = User::where('email', 'nadia@kerjasetara.id')->first()
            ?? User::where('role', 'kandidat')->first();

        $this->uiuxJob = JobVacancy::whereHas('company', fn ($q) => $q->where('company_name', 'like', '%Teknologi Inovatif%'))->first()
            ?? JobVacancy::where('title', 'like', '%UI/UX Designer%')->first();
    }

    public function test_matching_service_calculates_high_match_for_calibrated_profile(): void
    {
        $service = app(MatchingService::class);
        $result = $service->calculateMatchPercentage($this->nadia, $this->uiuxJob);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('percentage', $result);
        $this->assertArrayHasKey('badge', $result);
        $this->assertArrayHasKey('breakdown', $result);

        // Benchmark check: Nadia on UI/UX Designer should achieve >= 90%
        $this->assertGreaterThanOrEqual(90, $result['percentage']);
        $this->assertEquals('Sangat Cocok', $result['badge']);

        // Check breakdown contains required items
        $features = array_column($result['breakdown'], 'feature');
        $this->assertContains('Akses Kursi Roda', $features);
    }

    public function test_job_model_method_delegates_to_matching_service(): void
    {
        $matching = $this->uiuxJob->calculateMatching($this->nadia);

        $this->assertIsArray($matching);
        $this->assertGreaterThanOrEqual(90, $matching['percentage']);
        $this->assertEquals('Sangat Cocok', $matching['badge']);
        $this->assertNotEmpty($matching['breakdown']);
    }

    public function test_job_detail_page_renders_dynamic_matching_score_and_breakdown(): void
    {
        $response = $this->actingAs($this->nadia)->get("/kandidat/lowongan/{$this->uiuxJob->id}");

        $response->assertStatus(200);
        $response->assertSee('Kecocokan Aksesibilitas Anda');
        $response->assertSee('Sangat Cocok');
        $response->assertSee('Rincian Pencocokan System:');
        $response->assertSee('Akses Kursi Roda');
        $response->assertSee('(Tersedia)');
    }

    public function test_matching_percentage_helper_returns_integer(): void
    {
        $score = $this->uiuxJob->calculateMatchPercentage($this->nadia);

        $this->assertIsInt($score);
        $this->assertGreaterThanOrEqual(0, $score);
        $this->assertLessThanOrEqual(100, $score);
    }
}
