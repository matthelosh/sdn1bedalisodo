<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'label' => 'Dashboard',
                'url' => 'dashboard',
                'icon' => 'view-dashboard-outline',
                'roles' => 'all',
                'status' => 1,
                'parent_id' => '0'
            ],
            [
                'label' => 'Post',
                'url' => 'dashboard.post',
                'icon' => 'post',
                'roles' => 'all',
                'status' => 1,
                'parent_id' => '0'
            ],
        ];
        
        foreach($menus as $menu) {
            Menu::create([
                'label' => $menu['label'],
                'url' => $menu['url'],
                'icon' => $menu['icon'],
                'roles' => $menu['roles'],
                'status' => $menu['status'],
                'parent_id' => $menu['parent_id']
            ]);
        }
    }
}
