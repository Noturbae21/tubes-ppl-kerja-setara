<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\CompanyProfile;
use App\Models\Facility;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFacilityManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $hrd;
    protected CompanyProfile $company;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->hrd = User::factory()->create([
            'role' => 'hrd',
            'status' => 'active',
        ]);

        $this->company = CompanyProfile::create([
            'user_id' => $this->hrd->id,
            'company_name' => 'PT Inklusi Mandiri',
            'industry' => 'Teknologi',
            'location' => 'Jakarta',
            'description' => 'Perusahaan ramah disabilitas',
            'accessibility_features' => [],
            'employee_count' => 100,
        ]);
    }

    /**
     * Helper to create a valid, safe SVG UploadedFile.
     */
    protected function createSafeSvgFile(string $name = 'test-icon.svg', int $sizeKb = 2): UploadedFile
    {
        $svgContent = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>';
        return UploadedFile::fake()->createWithContent($name, $svgContent);
    }

    public function test_guest_is_redirected_from_facilities_page(): void
    {
        $response = $this->get(route('admin.facilities.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_facilities_index_page(): void
    {
        Facility::create([
            'name' => 'Akses Kursi Roda',
            'description' => 'Ramp dan lift ramah kursi roda',
            'icon_path' => 'facilities/icons/wheelchair.svg',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.facilities.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Master Data Fasilitas Aksesibilitas');
        $response->assertSee('Akses Kursi Roda');
        $response->assertSee('FAS-001');
    }

    public function test_facilities_can_be_filtered_by_status(): void
    {
        Facility::create([
            'name' => 'Fasilitas Aktif Satu',
            'description' => 'Deskripsi aktif',
            'is_active' => true,
        ]);

        Facility::create([
            'name' => 'Fasilitas Nonaktif Dua',
            'description' => 'Deskripsi nonaktif',
            'is_active' => false,
        ]);

        // Filter: active
        $responseActive = $this->actingAs($this->admin)->get(route('admin.facilities.index', ['status' => 'active']));
        $responseActive->assertOk();
        $responseActive->assertSee('Fasilitas Aktif Satu');
        $responseActive->assertDontSee('Fasilitas Nonaktif Dua');

        // Filter: inactive
        $responseInactive = $this->actingAs($this->admin)->get(route('admin.facilities.index', ['status' => 'inactive']));
        $responseInactive->assertOk();
        $responseInactive->assertDontSee('Fasilitas Aktif Satu');
        $responseInactive->assertSee('Fasilitas Nonaktif Dua');
    }

    public function test_facilities_search_by_name_and_identifier(): void
    {
        $f1 = Facility::create([
            'name' => 'Lift Khusus Prioritas',
            'description' => 'Lift tombol rendah dan suara',
            'is_active' => true,
        ]);

        $f2 = Facility::create([
            'name' => 'Screen Reader Friendly',
            'description' => 'Kompatibel dengan JAWS dan NVDA',
            'is_active' => true,
        ]);

        // Search by keyword
        $responseSearch = $this->actingAs($this->admin)->get(route('admin.facilities.index', ['search' => 'Screen Reader']));
        $responseSearch->assertOk();
        $responseSearch->assertSee('Screen Reader Friendly');
        $responseSearch->assertDontSee('Lift Khusus Prioritas');

        // Search by FAS-001 identifier
        $responseId = $this->actingAs($this->admin)->get(route('admin.facilities.index', ['search' => $f1->identifier]));
        $responseId->assertOk();
        $responseId->assertSee('Lift Khusus Prioritas');
        $responseId->assertDontSee('Screen Reader Friendly');
    }

    public function test_admin_can_store_facility_with_valid_svg(): void
    {
        $svgFile = $this->createSafeSvgFile('kursi-roda.svg');

        $response = $this->actingAs($this->admin)->post(route('admin.facilities.store'), [
            'name' => 'Akses Ramp Khusus',
            'description' => 'Jalur bidang miring landai bebas hambatan',
            'icon' => $svgFile,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.facilities.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('facilities', [
            'name' => 'Akses Ramp Khusus',
            'is_active' => true,
        ]);

        $facility = Facility::where('name', 'Akses Ramp Khusus')->first();
        $this->assertNotNull($facility);
        $this->assertNotNull($facility->icon_path);
        Storage::disk('public')->assertExists($facility->icon_path);

        // Check ActivityLog
        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'create',
            'target_type' => 'facility',
            'target_id' => $facility->id,
        ]);
    }

    public function test_duplicate_facility_name_is_rejected(): void
    {
        Facility::create([
            'name' => 'Akses Kursi Roda',
            'description' => 'Ramp dan lift',
            'is_active' => true,
        ]);

        $svgFile = $this->createSafeSvgFile('test.svg');

        $response = $this->actingAs($this->admin)->post(route('admin.facilities.store'), [
            'name' => 'Akses Kursi Roda', // duplicate
            'description' => 'Deskripsi lain',
            'icon' => $svgFile,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_svg_with_script_tag_is_rejected_and_sanitized(): void
    {
        $maliciousSvg = '<svg xmlns="http://www.w3.org/2000/svg"><script>alert("XSS Attack!");</script><circle cx="12" cy="12" r="10"/></svg>';
        $file = UploadedFile::fake()->createWithContent('exploit.svg', $maliciousSvg);

        $response = $this->actingAs($this->admin)->post(route('admin.facilities.store'), [
            'name' => 'Fasilitas Exploit Script',
            'description' => 'Deskripsi uji coba',
            'icon' => $file,
        ]);

        $response->assertSessionHasErrors('icon');
        $this->assertDatabaseMissing('facilities', ['name' => 'Fasilitas Exploit Script']);
    }

    public function test_svg_with_dangerous_event_handler_is_rejected(): void
    {
        $maliciousSvg = '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(document.cookie)"><circle cx="12" cy="12" r="10"/></svg>';
        $file = UploadedFile::fake()->createWithContent('exploit_event.svg', $maliciousSvg);

        $response = $this->actingAs($this->admin)->post(route('admin.facilities.store'), [
            'name' => 'Fasilitas Exploit Event',
            'description' => 'Deskripsi uji coba',
            'icon' => $file,
        ]);

        $response->assertSessionHasErrors('icon');
        $this->assertDatabaseMissing('facilities', ['name' => 'Fasilitas Exploit Event']);
    }

    public function test_svg_exceeding_100kb_is_rejected(): void
    {
        // 120 KB file
        $bigContent = '<svg xmlns="http://www.w3.org/2000/svg">' . str_repeat('<!-- padding -->', 8000) . '<circle cx="12" cy="12" r="10"/></svg>';
        $file = UploadedFile::fake()->createWithContent('large.svg', $bigContent);

        $response = $this->actingAs($this->admin)->post(route('admin.facilities.store'), [
            'name' => 'Fasilitas Gambar Besar',
            'description' => 'Deskripsi',
            'icon' => $file,
        ]);

        $response->assertSessionHasErrors('icon');
        $this->assertDatabaseMissing('facilities', ['name' => 'Fasilitas Gambar Besar']);
    }

    public function test_facility_show_json_endpoint_returns_data_for_modal(): void
    {
        $facility = Facility::create([
            'name' => 'Akses Toilet Ramah Disabilitas',
            'description' => 'Grab bar dan pintu lebar',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->getJson(route('admin.facilities.show', $facility));

        $response->assertOk();
        $response->assertJson([
            'id' => $facility->id,
            'identifier' => $facility->identifier,
            'name' => 'Akses Toilet Ramah Disabilitas',
            'description' => 'Grab bar dan pintu lebar',
            'is_active' => true,
            'usage_count' => 0,
            'is_in_use' => false,
        ]);
    }

    public function test_admin_can_update_facility(): void
    {
        $facility = Facility::create([
            'name' => 'Penerjemah Isyarat Lama',
            'description' => 'Deskripsi awal',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.facilities.update', $facility), [
            'name' => 'Layanan Penerjemah Isyarat (BISINDO)',
            'description' => 'Dukungan juru bahasa isyarat untuk seluruh rapat',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.facilities.index'));
        $response->assertSessionHas('success');

        $facility->refresh();
        $this->assertEquals('Layanan Penerjemah Isyarat (BISINDO)', $facility->name);
        $this->assertEquals('Dukungan juru bahasa isyarat untuk seluruh rapat', $facility->description);

        // Check ActivityLog
        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'update',
            'target_type' => 'facility',
            'target_id' => $facility->id,
        ]);
    }

    public function test_admin_can_toggle_facility_status(): void
    {
        $facility = Facility::create([
            'name' => 'Parkir Khusus',
            'description' => 'Slot parkir lebar',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.facilities.toggle-status', $facility));

        $response->assertRedirect();
        $facility->refresh();
        $this->assertFalse($facility->is_active);

        // Toggle back to active
        $this->actingAs($this->admin)->post(route('admin.facilities.toggle-status', $facility));
        $facility->refresh();
        $this->assertTrue($facility->is_active);
    }

    public function test_facility_in_use_cannot_be_deleted_and_admin_is_blocked(): void
    {
        $facility = Facility::create([
            'name' => 'Akses Kursi Roda Penting',
            'description' => 'Digunakan oleh lowongan kerja',
            'is_active' => true,
        ]);

        $vacancy = JobVacancy::create([
            'company_id' => $this->company->id,
            'title' => 'Software Engineer Inklusif',
            'description' => 'Pekerjaan rekayasa perangkat lunak',
            'location' => 'Jakarta',
            'job_type' => 'full-time',
            'category' => 'digital',
            'accessibility_tags' => ['kursi_roda'],
            'is_active' => true,
        ]);

        // Attach facility to vacancy
        $facility->jobVacancies()->attach($vacancy->id);

        $this->assertEquals(1, $facility->fresh()->usage_count);

        // Attempt deletion via DELETE route
        $response = $this->actingAs($this->admin)->delete(route('admin.facilities.destroy', $facility));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Verify facility was NOT deleted
        $this->assertDatabaseHas('facilities', [
            'id' => $facility->id,
            'name' => 'Akses Kursi Roda Penting',
        ]);

        // Verify blocked attempt logged in activity log
        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'delete_blocked',
            'target_type' => 'facility',
            'target_id' => $facility->id,
            'result' => 'failed',
        ]);
    }

    public function test_facility_with_zero_usage_can_be_deleted(): void
    {
        $facility = Facility::create([
            'name' => 'Fasilitas Uji Coba Nol Penggunaan',
            'description' => 'Belum terhubung ke lowongan manapun',
            'is_active' => true,
        ]);

        $facilityId = $facility->id;
        $this->assertEquals(0, $facility->usage_count);

        $response = $this->actingAs($this->admin)->delete(route('admin.facilities.destroy', $facility));

        $response->assertRedirect(route('admin.facilities.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('facilities', [
            'id' => $facilityId,
        ]);

        // Verify delete activity logged
        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'delete',
            'target_type' => 'facility',
            'target_id' => $facilityId,
        ]);
    }

    public function test_inactive_facility_cannot_be_selected_for_new_vacancy_but_existing_relations_retained(): void
    {
        $activeFacility = Facility::create([
            'name' => 'Akses Aktif',
            'description' => 'Fasilitas aktif',
            'is_active' => true,
        ]);

        $inactiveFacility = Facility::create([
            'name' => 'Akses Nonaktif Dihentikan',
            'description' => 'Fasilitas nonaktif',
            'is_active' => false,
        ]);

        // 1. HRD creates new vacancy attempting to attach inactive facility
        $responseCreate = $this->actingAs($this->hrd)->post(route('hrd.lowongan.store'), [
            'title' => 'Lowongan Baru HRD',
            'job_type' => 'Full-Time',
            'location' => 'Jakarta',
            'description' => 'Deskripsi pekerjaan',
            'accessibility_tags' => [(string) $activeFacility->id, (string) $inactiveFacility->id],
            'action' => 'publish',
        ]);

        $responseCreate->assertRedirect(route('hrd.lowongan.index'));

        $vacancy = JobVacancy::where('title', 'Lowongan Baru HRD')->first();
        $this->assertNotNull($vacancy);

        // Inactive facility was excluded from new vacancy attachments
        $this->assertTrue($vacancy->facilities->contains($activeFacility));
        $this->assertFalse($vacancy->facilities->contains($inactiveFacility));

        // 2. Existing relation on another vacancy is preserved
        $existingVacancy = JobVacancy::create([
            'company_id' => $this->company->id,
            'title' => 'Lowongan Lama',
            'description' => 'Deskripsi lama',
            'location' => 'Bandung',
            'job_type' => 'Full-Time',
            'category' => 'digital',
            'is_active' => true,
        ]);
        $existingVacancy->facilities()->attach($inactiveFacility->id);

        $existingVacancy->refresh();
        $this->assertTrue($existingVacancy->facilities->contains($inactiveFacility));

        // Update title of existing vacancy without removing inactive tag
        $this->actingAs($this->hrd)->put(route('hrd.lowongan.update', $existingVacancy), [
            'title' => 'Lowongan Lama Diperbarui',
            'job_type' => 'Full-Time',
            'accessibility_tags' => [(string) $inactiveFacility->id],
        ]);

        $existingVacancy->refresh();
        // Inactive facility is retained on existing vacancy!
        $this->assertTrue($existingVacancy->facilities->contains($inactiveFacility));
    }
}
