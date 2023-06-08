<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sekolah::create([
            'npsn' => '20518848',
            'nss' => '-',
            'nama' => 'SD Negeri 1 Bedalisodo',
            'alamat' => 'Jl. Raya Sengon No. 293',
            'desa' => 'Dalisodo',
            'kecamatan' => 'Wagir',
            'kabupaten' => 'Malang',
            'provinsi' => 'Jawa Timur',
            'kode_pos' => '65158',
            'telp' => '-',
            'email' => 'info@sdn1-bedalisodo.sch.id',
            'website' => 'https://sdn1-bedalisodo.sch.id',
        ]);
    }
}
