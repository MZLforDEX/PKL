<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TempatPkl;
use App\Models\PengajuanPkl;
use App\Models\PembimbingIndustri;
use App\Models\PesanPembimbing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HubungiSekolahTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $pembimbingUser;
    protected User $guruUser;
    protected PembimbingIndustri $pembimbing;
    protected TempatPkl $tempatPkl;
    protected Guru $guru;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Admin
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
            'is_approved' => true,
        ]);

        // 2. Create Tempat PKL
        $this->tempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Solusi Teknologi',
            'alamat' => 'Jl. Merdeka No. 10',
            'bidang_usaha' => 'IT Services',
            'kuota' => 10,
        ]);

        // 3. Create Pembimbing Industri
        $this->pembimbingUser = User::factory()->create([
            'role' => 'pembimbing_industri',
            'is_approved' => true,
        ]);
        $this->pembimbing = PembimbingIndustri::create([
            'user_id' => $this->pembimbingUser->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'no_hp' => '08123456789',
            'jabatan' => 'HR Manager',
        ]);

        // 4. Create Guru
        $this->guruUser = User::factory()->create([
            'role' => 'guru',
            'is_approved' => true,
        ]);
        $this->guru = Guru::create([
            'user_id' => $this->guruUser->id,
            'nip' => '198701012010011002',
            'no_hp' => '082233445566',
        ]);
    }

    public function test_pembimbing_can_view_hubungi_sekolah_index_and_create(): void
    {
        $response = $this->actingAs($this->pembimbingUser)->get(route('pembimbing.hubungi-sekolah.index'));
        $response->assertStatus(302);

        $response2 = $this->actingAs($this->pembimbingUser)->get(route('pembimbing.hubungi-sekolah.create'));
        $response2->assertStatus(200);
    }

    public function test_pembimbing_can_submit_message(): void
    {
        $response = $this->actingAs($this->pembimbingUser)->post(route('pembimbing.hubungi-sekolah.store'), [
            'subjek' => 'Siswa Sering Terlambat',
            'kategori' => 'kendala_siswa',
            'pesan' => 'Mohon dibina karena siswa bersangkutan sering datang terlambat.',
        ]);

        $response->assertRedirect(route('pembimbing.hubungi-sekolah.index'));
        $this->assertDatabaseHas('pesan_pembimbing', [
            'pembimbing_industri_id' => $this->pembimbing->id,
            'subjek' => 'Siswa Sering Terlambat',
            'kategori' => 'kendala_siswa',
            'status' => 'menunggu_tanggapan',
        ]);
    }

    public function test_admin_can_view_and_reply_message(): void
    {
        // Create an aduan first
        $pesan = PesanPembimbing::create([
            'pembimbing_industri_id' => $this->pembimbing->id,
            'subjek' => 'Kendala Administrasi',
            'kategori' => 'administrasi',
            'pesan' => 'Saya belum menerima format lembar penilaian siswa.',
            'status' => 'menunggu_tanggapan',
        ]);

        // Admin checks list
        $response = $this->actingAs($this->adminUser)->get(route('admin.pesan.index'));
        $response->assertStatus(200);

        // Admin views detail
        $response2 = $this->actingAs($this->adminUser)->get(route('admin.pesan.show', $pesan->id));
        $response2->assertStatus(200);

        // Admin replies
        $response3 = $this->actingAs($this->adminUser)->post(route('admin.pesan.reply', $pesan->id), [
            'tanggapan' => 'Sudah kami kirimkan format penilaiannya lewat email, Pak.',
            'status' => 'selesai',
        ]);

        $response3->assertRedirect(route('admin.pesan.show', $pesan->id));
        $this->assertEquals('selesai', $pesan->fresh()->status);
        $this->assertEquals('Sudah kami kirimkan format penilaiannya lewat email, Pak.', $pesan->fresh()->tanggapan);
        $this->assertEquals($this->adminUser->id, $pesan->fresh()->dibalas_oleh_id);
    }

    public function test_guru_can_access_message_if_has_supervised_student_at_the_company(): void
    {
        // 1. Create a student under this guru
        $siswaUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $siswa = Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => '12123434',
            'kelas' => 'XII RPL 1',
            'jurusan' => 'RPL',
            'no_hp' => '08123123123',
            'alamat' => 'Alamat Siswa',
        ]);

        // 2. Create active pengajuan under this guru at this company
        $pengajuan = PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $this->guru->id, // supervised by this guru
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'PKL',
            'status' => 'sedang_pkl',
        ]);

        // 3. Create message from the pembimbing
        $pesan = PesanPembimbing::create([
            'pembimbing_industri_id' => $this->pembimbing->id,
            'subjek' => 'Laporan Progres Mingguan',
            'kategori' => 'administrasi',
            'pesan' => 'Berikut progres siswa minggu ini.',
            'status' => 'menunggu_tanggapan',
        ]);

        // 4. Guru checks list
        $response = $this->actingAs($this->guruUser)->get(route('guru.pesan.index'));
        $response->assertStatus(200);

        // 5. Guru replies
        $response2 = $this->actingAs($this->guruUser)->post(route('guru.pesan.reply', $pesan->id), [
            'tanggapan' => 'Terima kasih atas laporannya.',
            'status' => 'selesai',
        ]);

        $response2->assertRedirect(route('guru.pesan.show', $pesan->id));
        $this->assertEquals('selesai', $pesan->fresh()->status);
        $this->assertEquals('Terima kasih atas laporannya.', $pesan->fresh()->tanggapan);
    }

    public function test_guru_can_view_hubungi_admin_index_and_create(): void
    {
        $response = $this->actingAs($this->guruUser)->get(route('guru.hubungi-admin.index'));
        $response->assertStatus(302);

        $response2 = $this->actingAs($this->guruUser)->get(route('guru.hubungi-admin.create'));
        $response2->assertStatus(200);
    }

    public function test_guru_can_submit_message_to_admin(): void
    {
        $response = $this->actingAs($this->guruUser)->post(route('guru.hubungi-admin.store'), [
            'subjek' => 'Kendala Form Penilaian',
            'kategori' => 'penilaian',
            'pesan' => 'Saya tidak bisa klik simpan nilai sikap siswa.',
        ]);

        $response->assertRedirect(route('guru.hubungi-admin.index'));
        $this->assertDatabaseHas('pesan_guru', [
            'guru_id' => $this->guru->id,
            'subjek' => 'Kendala Form Penilaian',
            'kategori' => 'penilaian',
            'status' => 'menunggu_tanggapan',
        ]);
    }

    public function test_admin_can_view_and_reply_guru_message(): void
    {
        // Create an aduan first
        $pesan = \App\Models\PesanGuru::create([
            'guru_id' => $this->guru->id,
            'subjek' => 'Error Teknis Jurnal',
            'kategori' => 'teknis',
            'pesan' => 'Jurnal siswa Budi tidak muncul di dashboard saya.',
            'status' => 'menunggu_tanggapan',
        ]);

        // Admin checks list
        $response = $this->actingAs($this->adminUser)->get(route('admin.pesan-guru.index'));
        $response->assertStatus(200);

        // Admin views detail
        $response2 = $this->actingAs($this->adminUser)->get(route('admin.pesan-guru.show', $pesan->id));
        $response2->assertStatus(200);

        // Admin replies
        $response3 = $this->actingAs($this->adminUser)->post(route('admin.pesan-guru.reply', $pesan->id), [
            'tanggapan' => 'Sudah kami perbaiki mapping pembimbingnya, silakan cek kembali.',
            'status' => 'selesai',
        ]);

        $response3->assertRedirect(route('admin.pesan-guru.show', $pesan->id));
        $this->assertEquals('selesai', $pesan->fresh()->status);
        $this->assertEquals('Sudah kami perbaiki mapping pembimbingnya, silakan cek kembali.', $pesan->fresh()->tanggapan);
        $this->assertEquals($this->adminUser->id, $pesan->fresh()->dibalas_oleh_id);
    }
}
