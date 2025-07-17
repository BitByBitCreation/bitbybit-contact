<?php

namespace App\Mail;

use App\DTO\MessageAnalysisDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    private const SUBJECT_PREFIX = 'Your message was received - ';
    private const VIEW_VAR_DATA = 'data';
    private const VIEW = 'emails.confirmation';
    private const APP_NAME = 'app.name';

    public function __construct(
        public readonly MessageAnalysisDTO $dto
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: self::SUBJECT_PREFIX . config(self::APP_NAME)
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: self::VIEW, 
            with: [
                self::VIEW_VAR_DATA => $this->dto,
            ],
        );
    }

}