<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use App\Models\Customer;
use App\Models\Item;
use Filament\Forms\Components\{DatePicker, Select, TextInput, Repeater};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMailable;
use Filament\Notifications\Notification;
use Svg\Tag\Text;

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

        $customer = Customer::find($data['customer_id']);
        logger($customer);
        $items = collect($data['items'])->map(function ($item) {
            $model = Item::find($item['item_id']);
            return [
                'name' => $model->name,
                'price' => $model->final_price,
                'unit_type' => $item['unit_type'],
                'quantity' => $item['quantity'],
                'total' => $model->final_price * $item['quantity'],
            ];
        });

        $name = $data['invoice_name'];
        $due_date = $data['due_date'];
        $tax = (float) $data['tax'];

        $total = $items->sum('total');

        $pdf = Pdf::loadView('pdfs.invoice', [
            'customer' => $customer,
            'items' => $items,
            'total' => $total,
            'name' => $name,
            'due_date' => $due_date,
            'tax' => $tax,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'invoice.pdf');
    }

    public function sendInvoice()
    {
        $data = $this->form->getState();
        app()->setLocale($this->language);

        $customer = Customer::find($data['customer_id']);
        $items = collect($data['items'])->map(function ($item) {
            $model = Item::find($item['item_id']);
            return [
                'name' => $model->name,
                'price' => $model->final_price,
                'unit_type' => $item['unit_type'],
                'quantity' => $item['quantity'],
                'total' => $model->final_price * $item['quantity'],
            ];
        });

        $name = $data['invoice_name'];
        $due_date = $data['due_date'];
        $tax = (float) $data['tax'];

        $total = $items->sum('total');

        Mail::to($customer->email)->send(new InvoiceMailable($customer, $items, $total, $name, $due_date, $tax));

        Notification::make()
            ->title('Invoice emailed successfully!')
            ->success()
            ->send();
    }
}
