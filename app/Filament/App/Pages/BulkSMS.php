<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class BulkSMS extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';


    protected static string $view = 'filament.app.pages.bulk-s-m-s';

    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Services';
    protected static ?string $title = 'Bulk SMS';


    protected static ?string $navigationLabel = 'Bulk SMS';

    protected static ?string $slug = 'bulk-sms';



}
