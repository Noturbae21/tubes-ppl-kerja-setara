<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $kandidat;

    protected User $hrd;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create([
            'name' => 'Fakhri Izzul Haq',
            'full_name' => 'Fakhri Izzul Haq',
            'email' => 'fakhri.profile.admin@example.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '081234567890',
        ]);

        $this->kandidat = User::factory()->create([
            'name' => 'Nadia Profil',
            'email' => 'kandidat.profile.test@example.test',
            'role' => 'kandidat',
            'status' => 'active',
        ]);

        $this->hrd = User::factory()->create([
            'name' => 'HRD Budi Profil',
            'email' => 'hrd.profile.test@example.test',
            'role' => 'hrd',
            'status' => 'active',
        ]);
    }

    public function test_guest_cannot_access_admin_profile_page(): void
    {
        $response = $this->get(route('admin.profile'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_kandidat_cannot_access_admin_profile_page(): void
    {
        $response = $this->actingAs($this->kandidat)->get(route('admin.profile'));
        $response->assertRedirect(route('home'));
    }

    public function test_hrd_cannot_access_admin_profile_page(): void
    {
        $response = $this->actingAs($this->hrd)->get(route('admin.profile'));
        $response->assertRedirect(route('home'));
    }

    public function test_admin_can_view_profile_page_with_their_data(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.profile'));

        $response->assertOk();
        $response->assertSee('Profil Administrator');
        $response->assertSeeTextInOrder(['Admin', '/', 'Profil Administrator']);
        $response->assertSee('Fakhri Izzul Haq');
        $response->assertSee('fakhri.admin@example.com');
        $response->assertSee('081234567890');
        $response->assertSee('Super Admin');
        $response->assertSee('Administrator Utama KerjaSetara');
        $response->assertSee('FI'); // Initials
    }

    public function test_admin_can_update_account_information(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profile.update'), [
            'name' => 'Fakhri Izzul Haq Updated',
            'email' => 'fakhri.new@example.com',
            'phone' => '+62 813 9999 8888',
        ]);

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertEquals('Fakhri Izzul Haq Updated', $this->admin->name);
        $this->assertEquals('Fakhri Izzul Haq Updated', $this->admin->full_name);
        $this->assertEquals('fakhri.new@example.com', $this->admin->email);
        $this->assertEquals('+62 813 9999 8888', $this->admin->phone);

        // ActivityLog assertion
        $this->assertDatabaseHas('activity_logs', [
            'action_type' => 'update',
            'target_type' => 'user',
            'target_id' => $this->admin->id,
        ]);
    }

    public function test_update_validates_required_fields_and_unique_email(): void
    {
        User::factory()->create([
            'email' => 'existing.other@example.com',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.profile.update'), [
            'name' => '',
            'email' => 'existing.other@example.com',
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_admin_can_update_password_with_valid_current_password(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profile.update'), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'current_password' => 'password123',
            'new_password' => 'newSecretPass!2026',
            'new_password_confirmation' => 'newSecretPass!2026',
        ]);

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('newSecretPass!2026', $this->admin->password));

        // Check activity log does NOT log sensitive password
        $log = ActivityLog::where('target_id', $this->admin->id)->latest()->first();
        $this->assertNotNull($log);
        $this->assertStringNotContainsString('newSecretPass', json_encode($log->metadata));
        $this->assertStringNotContainsString('password123', json_encode($log->metadata));
    }

    public function test_update_password_fails_if_current_password_is_wrong(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profile.update'), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'current_password' => 'wrongPassword',
            'new_password' => 'newSecretPass!2026',
            'new_password_confirmation' => 'newSecretPass!2026',
        ]);

        $response->assertSessionHasErrors('current_password');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('password123', $this->admin->password));
    }

    public function test_update_password_fails_if_confirmation_does_not_match(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.profile.update'), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'current_password' => 'password123',
            'new_password' => 'newSecretPass!2026',
            'new_password_confirmation' => 'differentPassword',
        ]);

        $response->assertSessionHasErrors('new_password');
    }

    public function test_admin_can_upload_valid_avatar_image(): void
    {
        $file = UploadedFile::fake()->image('profile-picture.jpg', 400, 400)->size(500); // 500 KB

        $response = $this->actingAs($this->admin)->put(route('admin.profile.update'), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'avatar' => $file,
        ]);

        $response->assertRedirect(route('admin.profile'));
        $response->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertNotNull($this->admin->avatar);
        Storage::disk('public')->assertExists($this->admin->avatar);
    }

    public function test_avatar_upload_fails_for_invalid_file_type(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->put(route('admin.profile.update'), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_avatar_upload_fails_if_file_exceeds_2mb(): void
    {
        $file = UploadedFile::fake()->image('huge.png')->size(3000); // 3 MB > 2 MB

        $response = $this->actingAs($this->admin)->put(route('admin.profile.update'), [
            'name' => $this->admin->name,
            'email' => $this->admin->email,
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_logout_ends_admin_session_properly(): void
    {
        $this->actingAs($this->admin);
        $this->assertAuthenticatedAs($this->admin);

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
}
