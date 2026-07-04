<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderBlastMail extends Mailable
{
    use Queueable, SerializesModels;

    public $judul;
    public $pesan;
    public $nama_siswa;

    /**
     * Create a new message instance.
     */
    public function __construct($judul, $pesan, $nama_siswa)
    {
        $this->judul = $judul;
        $this->pesan = $pesan;
        $this->nama_siswa = $nama_siswa;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Sekolah: ' . $this->judul,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder_blast',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
