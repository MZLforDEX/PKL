<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\LaporanPkl;
use App\Models\PengajuanPkl;
use App\Models\Siswa;
use App\Models\TempatPkl;
use App\Models\User;
use App\Notifications\SiswaUploadLaporan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanPklRevisiTest extends TestCase
{
    use RefreshDatabase;

    private User $siswaUser;

    private Siswa $siswa;

    private PengajuanPkl $pengajuan;

    private LaporanPkl $laporan;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Notification::fake();

        $this->siswaUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $this->siswa = Siswa::create([
            'user_id' => $this->siswaUser->id,
            'nis' => '12345678',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Alamat Siswa',
            'no_hp' => '081111111111',
        ]);

        $guruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $guru = Guru::create([
            'user_id' => $guruUser->id,
            'nip' => '9999999999999999',
            'alamat' => 'Alamat Guru',
            'no_hp' => '089999999999',
        ]);

        $tempat = TempatPkl::create([
            'nama_tempat' => 'PT Makmur',
            'alamat' => 'Jl. Makmur',
            'bidang_usaha' => 'Bisnis',
            'kontak_person' => 'Jane',
            'no_hp' => '082222222222',
            'email' => 'jane@makmur.com',
            'kuota' => 5,
        ]);

        $this->pengajuan = PengajuanPkl::create([
            'siswa_id' => $this->siswa->id,
            'tempat_pkl_id' => $tempat->id,
            'guru_id' => $guru->id,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
            'alasan' => 'Ingin PKL di sini.',
            'status' => 'sedang_pkl',
        ]);

        $this->laporan = LaporanPkl::create([
            'pengajuan_pkl_id' => $this->pengajuan->id,
            'file_laporan' => 'laporan/lama.pdf',
            'status' => 'revisi',
            'catatan_guru' => 'Perbaiki bab kesimpulan.',
        ]);
    }

    public function test_siswa_can_view_edit_page_when_laporan_needs_revision(): void
    {
        $response = $this->actingAs($this->siswaUser)
            ->get(route('siswa.laporan.edit', $this->laporan));

        $response->assertOk();
        $response->assertSee('Perbaiki Laporan PKL');
        $response->assertSee('Perbaiki bab kesimpulan.');
    }

    public function test_siswa_can_resubmit_laporan_after_revision(): void
    {
        $guruUser = $this->pengajuan->guru->user;

        $file = UploadedFile::fake()->create('laporan-baru.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->siswaUser)
            ->put(route('siswa.laporan.update', $this->laporan), [
                'file_laporan' => $file,
            ]);

        $response->assertRedirect(route('siswa.laporan.index'));
        $response->assertSessionHas('success');

        $this->laporan->refresh();
        $this->assertEquals('menunggu_review', $this->laporan->status);
        $this->assertNull($this->laporan->catatan_guru);
        Storage::disk('public')->assertExists($this->laporan->file_laporan);

        Notification::assertSentTo($guruUser, SiswaUploadLaporan::class);
    }

    public function test_siswa_cannot_edit_laporan_when_not_in_revision(): void
    {
        $this->laporan->update(['status' => 'menunggu_review', 'catatan_guru' => null]);

        $response = $this->actingAs($this->siswaUser)
            ->get(route('siswa.laporan.edit', $this->laporan));

        $response->assertRedirect(route('siswa.laporan.index'));
        $response->assertSessionHasErrors('msg');
    }

    public function test_other_siswa_cannot_edit_laporan(): void
    {
        $otherUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        Siswa::create([
            'user_id' => $otherUser->id,
            'nis' => '87654321',
            'kelas' => 'XII',
            'jurusan' => 'TKJ',
            'alamat' => 'Alamat Lain',
            'no_hp' => '081222222222',
        ]);

        $response = $this->actingAs($otherUser)
            ->get(route('siswa.laporan.edit', $this->laporan));

        $response->assertForbidden();
    }
}
