<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAchievement extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $achievementTitle;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $achievementTitle
     */
    public function __construct(User $user, $achievementTitle)
    {
        $this->user = $user;
        $this->achievementTitle = $achievementTitle;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations on Your New Achievement!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_achievement', // Assuming you have a view at resources/views/emails/new_badge.blade.php
            with: [
                'userName' => $this->user->name,
                'achievementTitle' => $this->achievementTitle
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
