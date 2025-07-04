<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{Repeater, TextInput, Select, DatePicker, Toggle};
use Filament\Tables\Columns\TextColumn;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('customer_id')
                ->relationship('customer', 'name')
                ->required(),

            TextInput::make('name')->label('Invoice Name')->required(),

            Select::make('language')->options([
                'en' => 'English',
                'ro' => 'Romanian',
            ])->required(),

            DatePicker::make('due_date')->required(),

            TextInput::make('tax')->numeric()->required(),

            Toggle::make('is_paid')->label('Paid')->required(),

            Repeater::make('items')
                ->relationship()
                ->schema([
                    Select::make('item_id')
                        ->label('Item')
                        ->relationship('item', 'name')
                        ->required(),

                    TextInput::make('unit_type')
                        ->required(),

                    TextInput::make('quantity')
                        ->numeric()
                        ->required(),
                ])
                ->columns(3)
                ->label('Invoice Items')
                ->minItems(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('customer.name')->label('Customer'),
                TextColumn::make('name')->label('Invoice Name'),
                TextColumn::make('language'),
                TextColumn::make('due_date')->date(),
                TextColumn::make('tax'),
                ToggleColumn::make('is_paid')->label('Paid'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
