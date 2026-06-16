<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TempatPkl;
use App\Models\PembimbingIndustri;
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
    protected User $pembimbingUser;
    protected PembimbingIndustri $pembimbing;
    protected User $otherPembimbingUser;
    protected PembimbingIndustri $otherPembimbing;
    protected TempatPkl $tempatPkl;
    protected TempatPkl $otherTempatPkl;
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

        $this->otherTempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Sumber Rejeki',
            'alamat' => 'Jl. Rejeki No. 2',
            'bidang_usaha' => 'Jasa',
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

        // Setup Pembimbing Industri
        $this->pembimbingUser = User::factory()->create(['role' => 'pembimbing_industri', 'is_approved' => true]);
        $this->pembimbing = PembimbingIndustri::create([
            'user_id' => $this->pembimbingUser->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'no_hp' => '081234567892',
            'jabatan' => 'Supervisor',
        ]);

        // Setup Other Pembimbing Industri
        $this->otherPembimbingUser = User::factory()->create(['role' => 'pembimbing_industri', 'is_approved' => true]);
        $this->otherPembimbing = PembimbingIndustri::create([
            'user_id' => $this->otherPembimbingUser->id,
            'tempat_pkl_id' => $this->otherTempatPkl->id,
            'no_hp' => '081234567893',
            'jabatan' => 'HRD Manager',
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

    public function test_pembimbing_can_view_penilaian_index(): void
    {
        $response = $this->actingAs($this->pembimbingUser)->get(route('pembimbing.penilaian.index'));
        $response->assertStatus(200);
        $response->assertSee($this->siswaUser->name);
    }

    public function test_pembimbing_can_view_penilaian_create_page(): void
    {
        $response = $this->actingAs($this->pembimbingUser)->get(route('pembimbing.penilaian.create', $this->pengajuan));
        $response->assertStatus(200);
        $response->assertSee('Beri Penilaian PKL');
    }

    public function test_pembimbing_cannot_view_penilaian_create_page_if_status_not_menunggu_penilaian(): void
    {
        $this->pengajuan->update(['status' => 'sedang_pkl']);

        $response = $this->actingAs($this->pembimbingUser)->get(route('pembimbing.penilaian.create', $this->pengajuan));
        $response->assertRedirect(route('pembimbing.penilaian.index'));
        $response->assertSessionHasErrors('msg');
    }

    public function test_pembimbing_can_store_penilaian_successfully(): void
    {
        $response = $this->actingAs($this->pembimbingUser)->post(route('pembimbing.penilaian.store', $this->pengajuan), [
            'pengajuan_pkl_id' => $this->pengajuan->id,
            'items' => [
                ['nama' => 'Disiplin', 'nilai' => 90],
                ['nama' => 'Kerjasama', 'nilai' => 85],
                ['nama' => 'Laporan', 'nilai' => 80],
            ],
            'catatan_evaluasi' => 'Kerja sangat bagus.',
        ]);

        $response->assertRedirect(route('pembimbing.penilaian.index'));
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

        $penilaian = PenilaianPkl::where('pengajuan_pkl_id', $this->pengajuan->id)->first();
        $this->assertNotNull($penilaian->detail_nilai);
        $this->assertEquals('Disiplin', $penilaian->detail_nilai[0]['nama']);

        $this->assertEquals('selesai', $this->pengajuan->fresh()->status);
    }

    public function test_pembimbing_can_edit_and_update_penilaian(): void
    {
        $penilaian = PenilaianPkl::create([
            'pengajuan_pkl_id' => $this->pengajuan->id,
            'nilai_sikap' => 80,
            'nilai_keterampilan' => 80,
            'nilai_laporan' => 80,
            'nilai_akhir' => 80.00,
            'catatan_evaluasi' => 'Awal.',
            'detail_nilai' => [
                ['nama' => 'Sikap', 'nilai' => 80]
            ],
        ]);

        $response = $this->actingAs($this->pembimbingUser)->get(route('pembimbing.penilaian.edit', $penilaian));
        $response->assertStatus(200);

        $response2 = $this->actingAs($this->pembimbingUser)->put(route('pembimbing.penilaian.update', $penilaian), [
            'items' => [
                ['nama' => 'Sikap', 'nilai' => 95],
                ['nama' => 'Teknis', 'nilai' => 85],
            ],
            'catatan_evaluasi' => 'Meningkat.',
        ]);

        $response2->assertRedirect(route('pembimbing.penilaian.index'));
        $this->assertEquals(90.00, $penilaian->fresh()->nilai_akhir);
        $this->assertEquals('Meningkat.', $penilaian->fresh()->catatan_evaluasi);
    }

    public function test_pembimbing_can_delete_penilaian(): void
    {
        $penilaian = PenilaianPkl::create([
            'pengajuan_pkl_id' => $this->pengajuan->id,
            'nilai_sikap' => 80,
            'nilai_keterampilan' => 80,
            'nilai_laporan' => 80,
            'nilai_akhir' => 80.00,
            'catatan_evaluasi' => 'Catatan.',
        ]);
        $this->pengajuan->update(['status' => 'selesai']);

        $response = $this->actingAs($this->pembimbingUser)->delete(route('pembimbing.penilaian.destroy', $penilaian));
        $response->assertRedirect(route('pembimbing.penilaian.index'));

        $this->assertDatabaseMissing('penilaian_pkl', ['id' => $penilaian->id]);
        $this->assertEquals('menunggu_penilaian', $this->pengajuan->fresh()->status);
    }

    public function test_other_pembimbing_cannot_view_or_modify_penilaian(): void
    {
        $penilaian = PenilaianPkl::create([
            'pengajuan_pkl_id' => $this->pengajuan->id,
            'nilai_sikap' => 80,
            'nilai_keterampilan' => 80,
            'nilai_laporan' => 80,
            'nilai_akhir' => 80.00,
            'catatan_evaluasi' => 'Catatan.',
        ]);

        // Create page access restricted
        $response = $this->actingAs($this->otherPembimbingUser)->get(route('pembimbing.penilaian.create', $this->pengajuan));
        $response->assertStatus(403);

        // Edit page access restricted
        $response2 = $this->actingAs($this->otherPembimbingUser)->get(route('pembimbing.penilaian.edit', $penilaian));
        $response2->assertStatus(403);

        // Update restricted
        $response3 = $this->actingAs($this->otherPembimbingUser)->put(route('pembimbing.penilaian.update', $penilaian), [
            'items' => [['nama' => 'Sikap', 'nilai' => 90]],
            'catatan_evaluasi' => 'Hack.',
        ]);
        $response3->assertStatus(403);

        // Delete restricted
        $response4 = $this->actingAs($this->otherPembimbingUser)->delete(route('pembimbing.penilaian.destroy', $penilaian));
        $response4->assertStatus(403);
    }

    public function test_guru_cannot_access_penilaian_routes(): void
    {
        $response = $this->actingAs($this->guruUser)->get(route('pembimbing.penilaian.index'));
        $response->assertStatus(403);
    }
}
