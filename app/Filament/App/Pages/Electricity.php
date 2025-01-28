<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Electricity extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static string $view = 'filament.app.pages.electricity';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Services';



}
