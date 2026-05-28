<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TempatPkl;
use App\Models\PengajuanPkl;
use App\Models\AbsensiPkl;
use App\Models\PembimbingIndustri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Tests\TestCase;

class AbsensiPklTest extends TestCase
{
    use RefreshDatabase;

    protected User $siswaUser;
    protected Siswa $siswa;
    protected User $guruUser;
    protected Guru $guru;
    protected User $pembimbingUser;
    protected PembimbingIndustri $pembimbing;
    protected TempatPkl $tempatPkl;
    protected PengajuanPkl $pengajuan;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

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

        // Setup approved Pengajuan linking them
        $this->pengajuan = PengajuanPkl::create([
            'siswa_id' => $this->siswa->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $this->guru->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Kerja praktek',
            'status' => 'sedang_pkl',
        ]);

        // Setup Pembimbing Industri
        $this->pembimbingUser = User::factory()->create(['role' => 'pembimbing_industri', 'is_approved' => true]);
        $this->pembimbing = PembimbingIndustri::create([
            'user_id' => $this->pembimbingUser->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'no_hp' => '087777777777',
            'jabatan' => 'HRD',
        ]);
    }

    public function test_siswa_can_view_absensi_page(): void
    {
        $response = $this->actingAs($this->siswaUser)->get(route('siswa.absensi.index'));
        $response->assertStatus(200);
        $response->assertSee('Absensi Harian PKL');
    }

    public function test_siswa_can_clock_in_successfully(): void
    {
        $base64Image = 'data:image/jpeg;base64,' . base64_encode('dummy image content');

        $response = $this->actingAs($this->siswaUser)->post(route('siswa.absensi.store'), [
            'latitude' => -4.012345,
            'longitude' => 121.654321,
            'foto_selfie' => $base64Image,
        ]);

        $response->assertRedirect(route('siswa.absensi.index'));
        $response->assertSessionHasNoErrors();

        // Assert database entry
        $absensi = AbsensiPkl::first();
        $this->assertNotNull($absensi);
        $this->assertEquals($this->pengajuan->id, $absensi->pengajuan_pkl_id);
        $this->assertEquals(-4.012345, $absensi->latitude);
        $this->assertEquals(121.654321, $absensi->longitude);
        $this->assertNotNull($absensi->jam_masuk);
        $this->assertContains($absensi->status, ['hadir', 'terlambat']);
        Storage::disk('public')->assertExists($absensi->foto_selfie);
    }

    public function test_siswa_cannot_clock_in_twice_on_same_day(): void
    {
        $base64Image = 'data:image/jpeg;base64,' . base64_encode('dummy image content');

        // First check-in
        $this->actingAs($this->siswaUser)->post(route('siswa.absensi.store'), [
            'latitude' => -4.0,
            'longitude' => 121.0,
            'foto_selfie' => $base64Image,
        ]);

        // Second check-in
        $response = $this->actingAs($this->siswaUser)->post(route('siswa.absensi.store'), [
            'latitude' => -4.0,
            'longitude' => 121.0,
            'foto_selfie' => $base64Image,
        ]);

        $response->assertSessionHasErrors('msg');
        $this->assertEquals(1, AbsensiPkl::count());
    }

    public function test_guru_can_view_student_absensi(): void
    {
        // Create an attendance entry
        AbsensiPkl::create([
            'pengajuan_pkl_id' => $this->pengajuan->id,
            'tanggal' => Carbon::today()->toDateString(),
            'jam_masuk' => '07:30:00',
            'latitude' => -4.0,
            'longitude' => 121.0,
            'foto_selfie' => 'absensi/test.jpg',
            'status' => 'hadir',
        ]);

        $response = $this->actingAs($this->guruUser)->get(route('guru.absensi.index'));
        $response->assertStatus(200);
        $response->assertSee($this->siswaUser->name);
        $response->assertSee('PT Makmur Jaya');
    }

    public function test_pembimbing_industri_can_view_student_absensi(): void
    {
        // Create an attendance entry
        AbsensiPkl::create([
            'pengajuan_pkl_id' => $this->pengajuan->id,
            'tanggal' => Carbon::today()->toDateString(),
            'jam_masuk' => '08:15:00',
            'latitude' => -4.0,
            'longitude' => 121.0,
            'foto_selfie' => 'absensi/test.jpg',
            'status' => 'terlambat',
        ]);

        $response = $this->actingAs($this->pembimbingUser)->get(route('pembimbing.absensi.index'));
        $response->assertStatus(200);
        $response->assertSee($this->siswaUser->name);
        $response->assertSee('Terlambat');
    }
}
