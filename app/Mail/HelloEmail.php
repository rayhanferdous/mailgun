<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HelloEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $attachmentPath;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     * @param string|null $attachmentPath
     */
    public function __construct($mailData, $attachmentPath = null)
    {
        $this->mailData = $mailData;
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailData['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'demo_mail',
            with: ['subject' => $this->mailData['subject'], 'body' => $this->mailData['body']]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments()
    {
        if ($this->attachmentPath) {
            return [
                storage_path('app/' . $this->attachmentPath),
            ];
        }

        return [];
    }
}