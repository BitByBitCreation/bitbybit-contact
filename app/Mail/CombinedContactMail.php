<?php 

namespace App\Mail;

use App\DTO\MessageAnalysisDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CombinedContactMail extends Mailable
{
    use Queueable, SerializesModels;

    private const SUBJECT_PREFIX = 'Contact request + AI response: ';
    private const VIEW_VAR_DATA = 'data';
    private const VIEW_VAR_DRAFT = 'draft';
    private const VIEW = 'emails.combined_contact';


    public function __construct(
        public readonly MessageAnalysisDTO $dto,
        public readonly string $draft
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: self::SUBJECT_PREFIX . $this->dto->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: self::VIEW,
            with: [
                self::VIEW_VAR_DATA => $this->dto,
                self::VIEW_VAR_DRAFT => $this->draft
            ],
        );
    }
}
