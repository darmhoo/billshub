<?php

namespace App\Filament\Resources\DataBundleResource\Pages;

use App\Filament\Resources\DataBundleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataBundles extends ListRecords
{
    protected static string $resource = DataBundleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
