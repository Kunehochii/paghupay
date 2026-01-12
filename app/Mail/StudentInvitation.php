<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class StudentInvitation extends Mailable
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
     * The student's TUPV ID.
     */
    public string $tupvId;

    /**
     * The student's email address.
     */
    public ?string $email;

    /**
     * The temporary password.
     */
    public string $tempPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(string $tupvId, ?string $email, string $tempPassword)
    {
        $this->tupvId = $tupvId;
        $this->email = $email;
        $this->tempPassword = $tempPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Paghupay - TUP-V Guidance & Counseling System',
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
            view: 'emails.student-invitation',
            with: [
                'tupvId' => $this->tupvId,
                'email' => $this->email,
                'tempPassword' => $this->tempPassword,
                'loginUrl' => route('login'),
            ],
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
