<?php

namespace App\Filament\Resources\AirtimeTransactionResource\Pages;

use App\Filament\Resources\AirtimeTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAirtimeTransactions extends ListRecords
{
    protected static string $resource = AirtimeTransactionResource::class;

    protected static ?string $title = "Airtime Transactions";


    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
