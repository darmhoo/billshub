<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class AirtimeCash extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.airtime-cash';

    protected static ?string $title = "Convert Airtime to Cash";
    protected static ?string $navigationLabel = 'Airtime to Cash';
    
}
