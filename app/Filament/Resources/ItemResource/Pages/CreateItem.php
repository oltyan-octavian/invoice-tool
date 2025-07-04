<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = Auth::id();
        return $data;
    }

}
