<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Food::create([
            'food_id' => 1,
            'food_name' => 'Nasi',
        ]);

        User::create([
            'email' => 'berhasil@gmail.com',
            'password' => Hash::make('123456'), // Enkripsi password
        ]);
        
        User::create([
            'email' => 'gagal@gmail.com',
            'password' => Hash::make('123456'), // Enkripsi password
        ]);
    }
}
