<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class CableTv extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.cable-tv';
    protected static ?string $navigationGroup = 'Services';


    protected static ?int $navigationSort = 5;

}
