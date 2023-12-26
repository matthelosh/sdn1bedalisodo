<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

            User::create([
                'name' => 'admin',
                'email' => 'admin@sdn1-bedalisodo.sch.id',
                'email_verified_at' => now(),
                'password' => Hash::make('jirolu'), // password
                'level' => 'admin',
                'userable_id' => '1',
                'userable_type' => 'App\Models\Guru'
            ]);
       
    }
}
