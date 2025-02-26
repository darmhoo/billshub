<?php

namespace App\Filament\Resources\ElectricityTransactionResource\Pages;

use App\Filament\Resources\ElectricityTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditElectricityTransaction extends EditRecord
{
    protected static string $resource = ElectricityTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
