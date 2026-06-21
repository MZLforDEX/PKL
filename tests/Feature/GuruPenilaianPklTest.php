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

class GuruPenilaianPklTest extends TestCase
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

        $this->tempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Makmur Jaya',
            'alamat' => 'Jl. Makmur No. 1',
            'bidang_usaha' => 'Teknologi',
            'kuota' => 5,
        ]);

        $this->guruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $this->guru = Guru::create([
            'user_id' => $this->guruUser->id,
            'nip' => '1234567890123456',
            'alamat' => 'Jl. Guru',
            'no_hp' => '081234567890',
        ]);

        $this->otherGuruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $this->otherGuru = Guru::create([
            'user_id' => $this->otherGuruUser->id,
            'nip' => '1234567890123457',
            'alamat' => 'Jl. Guru Lain',
            'no_hp' => '081234567891',
        ]);

        $this->siswaUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $this->siswa = Siswa::create([
            'user_id' => $this->siswaUser->id,
            'nis' => '11223344',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Jl. Siswa',
            'no_hp' => '081122334455',
        ]);

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

    public function test_other_guru_does_not_see_unassigned_student_in_penilaian_index(): void
    {
        $response = $this->actingAs($this->otherGuruUser)->get(route('guru.penilaian.index'));
        $response->assertStatus(200);
        $response->assertDontSee($this->siswaUser->name);
    }

    public function test_non_guru_cannot_access_guru_penilaian_index(): void
    {
        $response = $this->actingAs($this->siswaUser)->get(route('guru.penilaian.index'));
        $response->assertStatus(403);
    }
}
