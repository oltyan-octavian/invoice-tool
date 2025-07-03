<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $items;
    public $total;
    public $name;
    public $due_date;
    public $tax;
    /**
     * Create a new message instance.
     */
    public function __construct($customer, $items, $total, $name, $due_date, $tax)
    {
        $this->customer = $customer;
        $this->items = $items;
        $this->total = $total;
        $this->name = $name;
        $this->due_date = $due_date;
        $this->tax = $tax;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Mailable',
        );
    }

    public function build(): InvoiceMailable
    {
        // Generate PDF as raw string
        $pdf = Pdf::loadView('pdfs.invoice', [
            'customer' => $this->customer,
            'items' => $this->items,
            'total' => $this->total,
            'name' => $this->name,
            'due_date' => $this->due_date,
            'tax' => $this->tax,
        ])->output();

        return $this->subject('Your Invoice')
            ->view('emails.invoice')
            ->attachData($pdf, 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
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
