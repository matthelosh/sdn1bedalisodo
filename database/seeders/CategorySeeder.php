<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'kode' => 'art',
                'label' => 'Artikel'
            ],
            [
                'kode' => 'inf',
                'label' => 'Informasi'
            ],
            [
                'kode' => 'brt',
                'label' => 'Berita'
            ],
        ];

        foreach($categories as $category) {
            Category::create([
                'kode' => $category['kode'],
                'label' => $category['label']
            ]);
        }
    }
}
