<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! $data['is_legal_entity']) {
            $data['company_name'] = null;
            $data['company_street'] = null;
            $data['company_city'] = null;
            $data['company_zip'] = null;
            $data['company_country'] = null;
            $data['company_vat'] = null;
        }
        else{
            $data['street'] = null;
            $data['city'] = null;
            $data['zip'] = null;
            $data['country'] = null;
        }

        logger($data);
        return $data;
    }


}
