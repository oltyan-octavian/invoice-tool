<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Services\InvoiceService;
use Filament\Pages\Actions\Action;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Generate PDF')
                ->action(function () {
                    $this->save();

                    $pdf = app(InvoiceService::class)->generatePdf($this->record);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, 'invoice.pdf');
                }),

            Action::make('Send Email')
                ->requiresConfirmation()
                ->action(function () {
                    $this->save();

                    app(InvoiceService::class)->sendInvoiceEmail($this->record);

                    Notification::make()
                        ->title('Invoice sent!')
                        ->success()
                        ->send();
                }),
        ];
    }
}
