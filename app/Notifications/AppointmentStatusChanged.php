<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        private Appointment $appointment,
        private string $newStatus,
        private ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'status' => $this->newStatus,
            'counselor_name' => $this->appointment->counselor->name,
            'scheduled_at' => $this->appointment->scheduled_at->toISOString(),
            'reason' => $this->reason,
            'message' => $this->buildMessage(),
        ];
    }

    private function buildMessage(): string
    {
        $counselor = $this->appointment->counselor->name;

        return match ($this->newStatus) {
            'accepted' => "Your appointment with {$counselor} has been accepted.",
            'cancelled' => "Your appointment with {$counselor} has been cancelled.",
            'completed' => "Your session with {$counselor} has been marked as completed.",
            'rescheduled' => "Your appointment with {$counselor} has been rescheduled.",
            default => "Your appointment status has been updated to {$this->newStatus}.",
        };
    }
}
