<?php

namespace App\Filament\Resources\VirtualAccountResource\Pages;

use App\Filament\Resources\VirtualAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVirtualAccount extends EditRecord
{
    protected static string $resource = VirtualAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
