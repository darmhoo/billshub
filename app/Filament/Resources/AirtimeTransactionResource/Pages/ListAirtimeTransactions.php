<?php

namespace App\Filament\Resources\AirtimeTransactionResource\Pages;

use App\Filament\Resources\AirtimeTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAirtimeTransactions extends ListRecords
{
    protected static string $resource = AirtimeTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
