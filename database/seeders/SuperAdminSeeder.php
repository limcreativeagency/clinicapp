<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@myclinic.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@myclinic.com',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_SUPER_ADMIN,
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Super admin kullanıcısı oluşturuldu:');
        $this->command->info('E-posta: admin@myclinic.com');
        $this->command->info('Şifre: password123');
    }
}
