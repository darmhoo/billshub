<?php

namespace App\Filament\Resources\ElectricityProviderResource\Pages;

use App\Filament\Resources\ElectricityProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListElectricityProviders extends ListRecords
{
    protected static string $resource = ElectricityProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
