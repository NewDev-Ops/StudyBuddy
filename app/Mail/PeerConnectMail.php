<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class PeerConnectMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $senderName;
    public string $senderUniversity;
    public string $subjectName;
    public ?string $note;

    public function __construct(
        public User $sender,
        public User $receiver,
        string $subjectName,
        ?string $note,
    ) {
        $this->senderName = $sender->name;
        $this->senderUniversity = $sender->university?->name ?? 'Unknown University';
        $this->subjectName = $subjectName;
        $this->note = $note;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [new Address($this->sender->email, $this->sender->name)],
            subject: "{$this->sender->name} wants to connect on Revisor",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.peer-connect',
        );
    }
}
