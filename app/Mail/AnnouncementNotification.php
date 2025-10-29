<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $announcement;
    public $churchName;

    /**
     * Create a new message instance.
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
        $this->churchName = \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $priority = '';
        if ($this->announcement->priority === 'urgent' || $this->announcement->priority === 'high') {
            $priority = '[' . strtoupper($this->announcement->priority) . '] ';
        }

        return new Envelope(
            subject: $priority . $this->announcement->title . ' - ' . $this->churchName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.announcement-notification',
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
