<?php

namespace App\Filament\App\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // protected static ?string $pollingInterval = '10s';
    protected ?string $heading = 'Analytics';

    protected ?string $description = 'An overview of some analytics.';
    protected function getStats(): array
    {

        //
        // dd(auth()->user()->id);
        $user = auth()->user();


        return [
            Stat::make('Wallet', '₦' . number_format($user->wallet_balance, 2))
                ->color('success')
                ->url('/filament/resources/users/' . auth()->user()->id)
                ->description('How much you get in your wallet')
                ->descriptionIcon('heroicon-o-currency-dollar', IconPosition::After),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Average time on page', '3:12'),

        ];
    }
}
