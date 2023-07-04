<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gurus = Guru::all();
        foreach($gurus as $guru) {
            $namas = explode(",", $guru->nama);
            $username = strtolower(str_replace(" ","",$namas[0]));
            User::create([
                'name' => $username,
                'email' => $username.'@sdn1-bedalisodo.sch.id',
                'email_verified_at' => now(),
                'password' => Hash::make('12345'), // password
                'level' => $guru->role == 'admin' ? 'admin' : 'guru',
                'userable_id' => $guru->id,
                'userable_type' => 'App\Models\Guru'
            ]);
        }
    }
}
