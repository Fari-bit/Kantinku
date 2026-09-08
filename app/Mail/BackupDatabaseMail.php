<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BackupDatabaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $filePath;
    public string $fileName;

    public function __construct(string $filePath, string $fileName)
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Backup Database - ' . $this->fileName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.backup-database',
            with: [
                'fileName' => $this->fileName,
                'createdAt' => now()->format('d M Y, H:i'),
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->filePath)->as($this->fileName)->withMime('application/sql'),
        ];
    }
}