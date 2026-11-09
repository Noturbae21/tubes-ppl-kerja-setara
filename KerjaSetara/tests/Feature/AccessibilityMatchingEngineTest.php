<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\JobVacancy;
use App\Models\User;
use App\Models\UserAccessibilityPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AccessibilityMatchingEngineTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected User $user;

    protected JobVacancy $job;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Talenta Test',
            'email' => 'talenta.test@kerjasetara.id',
            'role' => 'kandidat',
        ]);

        $company = CompanyProfile::first() ?? CompanyProfile::create([
            'user_id' => User::factory()->create(['role' => 'hrd'])->id,
            'company_name' => 'PT Inklusi Digital',
            'industry' => 'Teknologi',
            'location' => 'Jakarta',
            'description' => 'Perusahaan inklusif',
            'accessibility_features' => ['ramp', 'lift'],
        ]);

        $this->job = JobVacancy::create([
            'company_id' => $company->id,
            'title' => 'Accessibility QA Engineer',
            'description' => 'Testing dan audit aksesibilitas sistem.',
            'location' => 'Jakarta',
            'job_type' => 'full-time',
            'category' => 'digital',
            'accessibility_tags' => [
                'wheelchair_access',
                'ramp',
                'lift',
                'sign_language',
                'bisindo',
            ],
            'salary_min' => 8000000,
            'salary_max' => 12000000,
            'is_active' => true,
            'deadline' => now()->addDays(30),
        ]);
    }

    public function test_users_table_does_not_have_accessibility_columns(): void
    {
        $this->assertFalse(Schema::hasColumn('users', 'accessibility_preferences'));
        $this->assertFalse(Schema::hasColumn('users', 'accessibility_notes'));
        $this->assertFalse(Schema::hasColumn('users', 'accessibility_category'));
    }

    public function test_user_has_one_accessibility_preference_relation(): void
    {
        $pref = UserAccessibilityPreference::create([
            'user_id' => $this->user->id,
            'category' => 'fisik',
            'wheelchair_access' => true,
            'sign_language' => true,
            'screen_reader' => false,
            'quiet_room' => true,
        ]);

        $this->assertInstanceOf(UserAccessibilityPreference::class, $this->user->accessibilityPreference);
        $this->assertEquals($pref->id, $this->user->accessibilityPreference->id);
        $this->assertTrue($this->user->accessibilityPreference->wheelchair_access);
        $this->assertTrue($this->user->accessibilityPreference->sign_language);
        $this->assertFalse($this->user->accessibilityPreference->screen_reader);
    }

    public function test_job_controller_calculates_exact_percentage_using_formula(): void
    {
        // User has 4 active preferences:
        // 1. wheelchair_access -> Matched (in job tags)
        // 2. sign_language     -> Matched (in job tags)
        // 3. braille           -> NOT matched
        // 4. quiet_room        -> NOT matched
        // Formula: (2 / 4) * 100 = 50%
        UserAccessibilityPreference::create([
            'user_id' => $this->user->id,
            'wheelchair_access' => true,
            'sign_language' => true,
            'braille' => true,
            'quiet_room' => true,
        ]);

        $response = $this->actingAs($this->user)->get(route('job.show', $this->job->id));

        $response->assertStatus(200);
        $response->assertViewHas('matchPercentage', 50);
        $response->assertViewHas('breakdown');
        $response->assertSee('50%');
        $response->assertSee('Akses Kursi Roda');
        $response->assertSee('(Tersedia)');
        $response->assertSee('Materi / Navigasi Braille');
        $response->assertSee('(Tidak Disebutkan)');
    }

    public function test_job_controller_100_percent_match_when_all_preferences_satisfied(): void
    {
        // User has 2 active preferences, both matched:
        // (2 / 2) * 100 = 100%
        UserAccessibilityPreference::create([
            'user_id' => $this->user->id,
            'wheelchair_access' => true,
            'sign_language' => true,
        ]);

        $response = $this->actingAs($this->user)->get(route('lowongan.show', $this->job->id));

        $response->assertStatus(200);
        $response->assertViewHas('matchPercentage', 100);
        $response->assertSee('100%');
        $response->assertSee('Sangat Cocok');
    }

    public function test_breakdown_list_is_rendered_dynamically_without_hardcoded_text(): void
    {
        UserAccessibilityPreference::create([
            'user_id' => $this->user->id,
            'sign_language' => true,
            'braille' => true,
        ]);

        $response = $this->actingAs($this->user)->get(route('jobs.show', $this->job->id));

        $response->assertStatus(200);
        // sign_language is available
        $response->assertSee('Penerjemah Isyarat');
        $response->assertSee('(Tersedia)');
        // braille is not in job tags
        $response->assertSee('Materi / Navigasi Braille');
        $response->assertSee('(Tidak Disebutkan)');
    }
}
