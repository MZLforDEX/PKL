<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TempatPkl;
use App\Models\PengajuanPkl;
use App\Models\JurnalPkl;
use App\Models\PembimbingIndustri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembimbingIndustriTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $pembimbingUser;
    protected PembimbingIndustri $pembimbing;
    protected TempatPkl $tempatPkl;

    protected function setUp(): void
    {
        parent::setUp();

        // Admin User
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
            'is_approved' => true,
        ]);

        // Tempat PKL
        $this->tempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Test Industri',
            'alamat' => 'Jl. Test No. 5',
            'bidang_usaha' => 'Teknologi',
            'kuota' => 5,
        ]);

        // Pembimbing User & Model
        $this->pembimbingUser = User::factory()->create([
            'role' => 'pembimbing_industri',
            'is_approved' => true,
        ]);
        $this->pembimbing = PembimbingIndustri::create([
            'user_id' => $this->pembimbingUser->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'no_hp' => '087777777777',
            'jabatan' => 'HRD',
        ]);
    }

    public function test_admin_can_view_pembimbing_industri_list(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.pembimbing-industri.index'));
        $response->assertStatus(200);
        $response->assertSee($this->pembimbingUser->name);
        $response->assertSee('PT Test Industri');
    }

    public function test_admin_can_create_pembimbing_industri(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.pembimbing-industri.store'), [
            'name' => 'Mentor Baru',
            'email' => 'mentorbaru@test.com',
            'password' => 'password123',
            'tempat_pkl_id' => $this->tempatPkl->id,
            'jabatan' => 'Supervisor',
            'no_hp' => '081234567890',
        ]);

        $response->assertRedirect(route('admin.pembimbing-industri.index'));
        $this->assertDatabaseHas('users', ['email' => 'mentorbaru@test.com', 'role' => 'pembimbing_industri']);
        $this->assertDatabaseHas('pembimbing_industri', ['jabatan' => 'Supervisor']);
    }

    public function test_admin_can_edit_pembimbing_industri(): void
    {
        $response = $this->actingAs($this->adminUser)->put(route('admin.pembimbing-industri.update', $this->pembimbing), [
            'name' => 'Budi Industri Updated',
            'email' => $this->pembimbingUser->email,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'jabatan' => 'HR Manager',
            'no_hp' => '089999999999',
        ]);

        $response->assertRedirect(route('admin.pembimbing-industri.index'));
        $this->assertDatabaseHas('users', ['name' => 'Budi Industri Updated']);
        $this->assertDatabaseHas('pembimbing_industri', ['jabatan' => 'HR Manager', 'no_hp' => '089999999999']);
    }

    public function test_admin_can_delete_pembimbing_industri(): void
    {
        $response = $this->actingAs($this->adminUser)->delete(route('admin.pembimbing-industri.destroy', $this->pembimbing));
        
        $response->assertRedirect(route('admin.pembimbing-industri.index'));
        $this->assertDatabaseMissing('pembimbing_industri', ['id' => $this->pembimbing->id]);
        $this->assertDatabaseMissing('users', ['id' => $this->pembimbingUser->id]);
    }

    public function test_pembimbing_industri_can_view_dashboard_with_stats(): void
    {
        $response = $this->actingAs($this->pembimbingUser)->get(route('pembimbing.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Panel Pembimbing Industri');
        $response->assertSee($this->pembimbingUser->name);
    }

    public function test_pembimbing_industri_can_validate_student_jurnal(): void
    {
        // 1. Create Siswa
        $siswaUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $siswa = Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => '88889999',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Alamat',
            'no_hp' => '081234567890',
        ]);

        // 2. Create Guru
        $guruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $guru = Guru::create(['user_id' => $guruUser->id, 'nip' => '123456', 'alamat' => 'Alamat', 'no_hp' => '081234567890']);

        // 3. Create approved pengajuan for the siswa at the pembimbing's company
        $pengajuan = PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Test',
            'status' => 'disetujui',
        ]);

        // 3. Create Jurnal
        $jurnal = JurnalPkl::create([
            'pengajuan_pkl_id' => $pengajuan->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Kegiatan Hari Ini',
            'status' => 'menunggu_validasi',
        ]);

        // 4. Validate Jurnal
        $response = $this->actingAs($this->pembimbingUser)->put(route('pembimbing.jurnal.valid', $jurnal), [
            'catatan_pembimbing' => 'Bagus sekali.',
        ]);

        $response->assertRedirect();
        $this->assertEquals('valid', $jurnal->fresh()->status);
        $this->assertEquals('Bagus sekali.', $jurnal->fresh()->catatan_pembimbing);
    }

    public function test_siswa_can_edit_journal_when_revisi(): void
    {
        // 1. Create Siswa
        $siswaUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $siswa = Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => '11112222',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Alamat',
            'no_hp' => '081234567890',
        ]);

        // 2. Create Guru
        $guruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $guru = Guru::create(['user_id' => $guruUser->id, 'nip' => '123456', 'alamat' => 'Alamat', 'no_hp' => '081234567890']);

        // 3. Create approved pengajuan
        $pengajuan = PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Test',
            'status' => 'disetujui',
        ]);

        // 4. Create Jurnal in 'revisi' status
        $jurnal = JurnalPkl::create([
            'pengajuan_pkl_id' => $pengajuan->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Kegiatan Awal',
            'status' => 'revisi',
        ]);

        // 5. Update Jurnal
        $response = $this->actingAs($siswaUser)->put(route('siswa.jurnal.update', $jurnal), [
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Kegiatan Diperbarui',
        ]);

        $response->assertRedirect(route('siswa.jurnal.index'));
        $this->assertEquals('Kegiatan Diperbarui', $jurnal->fresh()->kegiatan);
        $this->assertEquals('menunggu_validasi', $jurnal->fresh()->status); // resets status!

        // 6. Try updating when valid
        $jurnal->update(['status' => 'valid']);
        $response2 = $this->actingAs($siswaUser)->put(route('siswa.jurnal.update', $jurnal), [
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Coba Ubah Lagi',
        ]);
        $response2->assertSessionHasErrors();
        $this->assertNotEquals('Coba Ubah Lagi', $jurnal->fresh()->kegiatan);
    }
}
