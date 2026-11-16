<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use App\Models\UserAccessibilityPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $kandidat;

    private User $hrd;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('email', 'admin@kerjasetara.id')->first() ?? User::factory()->create([
            'email' => 'admin@kerjasetara.id',
            'role' => 'admin',
        ]);
        $this->admin->update([
            'name' => 'Fakhri Izzul Haq',
            'status' => 'active',
            'password' => Hash::make('password'),
        ]);

        User::whereIn('email', ['budi.santoso@example.com', 'hrd@nusadigital.test'])->delete();

        $this->kandidat = User::where('email', 'nadia.saraswati@example.com')->first() ?? User::factory()->create([
            'name' => 'Nadia Saraswati',
            'email' => 'nadia.saraswati@example.com',
            'role' => 'kandidat',
            'status' => 'active',
            'password' => Hash::make('password'),
        ]);

        $this->hrd = User::where('email', 'hrd@teknologi-inovasi.test')->first() ?? User::factory()->create([
            'name' => 'PT Teknologi Inovasi',
            'email' => 'hrd@teknologi-inovasi.test',
            'role' => 'hrd',
            'status' => 'active',
            'password' => Hash::make('password'),
        ]);

        $company = CompanyProfile::where('user_id', $this->hrd->id)->first() ?? CompanyProfile::create([
            'user_id' => $this->hrd->id,
            'company_name' => 'PT Teknologi Inovasi',
            'industry' => 'Teknologi Informasi',
            'location' => 'Jakarta Pusat',
            'description' => 'Solusi teknologi inklusif.',
            'accessibility_features' => ['ramp', 'screen_reader'],
            'is_inclusive_certified' => true,
        ]);
    }

    public function test_guest_and_non_admin_cannot_access_user_management(): void
    {
        $response = $this->get(route('admin.users'));
        $response->assertRedirect(route('admin.login'));

        $response = $this->actingAs($this->kandidat)->get(route('admin.users'));
        $response->assertRedirect(route('home'));

        $response = $this->actingAs($this->hrd)->get(route('admin.users'));
        $response->assertRedirect(route('home'));
    }

    public function test_admin_can_view_user_management_page_with_data(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna Platform');
        $response->assertSee('Nadia Saraswati');
        $response->assertSee('PT Teknologi Inovasi');
        $response->assertSee('Fakhri Izzul Haq');
        $response->assertSee('Kandidat');
        $response->assertSee('Perusahaan');
        $response->assertSee('Administrator');
        $response->assertSee('+ Tambah User Baru');
    }

    public function test_admin_can_search_users_by_name_and_email(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users', ['search' => 'Nadia']));
        $response->assertStatus(200);
        $response->assertSee('Nadia Saraswati');
        $response->assertDontSee('PT Teknologi Inovasi');

        $response = $this->actingAs($this->admin)->get(route('admin.users', ['search' => 'teknologi-inovasi']));
        $response->assertStatus(200);
        $response->assertSee('PT Teknologi Inovasi');
        $response->assertDontSee('Nadia Saraswati');
    }

    public function test_admin_can_filter_users_by_role_and_status(): void
    {
        // Filter Role: Kandidat
        $response = $this->actingAs($this->admin)->get(route('admin.users', ['role' => 'kandidat']));
        $response->assertStatus(200);
        $response->assertSee('Nadia Saraswati');
        $response->assertDontSee('PT Teknologi Inovasi');

        // Filter Role: HRD / Perusahaan
        $response = $this->actingAs($this->admin)->get(route('admin.users', ['role' => 'hrd']));
        $response->assertStatus(200);
        $response->assertSee('PT Teknologi Inovasi');
        $response->assertDontSee('Nadia Saraswati');

        // Create a blocked user
        $blockedUser = User::factory()->create([
            'name' => 'Akun Terblokir',
            'email' => 'blocked@example.com',
            'role' => 'kandidat',
            'status' => 'blocked',
        ]);

        // Filter Status: Active
        $response = $this->actingAs($this->admin)->get(route('admin.users', ['status' => 'active']));
        $response->assertStatus(200);
        $response->assertSee('Nadia Saraswati');
        $response->assertDontSee('Akun Terblokir');

        // Filter Status: Blocked
        $response = $this->actingAs($this->admin)->get(route('admin.users', ['status' => 'blocked']));
        $response->assertStatus(200);
        $response->assertSee('Akun Terblokir');
        $response->assertDontSee('Nadia Saraswati');
    }

    public function test_admin_can_create_new_candidate_user(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'role' => 'kandidat',
            'status' => 'active',
            'password' => 'password123',
            'accessibility_category' => 'fisik',
        ]);

        $response->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', [
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'role' => 'kandidat',
            'status' => 'active',
        ]);

        $newUser = User::where('email', 'budi.santoso@example.com')->first();
        $this->assertTrue(Hash::check('password123', $newUser->password));
        $this->assertDatabaseHas('user_accessibility_preferences', [
            'user_id' => $newUser->id,
            'category' => 'fisik',
        ]);

        // Verify Activity Log
        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'create',
            'target_type' => 'user',
            'target_id' => $newUser->id,
        ]);
    }

    public function test_admin_can_create_new_hrd_mapped_from_perusahaan_label(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'PT Nusa Digital',
            'email' => 'hrd@nusadigital.test',
            'role' => 'perusahaan', // Label from UI
            'status' => 'active',
            'must_change_password' => '1',
        ]);

        $response->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', [
            'name' => 'PT Nusa Digital',
            'email' => 'hrd@nusadigital.test',
            'role' => 'hrd', // Correctly mapped to HRD
            'must_change_password' => true,
        ]);

        $newCompanyUser = User::where('email', 'hrd@nusadigital.test')->first();
        $this->assertDatabaseHas('company_profiles', [
            'user_id' => $newCompanyUser->id,
            'company_name' => 'PT Nusa Digital',
        ]);
    }

    public function test_create_user_validates_required_fields_and_unique_email(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'role' => 'invalid-role',
            'status' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'role', 'status']);

        // Duplicate email
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Duplikat',
            'email' => 'nadia.saraswati@example.com',
            'role' => 'kandidat',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_admin_can_view_user_detail_json_without_sensitive_credentials(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.show', $this->kandidat->id));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $this->kandidat->id,
            'name' => 'Nadia Saraswati',
            'email' => 'nadia.saraswati@example.com',
            'role' => 'kandidat',
            'status' => 'active',
        ]);

        $json = $response->json();
        $this->assertArrayNotHasKey('password', $json);
        $this->assertArrayNotHasKey('remember_token', $json);
    }

    public function test_admin_can_update_user_information(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->kandidat->id), [
            'name' => 'Nadia Saraswati Putri',
            'email' => 'nadia.putri@example.com',
            'role' => 'kandidat',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.users'));
        $this->assertDatabaseHas('users', [
            'id' => $this->kandidat->id,
            'name' => 'Nadia Saraswati Putri',
            'email' => 'nadia.putri@example.com',
        ]);

        // Verify Activity Log
        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'update',
            'target_type' => 'user',
            'target_id' => $this->kandidat->id,
        ]);
    }

    public function test_admin_cannot_block_themselves(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->admin->id), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'role' => 'admin',
            'status' => 'blocked',
        ]);

        $response->assertSessionHas('error', 'Anda tidak dapat memblokir akun Administrator Anda sendiri.');
        $this->assertEquals('active', $this->admin->fresh()->status);
    }

    public function test_admin_cannot_block_the_last_active_admin(): void
    {
        // $this->admin is the only admin
        $response = $this->actingAs($this->admin)->post(route('admin.users.block', $this->admin->id));
        $response->assertSessionHas('error');

        $this->assertEquals('active', $this->admin->fresh()->status);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin->id));

        $response->assertSessionHas('error', 'Anda tidak dapat menghapus akun Administrator Anda sendiri.');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_cannot_delete_the_last_active_admin(): void
    {
        $anotherAdmin = User::factory()->create([
            'name' => 'Admin Cadangan',
            'email' => 'cadangan@kerjasetara.id',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Delete another admin works when there are 2
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $anotherAdmin->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $anotherAdmin->id]);

        // Now only 1 admin remains; trying to delete the last one should be blocked
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin->id));
        $response->assertSessionHas('error');
    }

    public function test_admin_can_block_and_activate_user(): void
    {
        // Block
        $response = $this->actingAs($this->admin)->post(route('admin.users.block', $this->kandidat->id));
        $response->assertSessionHas('success');
        $this->assertEquals('blocked', $this->kandidat->fresh()->status);

        // Activate
        $response = $this->actingAs($this->admin)->post(route('admin.users.activate', $this->kandidat->id));
        $response->assertSessionHas('success');
        $this->assertEquals('active', $this->kandidat->fresh()->status);
    }

    public function test_blocked_user_is_prevented_from_logging_in(): void
    {
        $this->kandidat->update(['status' => 'blocked']);

        $response = $this->post('/login', [
            'email' => 'nadia.saraswati@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_with_temporary_password_must_change_password_on_first_login(): void
    {
        $userWithTempPassword = User::factory()->create([
            'email' => 'temp@example.com',
            'password' => Hash::make('temp-pass'),
            'role' => 'kandidat',
            'status' => 'active',
            'must_change_password' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'temp@example.com',
            'password' => 'temp-pass',
        ]);

        $response->assertRedirect(route('password.first_time'));
        $this->assertAuthenticated();

        // Submit new password
        $response = $this->post(route('password.first_time.update'), [
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertFalse($userWithTempPassword->fresh()->must_change_password);
        $this->assertTrue(Hash::check('NewSecurePassword123!', $userWithTempPassword->fresh()->password));
    }

    public function test_admin_can_safely_delete_candidate_with_related_data(): void
    {
        // Create candidate application and preference
        UserAccessibilityPreference::create([
            'user_id' => $this->kandidat->id,
            'category' => 'komunikasi',
        ]);

        $vacancy = JobVacancy::create([
            'company_id' => $this->hrd->companyProfile->id,
            'title' => 'Sample Job',
            'category' => 'digital',
            'location' => 'Jakarta',
            'job_type' => 'remote',
            'description' => 'Test',
        ]);

        JobApplication::create([
            'user_id' => $this->kandidat->id,
            'job_vacancy_id' => $vacancy->id,
            'status' => 'submitted',
            'resume_name' => 'CV_Nadia.pdf',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->kandidat->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $this->kandidat->id]);
        $this->assertDatabaseMissing('job_applications', ['user_id' => $this->kandidat->id]);
        $this->assertDatabaseMissing('user_accessibility_preferences', ['user_id' => $this->kandidat->id]);
    }

    public function test_admin_can_safely_delete_hrd_with_related_company_and_vacancies(): void
    {
        $vacancy = JobVacancy::create([
            'company_id' => $this->hrd->companyProfile->id,
            'title' => 'Software Engineer Inklusif',
            'category' => 'digital',
            'location' => 'Jakarta',
            'job_type' => 'remote',
            'description' => 'Testing safe deletion',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->hrd->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $this->hrd->id]);
        $this->assertDatabaseMissing('company_profiles', ['user_id' => $this->hrd->id]);
        $this->assertDatabaseMissing('job_vacancies', ['id' => $vacancy->id]);
    }
}
