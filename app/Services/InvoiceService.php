<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Models\Invoice;
use App\Models\Item;
use App\Mail\InvoiceMailable;

class InvoiceService
{
    public function generatePdf(Invoice $invoice)
    {
        $customer = $invoice->customer;

        $items = $invoice->items->map(function ($item) {
            $model = Item::find($item->item_id);
            return [
                'name' => $model->name,
                'price' => $model->final_price,
                'unit_type' => $item->unit_type,
                'quantity' => $item->quantity,
                'total' => $model->final_price * $item->quantity,
            ];
        });

        $total = $items->sum('total');

        return Pdf::loadView('pdfs.invoice', [
            'customer' => $customer,
            'items' => $items,
            'total' => $total,
            'name' => $invoice->name,
            'due_date' => $invoice->due_date,
            'tax' => $invoice->tax,
        ]);
    }

    public function sendInvoiceEmail(Invoice $invoice): void
    {
        $pdf = $this->generatePdf($invoice);

        $customer = $invoice->customer;
        $items = $invoice->items->map(function ($item) {
            $model = Item::find($item->item_id);
            return [
                'name' => $model->name,
                'price' => $model->final_price,
                'unit_type' => $item->unit_type,
                'quantity' => $item->quantity,
                'total' => $model->final_price * $item->quantity,
            ];
        });

        $total = $items->sum('total');

        Mail::to($customer->email)->send(new InvoiceMailable(
            $customer,
            $items,
            $total,
            $invoice->name,
            $invoice->due_date,
            $invoice->tax
        ));
    }
}

