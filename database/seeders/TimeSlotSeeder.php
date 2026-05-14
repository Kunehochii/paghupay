<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Replaces old 90-min slots with 8 one-hour slots (8 AM–12 PM, 1–5 PM).
     */
    public function run(): void
    {
        DB::transaction(function () {
            DB::table('time_slots')->delete();

            $now = now();

            DB::table('time_slots')->insert([
                ['type' => 'morning',   'start_time' => '08:00:00', 'end_time' => '09:00:00', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['type' => 'morning',   'start_time' => '09:00:00', 'end_time' => '10:00:00', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['type' => 'morning',   'start_time' => '10:00:00', 'end_time' => '11:00:00', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['type' => 'morning',   'start_time' => '11:00:00', 'end_time' => '12:00:00', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['type' => 'afternoon', 'start_time' => '13:00:00', 'end_time' => '14:00:00', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['type' => 'afternoon', 'start_time' => '14:00:00', 'end_time' => '15:00:00', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['type' => 'afternoon', 'start_time' => '15:00:00', 'end_time' => '16:00:00', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['type' => 'afternoon', 'start_time' => '16:00:00', 'end_time' => '17:00:00', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ]);
        });
    }
}
