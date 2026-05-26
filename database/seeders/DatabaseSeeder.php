<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\TempatPkl;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::factory()->create([
            'name' => 'Admin PKL',
            'email' => 'admin@pkl.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_approved' => true,
        ]);

        // Guru
        $guru1 = User::factory()->create([
            'name' => 'Guru Satu',
            'email' => 'guru1@pkl.test',
            'password' => bcrypt('password'),
            'role' => 'guru',
            'is_approved' => true,
        ]);
        Guru::create(['user_id' => $guru1->id, 'nip' => '198001012010011001', 'alamat' => 'Jl. Contoh No. 1', 'no_hp' => '081111111111']);

        $guru2 = User::factory()->create([
            'name' => 'Guru Dua',
            'email' => 'guru2@pkl.test',
            'password' => bcrypt('password'),
            'role' => 'guru',
            'is_approved' => true,
        ]);
        Guru::create(['user_id' => $guru2->id, 'nip' => '198001012010011002', 'alamat' => 'Jl. Contoh No. 2', 'no_hp' => '082222222222']);

        // Siswa
        for ($i = 1; $i <= 5; $i++) {
            $user = User::factory()->create([
                'name' => "Siswa $i",
                'email' => "siswa$i@pkl.test",
                'password' => bcrypt('password'),
                'role' => 'siswa',
                'is_approved' => true,
            ]);
            Siswa::create([
                'user_id' => $user->id,
                'nis' => '2024' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'kelas' => 'XII',
                'jurusan' => 'Rekayasa Perangkat Lunak',
                'alamat' => "Jl. Siswa No. $i",
                'no_hp' => "08$i" . str_repeat('0', 8) . $i,
            ]);
        }

        // Tempat PKL
        $tempat = [
            ['nama_tempat' => 'PT Teknologi Maju', 'alamat' => 'Jl. Industri No. 10', 'bidang_usaha' => 'Teknologi Informasi', 'kuota' => 3],
            ['nama_tempat' => 'CV Kreatif Digital', 'alamat' => 'Jl. Inovasi No. 25', 'bidang_usaha' => 'Desain Grafis', 'kuota' => 2],
            ['nama_tempat' => 'Rumah Sakit Sehat', 'alamat' => 'Jl. Kesehatan No. 5', 'bidang_usaha' => 'Kesehatan', 'kuota' => 2],
            ['nama_tempat' => 'Bank Mandiri Cab. Kolaka', 'alamat' => 'Jl. Ahmad Yani No. 1', 'bidang_usaha' => 'Perbankan', 'kuota' => 4],
            ['nama_tempat' => 'Dinas Pendidikan Kolaka', 'alamat' => 'Jl. Pendidikan No. 7', 'bidang_usaha' => 'Pendidikan', 'kuota' => 3],
        ];

        $tempatModels = [];
        foreach ($tempat as $t) {
            $tempatModels[] = TempatPkl::create($t);
        }

        // Pembimbing Industri
        $pembimbingUser = User::factory()->create([
            'name' => 'Budi Industri',
            'email' => 'pembimbing@pkl.test',
            'password' => bcrypt('password'),
            'role' => 'pembimbing_industri',
            'is_approved' => true,
        ]);
        \App\Models\PembimbingIndustri::create([
            'user_id' => $pembimbingUser->id,
            'tempat_pkl_id' => $tempatModels[0]->id, // PT Teknologi Maju
            'no_hp' => '087777777777',
            'jabatan' => 'HRD Manager',
        ]);
    }
}
