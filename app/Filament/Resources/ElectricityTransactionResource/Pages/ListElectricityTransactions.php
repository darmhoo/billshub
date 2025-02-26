<?php

namespace App\Filament\Resources\ElectricityTransactionResource\Pages;

use App\Filament\Resources\ElectricityTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListElectricityTransactions extends ListRecords
{
    protected static string $resource = ElectricityTransactionResource::class;
    protected static ?string $title = "Electricity Transactions";


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
