<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBadge extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $badgeTitle;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $badgeTitle
     */
    public function __construct(User $user, $badgeTitle)
    {
        $this->user = $user;
        $this->badgeTitle = $badgeTitle;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Badge Unlocked',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_badge', // Assuming you have a view at resources/views/emails/new_badge.blade.php
            with: [
                'userName' => $this->user->name,
                'badgeTitle' => $this->badgeTitle,
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
