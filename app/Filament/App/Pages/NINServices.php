<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class NINServices extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static string $view = 'filament.app.pages.n-i-n-services';
    protected static ?int $navigationSort = 8;
    protected static ?string $navigationGroup = 'Services';

    protected static ?string $navigationLabel = 'NIN Services';
    protected static ?string $title = 'NIN Services';


    protected static ?string $slug = 'nin-services';


}
