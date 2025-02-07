<?php

namespace App\Filament\Resources\VirtualAccountResource\Pages;

use App\Filament\Resources\VirtualAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVirtualAccounts extends ListRecords
{
    protected static string $resource = VirtualAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
