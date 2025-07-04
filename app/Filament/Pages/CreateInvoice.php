<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Item;
use App\Services\InvoiceService;
use Filament\Forms\Components\{DatePicker, Select, TextInput, Repeater, Toggle};
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class CreateInvoice extends Page implements \Filament\Forms\Contracts\HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.create-invoice';

    public string $language = 'en';
    public $customer_id;
    public $items = [];
    public $invoice_name;
    public $due_date;
    public $tax;

    public function mount(): void
    {
        $this->form->fill([]);
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('language')
                ->label('Language')
                ->options([
                    'en' => 'English',
                    'ro' => 'Romanian',
                ])
                ->default('en')
                ->live()
                ->afterStateUpdated(function (callable $set, $state) {
                    app()->setLocale($state);
                }),

            Select::make('customer_id')
                ->label('Select Customer')
                ->required()
                ->options(Customer::all()->pluck('name', 'id')),

            TextInput::make('invoice_name')
                ->label('Invoice name')
                ->required(),

            Repeater::make('items')
                ->label('Invoice Items')
                ->schema([
                    Select::make('item_id')
                        ->label('Item')
                        ->options(Item::all()->pluck('name', 'id'))
                        ->required(),

                    TextInput::make('unit_type')
                        ->label('Unit type')
                        ->required(),

                    TextInput::make('quantity')
                        ->numeric()
                        ->default(1)
                        ->required(),
                ])
                ->default([])
                ->columns(2)
                ->minItems(1),

            DatePicker::make('due_date')
                ->label('Due Date')
                ->required(),

            TextInput::make('tax')
                ->label('Tax')
                ->numeric()
                ->required(),
        ];
    }

    public function generatePdf()
    {
        $data = $this->form->getState();
        app()->setLocale($this->language);

        $invoice = $this->saveInvoiceToDatabase($data);
        $pdf = app(InvoiceService::class)->generatePdf($invoice);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'invoice.pdf');
    }

    public function sendInvoice()
    {
        $data = $this->form->getState();
        app()->setLocale($this->language);

        $invoice = $this->saveInvoiceToDatabase($data);
        app(InvoiceService::class)->sendInvoiceEmail($invoice);

        Notification::make()
            ->title('Invoice emailed successfully!')
            ->success()
            ->send();
    }

    private function saveInvoiceToDatabase(array $data)
    {
        $invoice = \App\Models\Invoice::create([
            'customer_id' => $data['customer_id'],
            'name' => $data['invoice_name'],
            'language' => $data['language'],
            'due_date' => $data['due_date'],
            'tax' => $data['tax'],
            'is_paid' => false,
        ]);

        foreach ($data['items'] as $item) {
            $invoice->items()->create([
                'item_id' => $item['item_id'],
                'unit_type' => $item['unit_type'],
                'quantity' => $item['quantity'],
            ]);
        }

        return $invoice;
    }

    public function saveInvoiceWrapper()
    {
        $data = $this->form->getState();
        app()->setLocale($this->language);

        $this->saveInvoiceToDatabase($data);

        Notification::make()
            ->title('Invoice saved successfully!')
            ->success()
            ->send();
    }
}
