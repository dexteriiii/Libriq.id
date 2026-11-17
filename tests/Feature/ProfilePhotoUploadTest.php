<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilePhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_profile_photo_and_see_it_on_dashboard(): void
    {
        $disk = config('filesystems.default', 'public');
        Storage::fake($disk);

        $admin = User::factory()->create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@libriq.id',
            'role' => 'admin',
        ]);

        $file = UploadedFile::fake()->create('admin_avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($admin)
            ->put(route('admin.profile.update'), [
                'name' => 'Admin Updated',
                'email' => 'admin@libriq.id',
                'avatar' => $file,
            ]);

        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertNotNull($admin->avatar_path);
        Storage::disk($disk)->assertExists($admin->avatar_path);

        $dashboardResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee($admin->avatar_url);
    }

    public function test_member_can_upload_profile_photo_and_see_it_on_dashboard(): void
    {
        $disk = config('filesystems.default', 'public');
        Storage::fake($disk);

        $member = User::factory()->create([
            'name' => 'Member Pembaca',
            'email' => 'member@libriq.id',
            'role' => 'member',
            'member_id' => 'MBR-001',
        ]);

        $file = UploadedFile::fake()->create('member_avatar.png', 100, 'image/png');

        $response = $this->actingAs($member)
            ->put(route('member.profile.update'), [
                'name' => 'Member Updated',
                'email' => 'member@libriq.id',
                'avatar' => $file,
            ]);

        $response->assertSessionHas('success');

        $member->refresh();
        $this->assertNotNull($member->avatar_path);
        $disk = config('filesystems.default', 'public');
        Storage::disk($disk)->assertExists($member->avatar_path);

        $dashboardResponse = $this->actingAs($member)->get(route('member.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee($member->avatar_url);
    }

    public function test_old_photo_is_deleted_when_uploading_new_photo(): void
    {
        $disk = config('filesystems.default', 'public');
        Storage::fake($disk);

        $member = User::factory()->create([
            'role' => 'member',
        ]);

        // Upload first avatar
        $firstFile = UploadedFile::fake()->create('first.jpg', 100, 'image/jpeg');
        $this->actingAs($member)->put(route('member.profile.update'), [
            'name' => $member->name,
            'email' => $member->email,
            'avatar' => $firstFile,
        ]);

        $member->refresh();
        $firstPath = $member->avatar_path;
        Storage::disk($disk)->assertExists($firstPath);

        // Upload second avatar
        $secondFile = UploadedFile::fake()->create('second.png', 100, 'image/png');
        $this->actingAs($member)->put(route('member.profile.update'), [
            'name' => $member->name,
            'email' => $member->email,
            'avatar' => $secondFile,
        ]);

        $member->refresh();
        $secondPath = $member->avatar_path;

        $disk = config('filesystems.default', 'public');
        $this->assertNotEquals($firstPath, $secondPath);
        Storage::disk($disk)->assertMissing($firstPath);
        Storage::disk($disk)->assertExists($secondPath);
    }
}
