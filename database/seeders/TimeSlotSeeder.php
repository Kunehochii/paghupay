<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeSlots = [
            // Morning slots
            [
                'type' => 'morning',
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
                'is_active' => true,
            ],
            [
                'type' => 'morning',
                'start_time' => '10:30:00',
                'end_time' => '12:00:00',
                'is_active' => true,
            ],
            // Afternoon slots
            [
                'type' => 'afternoon',
                'start_time' => '13:00:00',
                'end_time' => '14:30:00',
                'is_active' => true,
            ],
            [
                'type' => 'afternoon',
                'start_time' => '14:30:00',
                'end_time' => '16:00:00',
                'is_active' => true,
            ],
        ];

        foreach ($timeSlots as $slot) {
            TimeSlot::firstOrCreate(
                [
                    'type' => $slot['type'],
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                ],
                $slot
            );
        }
    }
}
