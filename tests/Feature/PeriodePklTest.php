<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\TempatPkl;
use App\Models\PengajuanPkl;
use App\Models\PeriodePkl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeriodePklTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $siswaUser;
    protected Siswa $siswa;
    protected TempatPkl $tempatPkl;

    protected function setUp(): void
    {
        parent::setUp();

        // Admin
        $this->adminUser = User::factory()->create(['role' => 'admin', 'is_approved' => true]);

        // Siswa
        $this->siswaUser = User::factory()->create(['role' => 'siswa', 'is_approved' => true]);
        $this->siswa = Siswa::create([
            'user_id' => $this->siswaUser->id,
            'nis' => '12345',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'alamat' => 'Siswa 1',
            'no_hp' => '08123',
        ]);

        $this->tempatPkl = TempatPkl::create([
            'nama_tempat' => 'PT Satu Kuota',
            'alamat' => 'Jl. Kuota No. 1',
            'bidang_usaha' => 'IT',
            'kuota' => 1,
        ]);
    }

    public function test_admin_can_manage_periods(): void
    {
        // 1. Create period
        $response = $this->actingAs($this->adminUser)->post(route('admin.periode-pkl.store'), [
            'nama_periode' => 'PKL 2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status_aktif' => '1',
        ]);

        $response->assertRedirect(route('admin.periode-pkl.index'));
        $this->assertDatabaseHas('periode_pkl', [
            'nama_periode' => 'PKL 2025/2026',
            'status_aktif' => true,
        ]);

        $period = PeriodePkl::first();

        // 2. Create another active period - should deactivate first one
        $response2 = $this->actingAs($this->adminUser)->post(route('admin.periode-pkl.store'), [
            'nama_periode' => 'PKL 2026/2027',
            'tanggal_mulai' => '2026-07-01',
            'tanggal_selesai' => '2026-12-31',
            'status_aktif' => '1',
        ]);

        $this->assertFalse($period->fresh()->status_aktif);
        $this->assertTrue(PeriodePkl::where('nama_periode', 'PKL 2026/2027')->first()->status_aktif);

        // 3. Admin cannot delete active period
        $activePeriod = PeriodePkl::where('nama_periode', 'PKL 2026/2027')->first();
        $responseDeleteActive = $this->actingAs($this->adminUser)->delete(route('admin.periode-pkl.destroy', $activePeriod));
        $responseDeleteActive->assertSessionHasErrors('msg');
        $this->assertDatabaseHas('periode_pkl', ['id' => $activePeriod->id]);

        // 4. Admin can delete inactive period
        $responseDeleteInactive = $this->actingAs($this->adminUser)->delete(route('admin.periode-pkl.destroy', $period));
        $responseDeleteInactive->assertRedirect(route('admin.periode-pkl.index'));
        $this->assertDatabaseMissing('periode_pkl', ['id' => $period->id]);
    }

    public function test_siswa_cannot_apply_when_no_active_period(): void
    {
        // Clear all periods first
        PeriodePkl::query()->delete();

        $response = $this->actingAs($this->siswaUser)->post(route('siswa.pengajuan.store'), [
            'tempat_pkl_id' => $this->tempatPkl->id,
            'tanggal_mulai' => '2025-08-01',
            'tanggal_selesai' => '2025-11-01',
            'alasan' => 'PKL 1',
        ]);

        $response->assertSessionHasErrors('msg');
        $this->assertEquals(0, PengajuanPkl::count());
    }

    public function test_siswa_application_automatically_links_to_active_period(): void
    {
        // Clear all periods first
        PeriodePkl::query()->delete();

        $period = PeriodePkl::create([
            'nama_periode' => 'PKL 2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($this->siswaUser)->post(route('siswa.pengajuan.store'), [
            'tempat_pkl_id' => $this->tempatPkl->id,
            'tanggal_mulai' => '2025-08-01',
            'tanggal_selesai' => '2025-11-01',
            'alasan' => 'PKL 1',
        ]);

        $response->assertRedirect(route('siswa.pengajuan.index'));
        $this->assertEquals(1, PengajuanPkl::count());
        $this->assertEquals($period->id, PengajuanPkl::first()->periode_pkl_id);
    }

    public function test_quota_does_not_count_placements_from_inactive_periods(): void
    {
        // Clear all periods first
        PeriodePkl::query()->delete();

        // 1. Inactive period placement
        $periodPast = PeriodePkl::create([
            'nama_periode' => 'PKL 2024/2025',
            'tanggal_mulai' => '2024-07-01',
            'tanggal_selesai' => '2024-12-31',
            'status_aktif' => false,
        ]);

        // Place student in past period (approving it)
        PengajuanPkl::create([
            'siswa_id' => $this->siswa->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'periode_pkl_id' => $periodPast->id,
            'tanggal_mulai' => '2024-08-01',
            'tanggal_selesai' => '2024-11-01',
            'alasan' => 'PKL Past',
            'status' => 'disetujui',
        ]);

        // 2. Active period
        $periodActive = PeriodePkl::create([
            'nama_periode' => 'PKL 2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status_aktif' => true,
        ]);

        // Place quota check should be free for active period because the old placement was in an inactive period
        $this->assertEquals(1, $this->tempatPkl->fresh()->sisa_kuota);
        $this->assertFalse($this->tempatPkl->fresh()->is_penuh);
    }

    public function test_admin_can_filter_guru_by_period(): void
    {
        // 1. Create two periods
        $periodA = PeriodePkl::create([
            'nama_periode' => 'Periode A',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-06-30',
            'status_aktif' => true,
        ]);
        $periodB = PeriodePkl::create([
            'nama_periode' => 'Periode B',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status_aktif' => false,
        ]);

        // 2. Create a Guru
        $guruUser = User::factory()->create(['role' => 'guru', 'is_approved' => true]);
        $guru = Guru::create([
            'user_id' => $guruUser->id,
            'nip' => '99999',
            'alamat' => 'Alamat Guru',
            'no_hp' => '08999',
        ]);

        // 3. Create a placement in Period A associated with Guru
        PengajuanPkl::create([
            'siswa_id' => $this->siswa->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $guru->id,
            'periode_pkl_id' => $periodA->id,
            'tanggal_mulai' => '2025-02-01',
            'tanggal_selesai' => '2025-05-01',
            'alasan' => 'Bimbingan A',
            'status' => 'disetujui',
        ]);

        // 4. Request index with period A
        $responseA = $this->actingAs($this->adminUser)->get(route('admin.guru.index', ['periode_id' => $periodA->id]));
        $responseA->assertStatus(200);
        $responseA->assertSee($guruUser->name);

        // 5. Request index with period B
        $responseB = $this->actingAs($this->adminUser)->get(route('admin.guru.index', ['periode_id' => $periodB->id]));
        $responseB->assertStatus(200);
        $responseB->assertDontSee($guruUser->name);
    }

    public function test_admin_can_filter_tempat_pkl_by_period(): void
    {
        // 1. Create two periods
        $periodA = PeriodePkl::create([
            'nama_periode' => 'Periode A',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-06-30',
            'status_aktif' => true,
        ]);
        $periodB = PeriodePkl::create([
            'nama_periode' => 'Periode B',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status_aktif' => false,
        ]);

        // 2. Create another Tempat PKL
        $anotherTempat = TempatPkl::create([
            'nama_tempat' => 'PT Filter Test',
            'alamat' => 'Alamat Test',
            'bidang_usaha' => 'Hardware',
            'kuota' => 2,
        ]);

        // 3. Create placement in Period A for PT Filter Test
        PengajuanPkl::create([
            'siswa_id' => $this->siswa->id,
            'tempat_pkl_id' => $anotherTempat->id,
            'periode_pkl_id' => $periodA->id,
            'tanggal_mulai' => '2025-02-01',
            'tanggal_selesai' => '2025-05-01',
            'alasan' => 'Test A',
            'status' => 'disetujui',
        ]);

        // 4. Request index with period A -> should see PT Filter Test
        $responseA = $this->actingAs($this->adminUser)->get(route('admin.tempat-pkl.index', ['periode_id' => $periodA->id]));
        $responseA->assertStatus(200);
        $responseA->assertSee('PT Filter Test');

        // 5. Request index with period B -> should not see PT Filter Test
        $responseB = $this->actingAs($this->adminUser)->get(route('admin.tempat-pkl.index', ['periode_id' => $periodB->id]));
        $responseB->assertStatus(200);
        $responseB->assertDontSee('PT Filter Test');
    }

    public function test_admin_can_filter_pembimbing_industri_by_period(): void
    {
        // 1. Create two periods
        $periodA = PeriodePkl::create([
            'nama_periode' => 'Periode A',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-06-30',
            'status_aktif' => true,
        ]);
        $periodB = PeriodePkl::create([
            'nama_periode' => 'Periode B',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status_aktif' => false,
        ]);

        // 2. Create Tempat PKL and Pembimbing Industri
        $tempat = TempatPkl::create([
            'nama_tempat' => 'PT Pembimbing Test',
            'alamat' => 'Alamat Test',
            'bidang_usaha' => 'Security',
            'kuota' => 2,
        ]);
        $pembimbingUser = User::factory()->create(['role' => 'pembimbing_industri', 'is_approved' => true]);
        $pembimbing = \App\Models\PembimbingIndustri::create([
            'user_id' => $pembimbingUser->id,
            'tempat_pkl_id' => $tempat->id,
            'no_hp' => '08777',
            'jabatan' => 'Supervisor',
        ]);

        // 3. Create placement in Period A for this tempat
        PengajuanPkl::create([
            'siswa_id' => $this->siswa->id,
            'tempat_pkl_id' => $tempat->id,
            'periode_pkl_id' => $periodA->id,
            'tanggal_mulai' => '2025-02-01',
            'tanggal_selesai' => '2025-05-01',
            'alasan' => 'Test A',
            'status' => 'disetujui',
        ]);

        // 4. Request index with period A -> should see supervisor name
        $responseA = $this->actingAs($this->adminUser)->get(route('admin.pembimbing-industri.index', ['periode_id' => $periodA->id]));
        $responseA->assertStatus(200);
        $responseA->assertSee($pembimbingUser->name);

        // 5. Request index with period B -> should not see supervisor name
        $responseB = $this->actingAs($this->adminUser)->get(route('admin.pembimbing-industri.index', ['periode_id' => $periodB->id]));
        $responseB->assertStatus(200);
        $responseB->assertDontSee($pembimbingUser->name);
    }

    public function test_admin_cannot_delete_period_with_placements(): void
    {
        $period = PeriodePkl::create([
            'nama_periode' => 'Periode Stale',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-06-30',
            'status_aktif' => false,
        ]);

        PengajuanPkl::create([
            'siswa_id' => $this->siswa->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'periode_pkl_id' => $period->id,
            'tanggal_mulai' => '2025-02-01',
            'tanggal_selesai' => '2025-05-01',
            'alasan' => 'Stale Placement',
            'status' => 'disetujui',
        ]);

        $response = $this->actingAs($this->adminUser)->delete(route('admin.periode-pkl.destroy', $period));
        $response->assertSessionHasErrors('msg');
        $this->assertDatabaseHas('periode_pkl', ['id' => $period->id]);
    }

    public function test_siswa_cannot_log_journal_for_stale_period(): void
    {
        // 1. Create inactive period and link student placement to it
        $periodPast = PeriodePkl::create([
            'nama_periode' => 'Periode Past',
            'tanggal_mulai' => '2024-01-01',
            'tanggal_selesai' => '2024-06-30',
            'status_aktif' => false,
        ]);
        
        $pengajuanPast = PengajuanPkl::create([
            'siswa_id' => $this->siswa->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'periode_pkl_id' => $periodPast->id,
            'tanggal_mulai' => '2024-02-01',
            'tanggal_selesai' => '2024-05-01',
            'alasan' => 'Past Placement',
            'status' => 'disetujui',
        ]);

        // 2. Create active period (where student has no placement)
        $periodActive = PeriodePkl::create([
            'nama_periode' => 'Periode Active',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-06-30',
            'status_aktif' => true,
        ]);

        // Try to access journal create page
        $response = $this->actingAs($this->siswaUser)->get(route('siswa.jurnal.create'));
        $response->assertRedirect(route('siswa.jurnal.index'));
        $response->assertSessionHasErrors('msg');

        // Try to store a journal
        $responseStore = $this->actingAs($this->siswaUser)->post(route('siswa.jurnal.store'), [
            'tanggal' => '2025-02-01',
            'kegiatan' => 'Doing PKL work',
            'kendala' => 'None',
        ]);
        $responseStore->assertRedirect(route('siswa.jurnal.index'));
        $responseStore->assertSessionHasErrors('msg');
    }

    public function test_siswa_cannot_edit_past_period_pengajuan(): void
    {
        $periodPast = PeriodePkl::create([
            'nama_periode' => 'Periode Past',
            'tanggal_mulai' => '2024-01-01',
            'tanggal_selesai' => '2024-06-30',
            'status_aktif' => false,
        ]);

        $pengajuanPast = PengajuanPkl::create([
            'siswa_id' => $this->siswa->id,
            'tempat_pkl_id' => $this->tempatPkl->id,
            'periode_pkl_id' => $periodPast->id,
            'tanggal_mulai' => '2024-02-01',
            'tanggal_selesai' => '2024-05-01',
            'alasan' => 'Past Placement',
            'status' => 'draft',
        ]);

        $periodActive = PeriodePkl::create([
            'nama_periode' => 'Periode Active',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-06-30',
            'status_aktif' => true,
        ]);

        // Try to edit
        $responseEdit = $this->actingAs($this->siswaUser)->get(route('siswa.pengajuan.edit', $pengajuanPast));
        $responseEdit->assertRedirect();
        $responseEdit->assertSessionHasErrors('msg');

        // Try to update
        $responseUpdate = $this->actingAs($this->siswaUser)->put(route('siswa.pengajuan.update', $pengajuanPast), [
            'tempat_pkl_id' => $this->tempatPkl->id,
            'tanggal_mulai' => '2024-02-01',
            'tanggal_selesai' => '2024-05-01',
            'alasan' => 'Stale Update',
        ]);
        $responseUpdate->assertRedirect();
        $responseUpdate->assertSessionHasErrors('msg');

        // Try to submit (ajukan)
        $responseAjukan = $this->actingAs($this->siswaUser)->put(route('siswa.pengajuan.ajukan', $pengajuanPast));
        $responseAjukan->assertRedirect();
        $responseAjukan->assertSessionHasErrors('msg');

        // Try to delete
        $responseDelete = $this->actingAs($this->siswaUser)->delete(route('siswa.pengajuan.destroy', $pengajuanPast));
        $responseDelete->assertRedirect();
        $responseDelete->assertSessionHasErrors('msg');
    }

    public function test_siswa_cannot_set_period_selection_session(): void
    {
        $period = PeriodePkl::create([
            'nama_periode' => 'Periode Select',
            'tanggal_mulai' => '2025-01-01',
            'tanggal_selesai' => '2025-06-30',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($this->siswaUser)->post(route('periode-pkl.select'), [
            'periode_pkl_id' => $period->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_view_dashboard_with_charts(): void
    {
        $period = PeriodePkl::create([
            'nama_periode' => 'PKL 2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status_aktif' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('mitraQuotaStats');
        $response->assertViewHas('periodeStats');
    }
}


