<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(100),

                Forms\Components\TextInput::make('phone_number')
                    ->required()
                    ->maxLength(15),

                Forms\Components\Toggle::make('is_legal_entity')
                    ->label('Legal entity')
                    ->live(),

                Forms\Components\TextInput::make('street')
                    ->requiredIf('is_legal_entity', false)
                    ->dehydrated()
                    ->nullable()
                    ->default(null)
                    ->visible(fn (callable $get) => $get('is_legal_entity') === false)
                    ->maxLength(255),

                Forms\Components\TextInput::make('city')
                    ->requiredIf('is_legal_entity', false)
                    ->dehydrated()
                    ->nullable()
                    ->default(null)
                    ->visible(fn (callable $get) => $get('is_legal_entity') === false)
                    ->maxLength(255),

                Forms\Components\TextInput::make('zip')
                    ->requiredIf('is_legal_entity', false)
                    ->dehydrated()
                    ->nullable()
                    ->default(null)
                    ->visible(fn (callable $get) => $get('is_legal_entity') === false)
                    ->maxLength(255),

                Forms\Components\TextInput::make('country')
                    ->requiredIf('is_legal_entity', false)
                    ->dehydrated()
                    ->nullable()
                    ->default(null)
                    ->visible(fn (callable $get) => $get('is_legal_entity') === false)
                    ->maxLength(255),

                Forms\Components\TextInput::make('company_name')
                    ->requiredIf('is_legal_entity', true)
                    ->dehydrated()
                    ->nullable()
                    ->default(null)
                    ->visible(fn (callable $get) => $get('is_legal_entity') === true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('company_street')
                    ->requiredIf('is_legal_entity', true)
                    ->dehydrated()
                    ->nullable()
                    ->default(null)
                    ->visible(fn (callable $get) => $get('is_legal_entity') === true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('company_city')
                    ->requiredIf('is_legal_entity', true)
                    ->dehydrated()
                    ->nullable()
                    ->default(null)
                    ->visible(fn (callable $get) => $get('is_legal_entity') === true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('company_zip')
                    ->requiredIf('is_legal_entity', true)
                    ->dehydrated()
                    ->nullable()
                    ->default(null)
                    ->visible(fn (callable $get) => $get('is_legal_entity') === true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('company_country')
                    ->requiredIf('is_legal_entity', true)
                    ->dehydrated()
                    ->nullable()
                    ->default(null)
                    ->visible(fn (callable $get) => $get('is_legal_entity') === true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('company_vat')
                    ->label('Company VAT no.')
                    ->requiredIf('is_legal_entity', true)
                    ->dehydrated()
                    ->nullable()
                    ->default(null)
                    ->visible(fn (callable $get) => $get('is_legal_entity') === true)
                    ->maxLength(15),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->wrap()
                ->sortable()
                ->limit(100),

                Tables\Columns\TextColumn::make('street')
                ->wrap()
                ->limit(255)
                ->extraAttributes(['style' => 'width: 20rem'])
                ->extraHeaderAttributes(['style' => 'width: 20rem']),

                Tables\Columns\TextColumn::make('city')
                    ->wrap()
                    ->limit(255)
                    ->extraAttributes(['style' => 'width: 20rem'])
                    ->extraHeaderAttributes(['style' => 'width: 20rem']),

                Tables\Columns\TextColumn::make('zip')
                    ->wrap()
                    ->limit(255)
                    ->extraAttributes(['style' => 'width: 20rem'])
                    ->extraHeaderAttributes(['style' => 'width: 20rem']),

                Tables\Columns\TextColumn::make('country')
                    ->wrap()
                    ->limit(255)
                    ->extraAttributes(['style' => 'width: 20rem'])
                    ->extraHeaderAttributes(['style' => 'width: 20rem']),

                Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->limit(100),

                Tables\Columns\TextColumn::make('phone_number')
                ->searchable()
                ->limit(15),

                Tables\Columns\ToggleColumn::make('is_legal_entity')
                ->disabled(),

                Tables\Columns\TextColumn::make('company_name')
                ->searchable()
                ->sortable()
                ->wrap()
                ->limit(255)
                ->extraAttributes(['style' => 'width: 20rem'])
                ->extraHeaderAttributes(['style' => 'width: 20rem']),

                Tables\Columns\TextColumn::make('company_street')
                ->searchable()
                ->wrap()
                ->limit(255)
                ->extraAttributes(['style' => 'width: 20rem'])
                ->extraHeaderAttributes(['style' => 'width: 20rem']),

                Tables\Columns\TextColumn::make('company_city')
                    ->searchable()
                    ->wrap()
                    ->limit(255)
                    ->extraAttributes(['style' => 'width: 20rem'])
                    ->extraHeaderAttributes(['style' => 'width: 20rem']),

                Tables\Columns\TextColumn::make('company_zip')
                    ->searchable()
                    ->wrap()
                    ->limit(255)
                    ->extraAttributes(['style' => 'width: 20rem'])
                    ->extraHeaderAttributes(['style' => 'width: 20rem']),

                Tables\Columns\TextColumn::make('company_country')
                    ->searchable()
                    ->wrap()
                    ->limit(255)
                    ->extraAttributes(['style' => 'width: 20rem'])
                    ->extraHeaderAttributes(['style' => 'width: 20rem']),

                Tables\Columns\TextColumn::make('company_vat')
                ->searchable()
                ->label('Company VAT')
                ->limit(15),

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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
