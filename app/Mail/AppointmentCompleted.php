<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\CaseLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCompleted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The appointment instance.
     */
    public Appointment $appointment;

    /**
     * The case log instance.
     */
    public CaseLog $caseLog;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, CaseLog $caseLog)
    {
        $this->appointment = $appointment->load(['counselor', 'client']);
        $this->caseLog = $caseLog;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Completed - Paghupay',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-completed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
