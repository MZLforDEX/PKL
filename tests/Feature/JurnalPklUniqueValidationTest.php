<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TempatPkl;
use App\Models\PengajuanPkl;
use App\Models\JurnalPkl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JurnalPklUniqueValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_cannot_create_duplicate_journal_for_same_date(): void
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

        // 3. Create Tempat Pkl
        $tempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Test Industri',
            'alamat' => 'Jl. Test No. 5',
            'bidang_usaha' => 'Teknologi',
            'kuota' => 5,
        ]);

        // 4. Create approved pengajuan
        $pengajuan = PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'tempat_pkl_id' => $tempatPkl->id,
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Test',
            'status' => 'disetujui',
        ]);

        // 5. Create Jurnal for today
        $tanggalHariIni = now()->toDateString();
        JurnalPkl::create([
            'pengajuan_pkl_id' => $pengajuan->id,
            'tanggal' => $tanggalHariIni,
            'kegiatan' => 'Kegiatan Pertama',
            'status' => 'menunggu_validasi',
        ]);

        // 6. Try to create another Jurnal for today via store route
        $response = $this->actingAs($siswaUser)->post(route('siswa.jurnal.store'), [
            'tanggal' => $tanggalHariIni,
            'kegiatan' => 'Kegiatan Kedua',
        ]);

        $response->assertSessionHasErrors(['tanggal']);
        $this->assertCount(1, JurnalPkl::where('pengajuan_pkl_id', $pengajuan->id)->where('tanggal', $tanggalHariIni)->get());
    }

    public function test_siswa_cannot_update_journal_to_existing_date(): void
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

        // 3. Create Tempat Pkl
        $tempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Test Industri',
            'alamat' => 'Jl. Test No. 5',
            'bidang_usaha' => 'Teknologi',
            'kuota' => 5,
        ]);

        // 4. Create approved pengajuan
        $pengajuan = PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'tempat_pkl_id' => $tempatPkl->id,
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Test',
            'status' => 'disetujui',
        ]);

        $tanggalKemarin = now()->subDay()->toDateString();
        $tanggalHariIni = now()->toDateString();

        // 5. Create two journal entries
        JurnalPkl::create([
            'pengajuan_pkl_id' => $pengajuan->id,
            'tanggal' => $tanggalKemarin,
            'kegiatan' => 'Kegiatan Kemarin',
            'status' => 'menunggu_validasi',
        ]);

        $jurnalHariIni = JurnalPkl::create([
            'pengajuan_pkl_id' => $pengajuan->id,
            'tanggal' => $tanggalHariIni,
            'kegiatan' => 'Kegiatan Hari Ini',
            'status' => 'revisi', // set to revisi so it can be updated
        ]);

        // 6. Try to update jurnalHariIni's date to $tanggalKemarin
        $response = $this->actingAs($siswaUser)->put(route('siswa.jurnal.update', $jurnalHariIni), [
            'tanggal' => $tanggalKemarin,
            'kegiatan' => 'Coba ubah tanggal ke kemarin',
        ]);

        $response->assertSessionHasErrors(['tanggal']);
        $this->assertEquals($tanggalHariIni, $jurnalHariIni->fresh()->tanggal);
    }

    public function test_guru_and_pembimbing_can_both_validate_parallelly(): void
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

        // 3. Create Tempat Pkl
        $tempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Test Industri',
            'alamat' => 'Jl. Test No. 5',
            'bidang_usaha' => 'Teknologi',
            'kuota' => 5,
        ]);

        // 4. Create Pembimbing
        $pembimbingUser = User::factory()->create(['role' => 'pembimbing_industri', 'is_approved' => true]);
        \App\Models\PembimbingIndustri::create([
            'user_id' => $pembimbingUser->id,
            'tempat_pkl_id' => $tempatPkl->id,
            'no_hp' => '087777777777',
            'jabatan' => 'HRD',
        ]);

        // 5. Create approved pengajuan
        $pengajuan = PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'tempat_pkl_id' => $tempatPkl->id,
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Test',
            'status' => 'disetujui',
        ]);

        // 6. Create Jurnal
        $jurnal = JurnalPkl::create([
            'pengajuan_pkl_id' => $pengajuan->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Belajar TDD',
            'status' => 'menunggu_validasi',
        ]);

        // 7. Guru validates
        $response1 = $this->actingAs($guruUser)->put(route('guru.jurnal.valid', $jurnal), [
            'catatan_guru' => 'Bagus sekali guru',
        ]);
        $response1->assertRedirect();
        
        $jurnal = $jurnal->fresh();
        $this->assertEquals('valid', $jurnal->status);
        $this->assertEquals('Bagus sekali guru', $jurnal->catatan_guru);

        // 8. Pembimbing validates the already 'valid' journal (since they haven't validated it yet)
        $response2 = $this->actingAs($pembimbingUser)->put(route('pembimbing.jurnal.valid', $jurnal), [
            'catatan_pembimbing' => 'Bagus sekali industri',
        ]);
        $response2->assertRedirect();

        $jurnal = $jurnal->fresh();
        $this->assertEquals('valid', $jurnal->status);
        $this->assertEquals('Bagus sekali industri', $jurnal->catatan_pembimbing);
    }

    public function test_student_update_clears_validation_notes(): void
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

        // 3. Create Tempat Pkl
        $tempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Test Industri',
            'alamat' => 'Jl. Test No. 5',
            'bidang_usaha' => 'Teknologi',
            'kuota' => 5,
        ]);

        // 4. Create approved pengajuan
        $pengajuan = PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'tempat_pkl_id' => $tempatPkl->id,
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Test',
            'status' => 'disetujui',
        ]);

        // 5. Create Jurnal under revisi with existing notes
        $jurnal = JurnalPkl::create([
            'pengajuan_pkl_id' => $pengajuan->id,
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Belajar TDD',
            'status' => 'revisi',
            'catatan_guru' => 'Revisi guru',
            'catatan_pembimbing' => 'Revisi industri',
        ]);

        // 6. Update journal
        $response = $this->actingAs($siswaUser)->put(route('siswa.jurnal.update', $jurnal), [
            'tanggal' => now()->toDateString(),
            'kegiatan' => 'Belajar TDD Diperbaiki',
        ]);
        $response->assertRedirect(route('siswa.jurnal.index'));

        $jurnal = $jurnal->fresh();
        $this->assertEquals('menunggu_validasi', $jurnal->status);
        $this->assertNull($jurnal->catatan_guru);
        $this->assertNull($jurnal->catatan_pembimbing);
    }
}
