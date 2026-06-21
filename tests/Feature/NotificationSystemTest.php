<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TempatPkl;
use App\Models\PengajuanPkl;
use App\Models\JurnalPkl;
use App\Models\LaporanPkl;
use App\Notifications\PengajuanPklStatusChanged;
use App\Notifications\SiswaUploadJurnal;
use App\Notifications\SiswaUploadLaporan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_receives_notification_when_pengajuan_status_changes(): void
    {
        Notification::fake();

        // 1. Create Siswa
        $siswaUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $siswa = Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => '11112222',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Alamat Siswa',
            'no_hp' => '081111111111',
        ]);

        // 2. Create Guru
        $guruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $guru = Guru::create([
            'user_id' => $guruUser->id,
            'nip' => '9999999999999999',
            'alamat' => 'Alamat Guru',
            'no_hp' => '089999999999',
        ]);

        // 3. Create Tempat PKL
        $tempat = TempatPkl::create([
            'nama_tempat' => 'PT Makmur',
            'alamat' => 'Jl. Makmur',
            'bidang_usaha' => 'Bisnis',
            'kontak_person' => 'Jane',
            'no_hp' => '082222222222',
            'email' => 'jane@makmur.com',
            'kuota' => 5,
        ]);

        // 4. Create Pengajuan
        $pengajuan = PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'tempat_pkl_id' => $tempat->id,
            'guru_id' => $guru->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Ingin PKL di sini.',
            'status' => 'menunggu_persetujuan',
        ]);

        // 5. Admin approves the pengajuan
        $adminUser = User::factory()->create(['role' => 'admin', 'is_approved' => true]);
        $response = $this->actingAs($adminUser)->put(route('admin.pengajuan.setujui', $pengajuan), [
            'catatan' => 'Disetujui, harap laksanakan dengan baik.',
        ]);

        $response->assertRedirect();
        
        // Assert notification sent to Siswa
        Notification::assertSentTo(
            $siswaUser,
            PengajuanPklStatusChanged::class,
            function ($notification, $channels) {
                return in_array('database', $channels) && in_array('mail', $channels);
            }
        );
    }

    public function test_guru_receives_notification_when_siswa_uploads_jurnal(): void
    {
        Notification::fake();

        // 1. Create Siswa
        $siswaUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $siswa = Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => '11112222',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Alamat Siswa',
            'no_hp' => '081111111111',
        ]);

        // 2. Create Guru
        $guruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $guru = Guru::create([
            'user_id' => $guruUser->id,
            'nip' => '9999999999999999',
            'alamat' => 'Alamat Guru',
            'no_hp' => '089999999999',
        ]);

        // 3. Create Tempat PKL
        $tempat = TempatPkl::create([
            'nama_tempat' => 'PT Makmur',
            'alamat' => 'Jl. Makmur',
            'bidang_usaha' => 'Bisnis',
            'kontak_person' => 'Jane',
            'no_hp' => '082222222222',
            'email' => 'jane@makmur.com',
            'kuota' => 5,
        ]);

        // 4. Create Approved Pengajuan
        $pengajuan = PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'tempat_pkl_id' => $tempat->id,
            'guru_id' => $guru->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Ingin PKL di sini.',
            'status' => 'disetujui',
        ]);

        // 5. Siswa uploads a journal
        $response = $this->actingAs($siswaUser)->post(route('siswa.jurnal.store'), [
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Membuat fitur notifikasi',
        ]);

        $response->assertRedirect(route('siswa.jurnal.index'));

        // Assert notification sent to Guru
        Notification::assertSentTo(
            $guruUser,
            SiswaUploadJurnal::class
        );
    }

    public function test_guru_receives_notification_when_siswa_uploads_laporan(): void
    {
        Notification::fake();

        // 1. Create Siswa
        $siswaUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $siswa = Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => '11112222',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Alamat Siswa',
            'no_hp' => '081111111111',
        ]);

        // 2. Create Guru
        $guruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $guru = Guru::create([
            'user_id' => $guruUser->id,
            'nip' => '9999999999999999',
            'alamat' => 'Alamat Guru',
            'no_hp' => '089999999999',
        ]);

        // 3. Create Tempat PKL
        $tempat = TempatPkl::create([
            'nama_tempat' => 'PT Makmur',
            'alamat' => 'Jl. Makmur',
            'bidang_usaha' => 'Bisnis',
            'kontak_person' => 'Jane',
            'no_hp' => '082222222222',
            'email' => 'jane@makmur.com',
            'kuota' => 5,
        ]);

        // 4. Create Approved Pengajuan
        $pengajuan = PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'tempat_pkl_id' => $tempat->id,
            'guru_id' => $guru->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Ingin PKL di sini.',
            'status' => 'disetujui',
        ]);

        // Use a mock file upload
        $file = \Illuminate\Http\UploadedFile::fake()->create('laporan.pdf', 100);

        // 5. Siswa uploads a report
        $response = $this->actingAs($siswaUser)->post(route('siswa.laporan.store'), [
            'file_laporan' => $file,
        ]);

        $response->assertRedirect(route('siswa.laporan.index'));

        // Assert notification sent to Guru
        Notification::assertSentTo(
            $guruUser,
            SiswaUploadLaporan::class
        );
    }
}
