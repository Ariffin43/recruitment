<?php

namespace App\Mail;

use App\Models\Lamaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lamaran $lamaran) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Interview Posisi ' .
            $this->lamaran->lowongan->fptk->posisi_dibutuhkan
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.interview-scheduled',
            with: [
                'lamaran' => $this->lamaran,
            ],
        );
    }
}