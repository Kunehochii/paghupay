<?php

namespace Database\Seeders;

use App\Models\CounselorProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User with Admin ID
        User::create([
            'name' => 'System Administrator',
            'admin_id' => 'ADMIN-001',
            'email' => 'admin@tupv.edu.ph', // Optional, for reference
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Sample Counselor (uses email for login - unchanged)
        $counselor = User::create([
            'name' => 'Dr. Maria Santos',
            'email' => 'maria.santos@tupv.edu.ph',
            'password' => Hash::make('counselor123'),
            'role' => 'counselor',
            'is_active' => true,
        ]);

        CounselorProfile::create([
            'user_id' => $counselor->id,
            'position' => 'Head Psychologist',
            'device_token' => null, // Set on first login
        ]);

        // Create Sample Client (Student) with TUPV ID - Inactive (needs onboarding)
        User::create([
            'name' => 'Juan Dela Cruz',
            'tupv_id' => 'TUPV-24-0001',
            'email' => 'juan.delacruz@tupv.edu.ph', // Optional
            'password' => Hash::make('student123'),
            'role' => 'client',
            'is_active' => false, // Needs onboarding
        ]);

        // Create another Sample Client (Student) with TUPV ID - Active
        User::create([
            'name' => 'Maria Santos',
            'tupv_id' => 'TUPV-23-0042',
            'email' => null, // Email is optional for students
            'password' => Hash::make('student123'),
            'role' => 'client',
            'nickname' => 'Marie',
            'course_year_section' => 'BSIT-4A',
            'birthdate' => '2001-05-15',
            'birthplace' => 'Tacloban City',
            'sex' => 'Female',
            'contact_number' => '09171234567',
            'nationality' => 'Filipino',
            'address' => '123 Sample St., Tacloban City',
            'home_address' => '123 Sample St., Tacloban City',
            'guardian_name' => 'Pedro Santos',
            'guardian_relationship' => 'Father',
            'guardian_contact' => '09179876543',
            'is_active' => true, // Already completed onboarding
        ]);

        // Seed Time Slots
        $this->call(TimeSlotSeeder::class);

        $this->command->info('Seeded: Admin (ADMIN-001 / admin123)');
        $this->command->info('Seeded: Counselor (maria.santos@tupv.edu.ph / counselor123)');
        $this->command->info('Seeded: Student 1 (TUPV-24-0001 / student123) - Inactive');
        $this->command->info('Seeded: Student 2 (TUPV-23-0042 / student123) - Active');
        $this->command->info('Seeded: Time Slots (4 slots - 2 morning, 2 afternoon)');
    }
}
