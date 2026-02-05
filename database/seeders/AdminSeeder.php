<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(! User::query()->where('role', '=','admin')->exists()){
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'password' => Hash::make('123123123'),
            ]);
        }
    }
}
