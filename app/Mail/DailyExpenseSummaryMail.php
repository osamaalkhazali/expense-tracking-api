<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class DailyExpenseSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $summary;
    public $total;
    public $date;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $summary, $total, Carbon $date)
    {
        $this->user = $user;
        $this->summary = $summary;
        $this->total = $total;
        $this->date = $date;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Expense Summary - ' . $this->date->format('M d, Y'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-expense-summary',
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
