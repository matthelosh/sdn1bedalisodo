<?php

namespace Database\Seeders;

use App\Models\Layout;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layouts = ['Default', 'Mandita', 'Light', 'Duatiga'];

        foreach($layouts as $layout)
        {
            Layout::create(['name' => $layout]);
        }
    }
}
