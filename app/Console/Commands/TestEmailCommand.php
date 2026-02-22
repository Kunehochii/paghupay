<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentInvitation;
use App\Mail\CounselorInvitation;
use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentAccepted;
use App\Mail\AppointmentRejected;
use App\Mail\AppointmentCancelled;
use App\Mail\AppointmentCompleted;
use App\Models\Appointment;
use App\Models\User;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {type} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending. Types: student, counselor, appointment-confirm, appointment-accepted, appointment-rejected, appointment-cancelled, appointment-completed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $email = $this->argument('email');

        $this->info("Testing {$type} email to {$email}...");

        try {
            switch ($type) {
                case 'student':
                    $this->testStudentInvitation($email);
                    break;

                case 'counselor':
                    $this->testCounselorInvitation($email);
                    break;

                case 'appointment-confirm':
                    $this->testAppointmentConfirmation($email);
                    break;

                case 'appointment-accepted':
                    $this->testAppointmentAccepted($email);
                    break;

                case 'appointment-rejected':
                    $this->testAppointmentRejected($email);
                    break;

                case 'appointment-cancelled':
                    $this->testAppointmentCancelled($email);
                    break;

                case 'appointment-completed':
                    $this->testAppointmentCompleted($email);
                    break;

                default:
                    $this->error("Unknown email type: {$type}");
                    $this->info("Available types: student, counselor, appointment-confirm, appointment-accepted, appointment-rejected, appointment-cancelled, appointment-completed");
                    return 1;
            }

            $this->info("✅ Email sent successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }

    private function testStudentInvitation($email)
    {
        $tempPassword = 'TestPass123!';

        Mail::to($email)->send(new StudentInvitation(
            '2021-00001-MN-0',
            $email,
            $tempPassword
        ));

        $this->line("Student invitation sent with temp password: {$tempPassword}");
    }

    private function testCounselorInvitation($email)
    {
        $tempPassword = 'CounselorPass123!';

        Mail::to($email)->send(new CounselorInvitation(
            'Dr. Test Counselor',
            $email,
            $tempPassword
        ));

        $this->line("Counselor invitation sent with temp password: {$tempPassword}");
    }

    private function testAppointmentConfirmation($email)
    {
        // Create a mock appointment for testing
        $appointment = $this->createMockAppointment($email);

        Mail::to($email)->send(new AppointmentConfirmation($appointment));

        $this->line("Appointment confirmation sent for appointment ID: {$appointment->id}");
    }

    private function testAppointmentAccepted($email)
    {
        $appointment = $this->createMockAppointment($email);

        Mail::to($email)->send(new AppointmentAccepted($appointment));

        $this->line("Appointment accepted notification sent");
    }

    private function testAppointmentRejected($email)
    {
        $appointment = $this->createMockAppointment($email);
        $reason = "Test rejection reason - Schedule conflict";

        Mail::to($email)->send(new AppointmentRejected($appointment, $reason));

        $this->line("Appointment rejected notification sent with reason: {$reason}");
    }

    private function testAppointmentCancelled($email)
    {
        $appointment = $this->createMockAppointment($email);
        $reason = "Test cancellation - Student requested cancellation";

        Mail::to($email)->send(new AppointmentCancelled($appointment, $reason));

        $this->line("Appointment cancelled notification sent");
    }

    private function testAppointmentCompleted($email)
    {
        $appointment = $this->createMockAppointment($email);

        Mail::to($email)->send(new AppointmentCompleted($appointment));

        $this->line("Appointment completed notification sent");
    }

    private function createMockAppointment($clientEmail)
    {
        // Try to find existing users or create temporary ones
        $client = User::where('role', 'client')->first();
        $counselor = User::where('role', 'counselor')->first();

        if (!$client) {
            $this->warn("No client user found in database. Using mock data.");
            $client = new User([
                'id' => 999,
                'name' => 'Test Student',
                'email' => $clientEmail,
                'role' => 'client'
            ]);
        }

        if (!$counselor) {
            $this->warn("No counselor user found in database. Using mock data.");
            $counselor = new User([
                'id' => 998,
                'name' => 'Dr. Maria Santos',
                'email' => 'maria.santos@tupv.edu.ph',
                'role' => 'counselor'
            ]);
        }

        // Create a mock appointment without saving to database
        $appointment = new Appointment([
            'id' => 999,
            'client_id' => $client->id,
            'counselor_id' => $counselor->id,
            'scheduled_at' => now()->addDays(3)->setTime(10, 0),
            'reason' => 'Test appointment - Academic stress and time management concerns',
            'status' => 'pending'
        ]);

        // Manually set relationships
        $appointment->setRelation('client', $client);
        $appointment->setRelation('counselor', $counselor);

        return $appointment;
    }
}
