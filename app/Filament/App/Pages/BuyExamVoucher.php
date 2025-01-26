<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class BuyExamVoucher extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.buy-exam-voucher';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationGroup = 'Services';

}
