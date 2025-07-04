<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Item name:')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('price_without_tax')
                    ->prefix('€')
                    ->label('Price (without tax):')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('discount')
                    ->label('Discount (%):')
                    ->required()
                    ->integer()
                    ->default(0),

                Forms\Components\TextInput::make('final_price')
                    ->prefix('€')
                    ->disabled()
                    ->label('Final Price')
                    ->numeric()
                    ->readOnly()
                    ->formatStateUsing(function ($state, callable $get) {
                        return number_format($get('final_price'), 2);
                    })
                ->suffixAction(
                    Forms\Components\Actions\Action::make('calculateFinalPrice')
                        ->label('Calculate')
                        ->icon('heroicon-o-calculator')
                        ->action(function (callable $get, callable $set) {
                            $price = floatval($get('price_without_tax') ?? 0);
                            $discount = floatval($get('discount') ?? 0);
                            $final = $price * (1 - $discount / 100);

                            $set('final_price', round($final, 2));
                        }),
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Item name')
                    ->searchable()
                    ->sortable()
                    ->limit(100),
                Tables\Columns\TextColumn::make('price_without_tax')
                ->label('Price (without tax)')
                ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                ->label('Discount (%)')
                ->sortable(),
                Tables\Columns\TextColumn::make('final_price')
                ->label('Final Price (without tax)')
                ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                ->label('Last updated')

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
