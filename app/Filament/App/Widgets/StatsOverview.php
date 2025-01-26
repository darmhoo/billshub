<?php

namespace App\Filament\App\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // protected static ?string $pollingInterval = '10s';


    protected ?string $heading = '';

    protected function getDescription(): string|null
    {
        return 'Welcome back, ' . auth()->user()->name;
    }
    protected function getStats(): array
    {

        //
        // dd(auth()->user()->id);
        $user = auth()->user();


        return [
            Stat::make('Wallet', '₦' . number_format($user->wallet_balance, 2))
                ->color('success')
                ->description('Your wallet balance')
                ->descriptionIcon('heroicon-o-wallet', IconPosition::Before),

            Stat::make('Account Type', $user->accountType->name)
                ->color('success')
                ->description('Upgrade your account')
                ->descriptionIcon('heroicon-o-chevron-double-up', IconPosition::Before),


        ];
    }
}
