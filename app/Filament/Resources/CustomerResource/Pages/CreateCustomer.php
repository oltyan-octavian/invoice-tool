<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! $data['is_legal_entity']) {
            $data['company_name'] = null;
            $data['company_vat'] = null;
        }

        return $data;
    }
    public function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = Auth::id();
        return $data;
    }
}
