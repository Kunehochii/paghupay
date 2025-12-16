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
        // Create Admin User
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@tup.edu.ph',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Sample Counselor
        $counselor = User::create([
            'name' => 'Dr. Maria Santos',
            'email' => 'maria.santos@tup.edu.ph',
            'password' => Hash::make('counselor123'),
            'role' => 'counselor',
            'is_active' => true,
        ]);

        CounselorProfile::create([
            'user_id' => $counselor->id,
            'position' => 'Head Psychologist',
            'device_token' => null, // Set on first login
        ]);

        // Create Sample Client (Student)
        User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan.delacruz@tup.edu.ph',
            'password' => Hash::make('student123'),
            'role' => 'client',
            'is_active' => false, // Needs onboarding
        ]);

        // Seed Time Slots
        $this->call(TimeSlotSeeder::class);

        $this->command->info('Seeded: Admin (admin@tup.edu.ph / admin123)');
        $this->command->info('Seeded: Counselor (maria.santos@tup.edu.ph / counselor123)');
        $this->command->info('Seeded: Student (juan.delacruz@tup.edu.ph / student123)');
        $this->command->info('Seeded: Time Slots (4 slots - 2 morning, 2 afternoon)');
    }
}
