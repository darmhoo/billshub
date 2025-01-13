<?php

namespace App\Filament\Resources\AirtimeBundleResource\Pages;

use App\Filament\Resources\AirtimeBundleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAirtimeBundle extends EditRecord
{
    protected static string $resource = AirtimeBundleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
