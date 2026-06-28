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
        $base64Image = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/wAALCAABAAEBAREA/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oACAEBAAA/AD9i+bmWOv/Z';

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
        $base64Image = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/wAALCAABAAEBAREA/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oACAEBAAA/AD9i+bmWOv/Z';

        // First check-in
        $response = $this->actingAs($this->siswaUser)->post(route('siswa.absensi.store'), [
            'latitude' => -4.0,
            'longitude' => 121.0,
            'foto_selfie' => $base64Image,
        ]);
        $response->assertSessionHasNoErrors();

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

    public function test_guru_can_export_absensi_excel(): void
    {
        // Set period session helper
        $this->actingAs($this->guruUser);
        $selectedPeriod = \App\Models\PeriodePkl::where('status_aktif', true)->first();
        if (!$selectedPeriod) {
            $selectedPeriod = \App\Models\PeriodePkl::create([
                'nama_periode' => 'PKL 2025/2026',
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2026-06-30',
                'status_aktif' => true,
            ]);
        }

        $response = $this->actingAs($this->guruUser)->get(route('guru.absensi.export', [
            'bulan' => 5,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="Rekap_Absensi_Siswa_Bimbingan_PKL_2025_2026_Mei_2026.xls"');
        $response->assertSee('LAPORAN HARIAN ABSENSI SISWA PKL');
        $response->assertSee($this->siswa->user->name);
    }

    public function test_pembimbing_industri_can_export_absensi_excel(): void
    {
        // Set period session helper
        $this->actingAs($this->pembimbingUser);
        $selectedPeriod = \App\Models\PeriodePkl::where('status_aktif', true)->first();
        if (!$selectedPeriod) {
            $selectedPeriod = \App\Models\PeriodePkl::create([
                'nama_periode' => 'PKL 2025/2026',
                'tanggal_mulai' => '2025-07-01',
                'tanggal_selesai' => '2026-06-30',
                'status_aktif' => true,
            ]);
        }

        $response = $this->actingAs($this->pembimbingUser)->get(route('pembimbing.absensi.export', [
            'bulan' => 5,
            'tahun' => 2026,
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="Rekap_Absensi_Siswa_Magang_PKL_2025_2026_Mei_2026.xls"');
        $response->assertSee('LAPORAN HARIAN ABSENSI SISWA PKL');
        $response->assertSee($this->siswa->user->name);
    }

    public function test_non_authorized_role_cannot_export_absensi_excel(): void
    {
        // Siswa role trying to access guru export
        $response = $this->actingAs($this->siswaUser)->get(route('guru.absensi.export', [
            'bulan' => 5,
            'tahun' => 2026,
        ]));
        $response->assertStatus(403);

        // Siswa role trying to access pembimbing export
        $response = $this->actingAs($this->siswaUser)->get(route('pembimbing.absensi.export', [
            'bulan' => 5,
            'tahun' => 2026,
        ]));
        $response->assertStatus(403);
    }
}
