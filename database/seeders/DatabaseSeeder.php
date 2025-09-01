<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,           // Create users first
            ResourceSeeder::class,       // Create hospital resources
            IncidentSeeder::class,       // Create incidents with actions
            NotificationSeeder::class,   // Create notifications
        ]);

        $this->command->info('🏥 Hospital Management System seeded successfully!');
        $this->command->info('');
        $this->command->info('📊 Seeded Data Summary:');
        $this->command->info('👥 Users: Admins, Doctors, Nurses, Staff');
        $this->command->info('🏥 Resources: Beds, Equipment, Staff Resources');
        $this->command->info('🚨 Incidents: Sample incidents with chat messages');
        $this->command->info('🔔 Notifications: User notifications for incidents');
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('Admin: admin@metrica.com / admin123');
        $this->command->info('Doctor: sarah.johnson@metrica.com / doctor123');
        $this->command->info('Nurse: jennifer.martinez@metrica.com / nurse123');
        $this->command->info('Staff: john.smith@metrica.com / staff123');
    }
}