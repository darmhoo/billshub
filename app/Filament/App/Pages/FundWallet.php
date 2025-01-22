<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\AccountFundingList;
use App\Models\User;
use Filament\Pages\Page;

class FundWallet extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Wallet';


    protected static string $view = 'filament.app.pages.fund-wallet';

    protected static ?string $title = 'Fund Wallet';

    protected static ?int $navigationSort = 2;


    protected function getViewData(): array
    {
        $user = User::find(auth()->user()->id);
        // dd($user);
        return [
            'bank_accounts' => $user->accounts
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         AccountFundingList::class
    //     ];
    // }
}
