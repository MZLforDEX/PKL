<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TempatPkl;
use App\Models\PengajuanPkl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanPklQuotaTest extends TestCase
{
    use RefreshDatabase;

    protected User $siswaUser1;
    protected User $siswaUser2;
    protected Siswa $siswa1;
    protected Siswa $siswa2;
    protected User $guruUser;
    protected User $adminUser;
    protected Guru $guru;
    protected TempatPkl $tempatPkl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Satu Kuota',
            'alamat' => 'Jl. Kuota No. 1',
            'bidang_usaha' => 'IT',
            'kuota' => 1,
        ]);

        // Admin
        $this->adminUser = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        // Guru
        $this->guruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $this->guru = Guru::create([
            'user_id' => $this->guruUser->id,
            'nip' => '1111222233334444',
            'alamat' => 'Jl. Guru',
            'no_hp' => '0812345678',
        ]);

        // Siswa 1
        $this->siswaUser1 = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $this->siswa1 = Siswa::create([
            'user_id' => $this->siswaUser1->id,
            'nis' => '12345',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Siswa 1',
            'no_hp' => '08123',
        ]);

        // Siswa 2
        $this->siswaUser2 = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $this->siswa2 = Siswa::create([
            'user_id' => $this->siswaUser2->id,
            'nis' => '67890',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Siswa 2',
            'no_hp' => '08456',
        ]);
    }

    public function test_siswa_cannot_apply_when_quota_is_full(): void
    {
        // First student occupies the 1 quota
        PengajuanPkl::create([
            'siswa_id' => $this->siswa1->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $this->guru->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'PKL 1',
            'status' => 'disetujui',
        ]);

        // Assert Tempat PKL is full
        $this->assertTrue($this->tempatPkl->fresh()->is_penuh);
        $this->assertEquals(0, $this->tempatPkl->fresh()->sisa_kuota);

        // Second student tries to create a new application
        $response = $this->actingAs($this->siswaUser2)->post(route('siswa.pengajuan.store'), [
            'tempat_pkl_id' => $this->tempatPkl->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'PKL 2',
        ]);

        $response->assertSessionHasErrors('msg');
        $this->assertEquals(1, PengajuanPkl::count());
    }

    public function test_siswa_cannot_submit_when_quota_is_full(): void
    {
        // Second student has a draft application
        $pengajuan2 = PengajuanPkl::create([
            'siswa_id' => $this->siswa2->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'PKL 2',
            'status' => 'draft',
        ]);

        // First student gets approved and occupies the 1 quota
        PengajuanPkl::create([
            'siswa_id' => $this->siswa1->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $this->guru->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'PKL 1',
            'status' => 'disetujui',
        ]);

        // Second student tries to submit their draft
        $response = $this->actingAs($this->siswaUser2)->put(route('siswa.pengajuan.ajukan', $pengajuan2));
        $response->assertSessionHasErrors('msg');
        $this->assertEquals('draft', $pengajuan2->fresh()->status);
    }

    public function test_admin_cannot_approve_when_quota_is_full(): void
    {
        // Student 1 has a pending request
        $pengajuan1 = PengajuanPkl::create([
            'siswa_id' => $this->siswa1->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $this->guru->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'PKL 1',
            'status' => 'menunggu_persetujuan',
        ]);

        // Student 2 has a pending request
        $pengajuan2 = PengajuanPkl::create([
            'siswa_id' => $this->siswa2->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $this->guru->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'PKL 2',
            'status' => 'menunggu_persetujuan',
        ]);

        // Admin approves Student 1
        $response1 = $this->actingAs($this->adminUser)->put(route('admin.pengajuan.setujui', $pengajuan1));
        $response1->assertSessionHasNoErrors();
        $this->assertEquals('disetujui', $pengajuan1->fresh()->status);

        // Admin tries to approve Student 2, but quota is now 0
        $response2 = $this->actingAs($this->adminUser)->put(route('admin.pengajuan.setujui', $pengajuan2));
        $response2->assertSessionHasErrors('msg');
        $this->assertEquals('menunggu_persetujuan', $pengajuan2->fresh()->status);
    }
}
