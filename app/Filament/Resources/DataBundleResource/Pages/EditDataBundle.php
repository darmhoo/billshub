<?php

namespace App\Filament\Resources\DataBundleResource\Pages;

use App\Filament\Resources\DataBundleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataBundle extends EditRecord
{
    protected static string $resource = DataBundleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
