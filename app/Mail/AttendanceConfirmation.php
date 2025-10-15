<?php

namespace App\Mail;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendanceConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $attendance;
    public $event;
    public $member;
    public $churchName;

    /**
     * Create a new message instance.
     */
    public function __construct(Attendance $attendance, Event $event, Member $member)
    {
        $this->attendance = $attendance;
        $this->event = $event;
        $this->member = $member;
        $this->churchName = \App\Models\Setting::getValue('organization_name', 'general', 'Beulah Family Church');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Attendance Confirmed - ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.attendance-confirmation',
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
