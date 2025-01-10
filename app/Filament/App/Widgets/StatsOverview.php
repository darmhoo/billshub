<?php

namespace App\Filament\App\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected ?string $heading = 'Analytics';

    protected ?string $description = 'An overview of some analytics.';
    protected function getStats(): array
    {

        //
        // dd(auth()->user()->id);
        $user = User::find(auth()->user()->id)->first();


        return [
            Stat::make('Wallet', '₦' . $user->wallet_balance)->color('success')->description('How much you get in your wallet')->descriptionIcon('heroicon-o-currency-dollar', IconPosition::After),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Average time on page', '3:12'),
        ];
    }
}
