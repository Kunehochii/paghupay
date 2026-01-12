<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class AppointmentAccepted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        return new Headers(
            messageId: uniqid('paghupay-', true) . '@' . parse_url(config('app.url'), PHP_URL_HOST),
            references: [],
            text: [
                'X-Mailer' => 'Paghupay/1.0',
                'X-Priority' => '3',
                'List-Unsubscribe' => '<mailto:' . config('mail.from.address') . '?subject=Unsubscribe>',
            ],
        );
    }

    /**
     * The appointment instance.
     */
    public Appointment $appointment;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment->load(['counselor', 'client']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Accepted - Paghupay',
            replyTo: [
                new Address(config('mail.from.address'), 'TUP-V Guidance Office'),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-accepted',
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
