<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PosSaleInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $posSale;

    /**
     * Create a new message instance.
     */
    public function __construct(\App\Models\PosSale $posSale)
    {
        $this->posSale = $posSale;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice #' . $this->posSale->invoice_number . ' from ' . ($this->posSale->branch->name ?? config('app.name')),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pos_sale_invoice',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pos_sale_receipt_pdf', ['record' => $this->posSale])
            ->setPaper(array(0, 0, 226.77, 841.89));

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Invoice-' . $this->posSale->invoice_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
