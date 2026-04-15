<?php

namespace App\Mail;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReturnConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Peminjaman $peminjaman,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pengembalian Alat',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.peminjaman.return-confirmed',
        );
    }
}
