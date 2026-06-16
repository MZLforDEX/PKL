<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_pembimbing_industri_profile_information_can_be_updated(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $user = User::factory()->create(['role' => 'pembimbing_industri']);
        $tempat = \App\Models\TempatPkl::create([
            'nama_tempat' => 'Company A',
            'alamat' => 'Address A',
            'bidang_usaha' => 'IT',
            'kontak_person' => 'Budi',
            'no_hp' => '081',
            'email' => 'comp@test.com',
            'kuota' => 5
        ]);
        $pembimbing = \App\Models\PembimbingIndustri::create([
            'user_id' => $user->id,
            'tempat_pkl_id' => $tempat->id,
            'no_hp' => '08123456789',
            'jabatan' => 'HRD Manager',
        ]);

        $signatureFile = \Illuminate\Http\UploadedFile::fake()->create('signature.png', 100, 'image/png');
        $logoFile = \Illuminate\Http\UploadedFile::fake()->create('logo.png', 100, 'image/png');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Updated Pembimbing',
                'email' => 'pembimbing_updated@example.com',
                'jabatan' => 'CEO',
                'no_hp' => '08999999999',
                'tanda_tangan' => $signatureFile,
                'logo' => $logoFile,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();
        $pembimbing->refresh();

        $this->assertSame('Updated Pembimbing', $user->name);
        $this->assertSame('pembimbing_updated@example.com', $user->email);
        $this->assertSame('CEO', $pembimbing->jabatan);
        $this->assertSame('08999999999', $pembimbing->no_hp);
        $this->assertNotNull($pembimbing->tanda_tangan);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($pembimbing->tanda_tangan);
        $this->assertNotNull($pembimbing->logo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($pembimbing->logo);
    }
}
