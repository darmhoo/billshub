<?php

namespace App\Filament\Resources\ElectricityProviderResource\Pages;

use App\Filament\Resources\ElectricityProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditElectricityProvider extends EditRecord
{
    protected static string $resource = ElectricityProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
