<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TempatPkl;
use App\Models\PengajuanPkl;
use App\Models\PenilaianPkl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenilaianPklTest extends TestCase
{
    use RefreshDatabase;

    protected User $siswaUser;
    protected Siswa $siswa;
    protected User $guruUser;
    protected Guru $guru;
    protected User $otherGuruUser;
    protected Guru $otherGuru;
    protected TempatPkl $tempatPkl;
    protected PengajuanPkl $pengajuan;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Tempat PKL
        $this->tempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Makmur Jaya',
            'alamat' => 'Jl. Makmur No. 1',
            'bidang_usaha' => 'Teknologi',
            'kuota' => 5,
        ]);

        // Setup Guru bimbingan
        $this->guruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $this->guru = Guru::create([
            'user_id' => $this->guruUser->id,
            'nip' => '1234567890123456',
            'alamat' => 'Jl. Guru',
            'no_hp' => '081234567890',
        ]);

        // Setup Other Guru
        $this->otherGuruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $this->otherGuru = Guru::create([
            'user_id' => $this->otherGuruUser->id,
            'nip' => '1234567890123457',
            'alamat' => 'Jl. Guru Lain',
            'no_hp' => '081234567891',
        ]);

        // Setup Siswa
        $this->siswaUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $this->siswa = Siswa::create([
            'user_id' => $this->siswaUser->id,
            'nis' => '11223344',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Jl. Siswa',
            'no_hp' => '081122334455',
        ]);

        // Setup approved Pengajuan linking them, starting as 'menunggu_penilaian'
        $this->pengajuan = PengajuanPkl::create([
            'siswa_id' => $this->siswa->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $this->guru->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Kerja praktek',
            'status' => 'menunggu_penilaian',
        ]);
    }

    public function test_guru_can_view_penilaian_index(): void
    {
        $response = $this->actingAs($this->guruUser)->get(route('guru.penilaian.index'));
        $response->assertStatus(200);
        $response->assertSee($this->siswaUser->name);
    }

    public function test_guru_can_view_penilaian_create_page(): void
    {
        $response = $this->actingAs($this->guruUser)->get(route('guru.penilaian.create', $this->pengajuan));
        $response->assertStatus(200);
        $response->assertSee('Beri Penilaian PKL');
    }

    public function test_guru_cannot_view_penilaian_create_page_if_status_not_menunggu_penilaian(): void
    {
        $this->pengajuan->update(['status' => 'sedang_pkl']);

        $response = $this->actingAs($this->guruUser)->get(route('guru.penilaian.create', $this->pengajuan));
        $response->assertRedirect(route('guru.penilaian.index'));
        $response->assertSessionHasErrors('msg');
    }

    public function test_guru_can_store_penilaian_successfully(): void
    {
        $response = $this->actingAs($this->guruUser)->post(route('guru.penilaian.store', $this->pengajuan), [
            'pengajuan_pkl_id' => $this->pengajuan->id,
            'nilai_sikap' => 90,
            'nilai_keterampilan' => 85,
            'nilai_laporan' => 80,
            'catatan_evaluasi' => 'Kerja sangat bagus.',
        ]);

        $response->assertRedirect(route('guru.penilaian.index'));
        $response->assertSessionHasNoErrors();

        // Check database table
        $this->assertDatabaseHas('penilaian_pkl', [
            'pengajuan_pkl_id' => $this->pengajuan->id,
            'nilai_sikap' => 90,
            'nilai_keterampilan' => 85,
            'nilai_laporan' => 80,
            'nilai_akhir' => 85.00,
            'catatan_evaluasi' => 'Kerja sangat bagus.',
        ]);

        $this->assertEquals('selesai', $this->pengajuan->fresh()->status);
    }

    public function test_other_guru_cannot_view_or_store_penilaian(): void
    {
        // View page access restricted
        $response = $this->actingAs($this->otherGuruUser)->get(route('guru.penilaian.create', $this->pengajuan));
        $response->assertStatus(403);

        // Store action restricted
        $response2 = $this->actingAs($this->otherGuruUser)->post(route('guru.penilaian.store', $this->pengajuan), [
            'pengajuan_pkl_id' => $this->pengajuan->id,
            'nilai_sikap' => 90,
            'nilai_keterampilan' => 85,
            'nilai_laporan' => 80,
            'catatan_evaluasi' => 'Kerja sangat bagus.',
        ]);
        $response2->assertStatus(403);
    }
}
