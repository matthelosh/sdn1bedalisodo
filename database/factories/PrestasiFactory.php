<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prestasi>
 */
class PrestasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'siswa_id' => uniqid(),
            'kegiatan' => fake()->text(60),
            'bidang' => fake()->randomElement(['Seni','Olahraga','Akademik','MIPA','Agama']),
            'tingkat' => fake()->randomElement(['Sekolah','Gugus','Kecamatan', 'Kabupaten', 'Provinsi','Nasional','Internasional']),
            'peringkat' => fake()->randomElement(['Juara 1', 'Juara 2', 'Juara 3', 'Harapan 1','Harapan 2','Harapan 3']),
            'foto' => fake()->imageUrl(),
            'deskripsi' => fake()->text(500),
            'tanggal' => fake()->date('Y-m-d')
        ];
    }
}
