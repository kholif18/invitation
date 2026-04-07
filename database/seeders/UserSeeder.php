<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'ravaacreative@gmail.com'],
            [
                'name' => 'Ravaa Creative',
                'email' => 'ravaacreative@gmail.com',
                'password' => Hash::make('r4v44-CREATIVE'),
                'email_verified_at' => now(),
                'address' => 'Ngluyu, Kec. ngluyu, Kabupaten Nganjuk, Jawa Timur',
                'status' => 'active',
            ]
        );

        $this->command->info('Admin: ravaacreative@gmail.com / r4v44-CREATIVE');
    }
}
