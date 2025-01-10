<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings as ClustersSettings;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.settings';
    protected static ?string $cluster = ClustersSettings::class;
    protected static ?string $navigationGroup = 'Wallet';
}
