<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin Users
        $this->createAdminUsers();
        
        // Create Doctors
        $this->createDoctors();
        
        // Create Nurses
        $this->createNurses();
        
        // Create Staff
        $this->createStaff();
    }

    /**
     * Create admin users
     */
    private function createAdminUsers(): void
    {
        $admins = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@metrica.com',
                'password' => Hash::make('admin123'),
                'role' => User::ROLE_ADMIN,
                'department' => null,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Hospital Director',
                'email' => 'director@metrica.com',
                'password' => Hash::make('director123'),
                'role' => User::ROLE_ADMIN,
                'department' => null,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }

        $this->command->info('Created ' . count($admins) . ' admin users');
    }

    /**
     * Create doctor users
     */
    private function createDoctors(): void
    {
        $doctors = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@metrica.com',
                'password' => Hash::make('doctor123'),
                'role' => User::ROLE_DOCTOR,
                'department' => User::DEPT_CARDIOLOGY,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'michael.chen@metrica.com',
                'password' => Hash::make('doctor123'),
                'role' => User::ROLE_DOCTOR,
                'department' => User::DEPT_SURGERY,
                'email_verified_at' => now(),
            ],
     
        ];

        foreach ($doctors as $doctor) {
            User::create($doctor);
        }

        $this->command->info('Created ' . count($doctors) . ' doctor users');
    }

    /**
     * Create nurse users
     */
    private function createNurses(): void
    {
        $nurses = [
            [
                'name' => 'Nurse Jennifer Martinez',
                'email' => 'jennifer.martinez@metrica.com',
                'password' => Hash::make('nurse123'),
                'role' => User::ROLE_NURSE,
                'department' => User::DEPT_ICU,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Nurse David Brown',
                'email' => 'david.brown@metrica.com',
                'password' => Hash::make('nurse123'),
                'role' => User::ROLE_NURSE,
                'department' => User::DEPT_EMERGENCY,
                'email_verified_at' => now(),
            ],
  
        ];

        foreach ($nurses as $nurse) {
            User::create($nurse);
        }

        $this->command->info('Created ' . count($nurses) . ' nurse users');
    }

    /**
     * Create staff users
     */
    private function createStaff(): void
    {
        $staff = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@metrica.com',
                'password' => Hash::make('staff123'),
                'role' => User::ROLE_STAFF,
                'department' => User::DEPT_EMERGENCY,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mary Johnson',
                'email' => 'mary.johnson@metrica.com',
                'password' => Hash::make('staff123'),
                'role' => User::ROLE_STAFF,
                'department' => User::DEPT_ICU,
                'email_verified_at' => now(),
            ],
    
        ];

        foreach ($staff as $staffMember) {
            User::create($staffMember);
        }

        $this->command->info('Created ' . count($staff) . ' staff users');
    }
}