<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createBeds();
        $this->createEquipment();
        $this->createStaffResources();
    }

    /**
     * Create bed resources
     */
    private function createBeds(): void
    {
        $beds = [
            // ICU Beds
            ['name' => 'ICU Bed 1', 'location' => 'ICU Ward A', 'status' => 'available'],
            ['name' => 'ICU Bed 2', 'location' => 'ICU Ward A', 'status' => 'in_use'],
            ['name' => 'ICU Bed 3', 'location' => 'ICU Ward A', 'status' => 'available'],
            ['name' => 'ICU Bed 4', 'location' => 'ICU Ward B', 'status' => 'available'],
            ['name' => 'ICU Bed 5', 'location' => 'ICU Ward B', 'status' => 'maintenance'],
            ['name' => 'ICU Bed 6', 'location' => 'ICU Ward B', 'status' => 'available'],

            // Emergency Beds
            ['name' => 'ER Bed 1', 'location' => 'Emergency Room', 'status' => 'available'],
            ['name' => 'ER Bed 2', 'location' => 'Emergency Room', 'status' => 'in_use'],
            ['name' => 'ER Bed 3', 'location' => 'Emergency Room', 'status' => 'available'],
            ['name' => 'ER Bed 4', 'location' => 'Emergency Room', 'status' => 'available'],

            // Surgery Beds
            ['name' => 'OR Bed 1', 'location' => 'Operating Room 1', 'status' => 'available'],
            ['name' => 'OR Bed 2', 'location' => 'Operating Room 2', 'status' => 'in_use'],
            ['name' => 'OR Bed 3', 'location' => 'Operating Room 3', 'status' => 'available'],

            // General Ward Beds
            ['name' => 'Ward Bed 101', 'location' => 'General Ward 1', 'status' => 'available'],
            ['name' => 'Ward Bed 102', 'location' => 'General Ward 1', 'status' => 'in_use'],
            ['name' => 'Ward Bed 103', 'location' => 'General Ward 1', 'status' => 'available'],
            ['name' => 'Ward Bed 201', 'location' => 'General Ward 2', 'status' => 'available'],
            ['name' => 'Ward Bed 202', 'location' => 'General Ward 2', 'status' => 'available'],
        ];

        foreach ($beds as $bed) {
            Resource::create([
                'name' => $bed['name'],
                'type' => Resource::TYPE_BED,
                'status' => $bed['status'],
                'location' => $bed['location'],
                'description' => 'Hospital bed with standard medical equipment',
            ]);
        }

        $this->command->info('Created ' . count($beds) . ' bed resources');
    }

    /**
     * Create equipment resources
     */
    private function createEquipment(): void
    {
        $equipment = [
            // Ventilators
            ['name' => 'Ventilator V-001', 'location' => 'ICU Ward A', 'status' => 'available'],
            ['name' => 'Ventilator V-002', 'location' => 'ICU Ward A', 'status' => 'in_use'],
            ['name' => 'Ventilator V-003', 'location' => 'ICU Ward B', 'status' => 'available'],
            ['name' => 'Ventilator V-004', 'location' => 'Emergency Room', 'status' => 'available'],
            ['name' => 'Ventilator V-005', 'location' => 'Operating Room 1', 'status' => 'maintenance'],

            // Monitors
            ['name' => 'Cardiac Monitor CM-001', 'location' => 'ICU Ward A', 'status' => 'available'],
            ['name' => 'Cardiac Monitor CM-002', 'location' => 'ICU Ward A', 'status' => 'in_use'],
            ['name' => 'Cardiac Monitor CM-003', 'location' => 'ICU Ward B', 'status' => 'available'],
            ['name' => 'Cardiac Monitor CM-004', 'location' => 'Emergency Room', 'status' => 'available'],

            // Defibrillators
            ['name' => 'Defibrillator DF-001', 'location' => 'Emergency Room', 'status' => 'available'],
            ['name' => 'Defibrillator DF-002', 'location' => 'ICU Ward A', 'status' => 'available'],
            ['name' => 'Defibrillator DF-003', 'location' => 'Operating Room 1', 'status' => 'in_use'],

            // X-Ray Machines
            ['name' => 'X-Ray Machine XR-001', 'location' => 'Radiology Department', 'status' => 'available'],
            ['name' => 'X-Ray Machine XR-002', 'location' => 'Emergency Room', 'status' => 'available'],

            // Wheelchairs
            ['name' => 'Wheelchair WC-001', 'location' => 'General Ward 1', 'status' => 'available'],
            ['name' => 'Wheelchair WC-002', 'location' => 'General Ward 2', 'status' => 'available'],
            ['name' => 'Wheelchair WC-003', 'location' => 'Emergency Room', 'status' => 'in_use'],

            // IV Pumps
            ['name' => 'IV Pump IP-001', 'location' => 'ICU Ward A', 'status' => 'available'],
            ['name' => 'IV Pump IP-002', 'location' => 'ICU Ward B', 'status' => 'in_use'],
            ['name' => 'IV Pump IP-003', 'location' => 'General Ward 1', 'status' => 'available'],
        ];

        foreach ($equipment as $item) {
            Resource::create([
                'name' => $item['name'],
                'type' => Resource::TYPE_EQUIPMENT,
                'status' => $item['status'],
                'location' => $item['location'],
                'description' => 'Medical equipment for patient care',
            ]);
        }

        $this->command->info('Created ' . count($equipment) . ' equipment resources');
    }

    /**
     * Create staff resources
     */
    private function createStaffResources(): void
    {
        $staffResources = [
            // Nurses
            ['name' => 'Nurse Station A', 'location' => 'ICU Ward A', 'status' => 'available'],
            ['name' => 'Nurse Station B', 'location' => 'ICU Ward B', 'status' => 'available'],
            ['name' => 'Emergency Nurse Station', 'location' => 'Emergency Room', 'status' => 'available'],
            ['name' => 'Surgery Nurse Station', 'location' => 'Operating Room Complex', 'status' => 'available'],

            // Technicians
            ['name' => 'Radiology Technician', 'location' => 'Radiology Department', 'status' => 'available'],
            ['name' => 'Lab Technician', 'location' => 'Laboratory', 'status' => 'available'],
            ['name' => 'Equipment Technician', 'location' => 'Maintenance Department', 'status' => 'available'],

            // Support Staff
            ['name' => 'Transport Team A', 'location' => 'Hospital Wide', 'status' => 'available'],
            ['name' => 'Transport Team B', 'location' => 'Hospital Wide', 'status' => 'in_use'],
            ['name' => 'Cleaning Team ICU', 'location' => 'ICU Complex', 'status' => 'available'],
        ];

        foreach ($staffResources as $staff) {
            Resource::create([
                'name' => $staff['name'],
                'type' => Resource::TYPE_STAFF,
                'status' => $staff['status'],
                'location' => $staff['location'],
                'description' => 'Staff resource for patient care and hospital operations',
            ]);
        }

        $this->command->info('Created ' . count($staffResources) . ' staff resources');
    }
}